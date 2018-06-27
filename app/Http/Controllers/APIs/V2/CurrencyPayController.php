<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use DB;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\NativePayOrder;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Zhiyi\Plus\Packages\Currency\Processes\Recharge;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

/**
 * 原生积分充值
 * Class CurrencyPayController.
 */
class CurrencyPayController extends Controller
{
    protected $ratio = 1;

    /*
     * 创建支付订单
     */
    public function __construct()
    {
        $ratio = CommonConfig::where('namespace', 'currency')
            ->where('name', 'currency:recharge-ratio')
            ->value('value');

        $ratio && $this->ratio = $ratio;
    }

    public function entry(Request $request, ApplicationContract $app, ResponseContract $response)
    {
        $type = $request->input('type');
        if (! in_array($type, ['AlipayOrder', 'AlipayWapOrder', 'WechatOrder', 'WechatWapOrder'])) {
            return $response->json(['message' => '非法请求'], 422);
        }

        return $app->call([$this, 'get'.$type]);
    }

    /**
     * @param Request  $request
     * @param Carbon   $dateTime
     * @param Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlipayOrder(Request $request, ResponseFactory $response, NativePayOrder $order)
    {
        $user = $request->user();
        $amount = $request->input('amount', 0);
        $from = $request->input('from');
        $config = config('newPay.alipay');
        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $gateWay = Omnipay::create('Alipay_AopApp');
        $gateWay->setSignType($config['signType']);
        $gateWay->setAppId($config['appId']);
        $gateWay->setPrivateKey($config['secretKey']);
        $gateWay->setAlipayPublicKey($config['publicKey']);
        $gateWay->setNotifyUrl(config('app.url', '/api/v2/alipayCurrency/notify'));

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).config('newPay.sign');
        $order->subject = '积分充值';
        $order->content = sprintf('在%s充值积分：%d', config('app.name'), $amount * $this->ratio);
        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id;
        $order->from = $from;
        $order->type = 'alipay';
        $walletCharge = $this->createChargeModel($request, 'Alipay-Native');
        $currencyOrder = $this->createOrderModel($user->id, intval($amount), 'Native-Alipay', $order->subject);

        $result = $gateWay->purchase()->setBizContent([
            'subject'      => $order->subject,
            'out_trade_no' => $order->out_trade_no,
            'total_amount' => $order->amount / 100,
            'product_code' => $order->product_code,
            'body' => $order->content,
            'timeout_express' => '10m',
            'charge_type' => 'currency',
        ])->send();

        if ($result->isSuccessful()) {
            return DB::transaction(function () use ($order, $walletCharge, $response, $result, $currencyOrder) {
                try {
                    $order->save();
                    $currencyOrder->target_id = $order->id;
                    $walletCharge->charge_id = $order->id;
                    $walletCharge->save();
                    $currencyOrder->save();

                    return $response->json(['message' => '订单创建成功', 'data' => $result->getOrderString()], 201);
                } catch (Exception $e) {
                    DB::rollback();

                    return $response->json(['message' => '创建支付宝订单失败'], 422);
                }
            }, 1);
        }

        return $response->json(['message' => '创建支付宝订单失败'], 422);
    }

    public function getAlipayWapOrder(Request $request, Carbon $dateTime, ResponseFactory $response, NativePayOrder $order)
    {
        $user = $request->user();
        $amount = $request->input('amount', 0);
        $redirect = $request->input('redirect', '');
        $from = intval($request->input('from', 0));

        $isUrl = $request->input('url', 1);

        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }
        if ($from !== 1 && $from !== 2) {
            return $response->json(['message' => '请求来源非法'], 403);
        }
        $config = array_filter(config('newPay.alipay'));
        // 支付宝配置必须包含signType, appId, secretKey, publicKey, 缺一不可
        if (count($config) < 4) {
            return $response->json(['message' => '系统错误,请联系小助手'], 500);
        }
        $gateWayName = $from === 1 ? 'Alipay_AopPage' : 'Alipay_AopWap';
        $gateWay = Omnipay::create($gateWayName);
        // 签名方法
        $gateWay->setSignType($config['signType']);
        // appId
        $gateWay->setAppId($config['appId']);
        // 密钥
        $gateWay->setPrivateKey($config['secretKey']);
        // 公钥
        $gateWay->setAlipayPublicKey($config['publicKey']);
        // 通知地址
        $gateWay->setNotifyUrl(config('app.url').'/api/v2/alipayCurrency/notify');
        // 支付成功后返回地址
        $gateWay->setReturnUrl($redirect);

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).config('newPay.sign');
        $order->subject = '积分充值';
        $order->content = sprintf('在%s充值积分%d', config('app.name'), $amount * $this->ratio);
        $order->type = 'alipay';
        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id ?? 0;
        $order->from = $from;
        $walletCharge = $this->createChargeModel($request, 'Alipay-Native');
        $currencyOrder = $this->createOrderModel($user->id, intval($amount), 'Native-Alipay', $order->subject);

        $result = $gateWay->purchase()->setBizContent([
            'subject'      => $order->subject,
            'out_trade_no' => $order->out_trade_no,
            'total_amount' => $order->amount / 100,
            'product_code' => $order->product_code,
            'body' => $order->content,
            'timeout_express' => '10m',
            'charge_type' => 'currency',
        ])->send();

        if ($result->isSuccessful()) {
            return DB::transaction(function () use ($order, $walletCharge, $response, $isUrl, $result, $currencyOrder) {
                try {
                    $order->save();
                    $currencyOrder->target_id = $order->id;
                    $walletCharge->charge_id = $order->id;
                    $walletCharge->save();
                    $currencyOrder->save();

                    return $response->json(($isUrl ? $result->getRedirectUrl() : $result->getRedirectData()), 201);
                } catch (\Exception $e) {
                    DB::rollback();

                    return $response->json(['message' => '创建支付宝订单失败'], 422);
                }
            }, 1);
        }

        return $response->json(['message' => '创建支付宝订单失败'], 422);
    }

    /**=
     * 支付宝通知方法.
     * @Author   Wayne
     * @DateTime 2018-05-14
     * @Email    qiaobin@zhiyicx.com
     * @param    Request             $request    [description]
     * @param    NativePayOrder      $orderModel [description]
     * @return   [type]                          [description]
     */
    public function alipayNotify(Request $request, NativePayOrder $orderModel, ResponseFactory $response)
    {
        $data = $request->all();
        $config = array_filter(config('newPay.alipay'));
        // 支付宝配置必须包含signType, appId, secretKey, publicKey, 缺一不可
        if (count($config) < 4) {
            die('fail');
        }
        $order = $orderModel->where('out_trade_no', $data['out_trade_no'])
            ->first();
        if (! $order || $order->status === 1) {
            die('fail');
        }
        if ($order->amount != $data['total_amount'] * 100) {
            die('fail');
        }
        $gateWay = Omnipay::create('Alipay_AopApp');
        // 签名方法
        $gateWay->setSignType($config['signType']);
        // appId
        $gateWay->setAppId($config['appId']);
        // 密钥
        $gateWay->setPrivateKey($config['secretKey']);
        // 公钥
        $gateWay->setAlipayPublicKey($config['alipayKey']);

        $res = $gateWay->completePurchase();
        $res->setParams($_POST);
        try {
            $response = $res->send();
            if ($response->isPaid()) {
                $this->resolveNativePayOrder($order, $data);
                $currencyOrder = CurrencyOrderModel::where('target_id', $order->id)->first();
                if ($currencyOrder) {
                    $this->resolveCurrencyOrder($currencyOrder, $data);
                }
                $this->resolveWalletCharge($order->walletCharge, $data);
                $this->resolveUserCurrency($order);
                die('success');
            } else {
                die('fail');
            }
        } catch (Exception $e) {
            die('fail');
        }
    }

    public function checkAlipayOrder(Request $request, ResponseFactory $response, CurrencyOrderModel $orderModel, NativePayOrder $nativePayOrder)
    {
        $memo = $request->input('memo');
        $result = $request->input('result');
        $resultFormat = json_decode($result, true);
        $out_trade_no = $resultFormat['alipay_trade_app_pay_response']['out_trade_no'];
        // 验证订单合法性
        if (! $out_trade_no) {
            return $response->json(['message' => '充值信息有误'], 422);
        }
        $order = $nativePayOrder->where('out_trade_no', $out_trade_no)
            ->first();
        if (! $order) {
            return $response->json(['message' => '订单不存在'], 404);
        }
        if ($order->amount != $resultFormat['alipay_trade_app_pay_response']['total_amount'] * 100) {
            return $response->json(['message' => '订单金额有误，请联系小助手'], 422);
        }
        // 已经通过异步通知处理了
        if ($order->status === 1) {
            return $response->json(['message' => '充值成功'], 200);
        }
        if ($order->status === 2) {
            return $response->json(['message' => '充值失败'], 200);
        }
        $config = config('newPay.alipay');
        $resultStatus = $request->input('resultStatus');
        $gateWay = Omnipay::create('Alipay_AopApp');
        // 签名方法
        $gateWay->setSignType($config['signType']);
        // appId
        $gateWay->setAppId($config['appId']);
        // 密钥
        $gateWay->setPrivateKey($config['secretKey']);
        // 公钥
        $gateWay->setAlipayPublicKey($config['alipayKey']);

        $res = $gateWay->completePurchase();
        $res->setParams([
            'memo' => $memo,
            'result' => $result,
            'resultStatus' => $resultStatus,
        ]);
        $currencyOrder = $orderModel->where('target_id', $order->id)->first();

        try {
            $callback = $res->send();
            if ($callback->isPaid()) {
                $this->resolveNativePayOrder($order, $resultFormat['alipay_trade_app_pay_response']);
                if ($currencyOrder) {
                    $this->resolveCurrencyOrder($currencyOrder, $resultFormat['alipay_trade_app_pay_response']);
                }
                $this->resolveWalletCharge($order->walletCharge, $resultFormat['alipay_trade_app_pay_response']);
                $this->resolveUserCurrency($order);

                return $response->json(['message' => '充值成功'], 201);
            } else {
                return $response->json(['message' => '充值处理中'], 202);
            }
        } catch (Exception $e) {
            return $response->json(['message' => '充值结果未知，请等待'], 202);
        }
    }

    /**=
     * @param Request  $request
     * @param Carbon   $dateTime
     * @param Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWechatOrder(Request $request, ResponseFactory $response, NativePayOrder $order)
    {
        $user = $request->user();
        $amount = $request->input('amount', 0);
        $from = $request->input('from');
        $config = array_filter(config('newPay.wechatPay'));
        // 微信配置必须包含, appId, apiKey, mchId, 缺一不可

        if (count($config) < 3) {
            return $response->json(['message' => '系统错误,请联系小助手'], 500);
        }
        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }
        if ($from != 3 && $from != 4) {
            return $response->json(['message' => '请求来源非法'], 403);
        }

        $gateWay = Omnipay::create('WechatPay_App');
        $gateWay->setAppId($config['appId']);
        $gateWay->setApiKey($config['apiKey']);
        $gateWay->setMchId($config['mchId']);
        $gateWay->setNotifyUrl(config('app.url').'/api/v2/wechatCurrency/notify');

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).config('newPay.sign');
        $order->subject = '积分充值';
        $order->content = sprintf('在%s充值积分%d', config('app.name'), $amount * $this->ratio);
        $order->amount = $amount;
        $order->product_code = 'APP';
        $order->user_id = $user->id;
        $order->from = $from;
        $order->type = 'wechat';
        $walletCharge = $this->createChargeModel($request, 'Wechat-Native');
        $currencyOrder = $this->createOrderModel($user->id, intval($amount), 'Wechat-Alipay', $order->subject);
        $wechatOrder = [
            'body'              => $order->content,
            'out_trade_no'      => $order->out_trade_no,
            'total_fee'         => $amount,
            'spbill_create_ip'  => $request->getClientIp(),
            'fee_type'          => 'CNY',
        ];
        $request = $gateWay->purchase($wechatOrder)->send();

        if ($request->isSuccessful()) {
            $order->save();
            $walletCharge->charge_id = $order->id;
            $currencyOrder->target_id = $order->id;
            $walletCharge->save();
            $currencyOrder->save();

            return $response->json(['message' => '订单创建成功', 'data' => $request->getAppOrderData()], 201);
        }

        return $response->json(['message' => '创建微信订单失败'], 422);
    }

    /**
     * @param Request  $request
     * @param Carbon   $dateTime
     * @param Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWechatWapOrder(Request $request, ResponseFactory $response, NativePayOrder $order)
    {
        $user = $request->user();
        $amount = $request->input('amount', 0);
        $from = $request->input('from');
        $openId = $request->input('openId', '');
        $config = array_filter(config('newPay.wechatPay'));
        if (! $openId) {
            return $response->json(['message' => '系统错误,请联系小助手'], 500);
        }
        // 微信配置必须包含, appId, apiKey, mchId, 缺一不可
        if (count($config) < 3) {
            return $response->json(['message' => '系统错误,请联系小助手'], 500);
        }
        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        if ($from != 1 && $from != 2) {
            return $response->json(['message' => '请求来源非法'], 403);
        }

        $gateWay = Omnipay::create('WechatPay_Js');
        $gateWay->setAppId($config['appId']);
        $gateWay->setApiKey($config['apiKey']);
        $gateWay->setMchId($config['mchId']);
        $gateWay->setNotifyUrl(config('app.url').'/api/v2/wechatCurrency/notify');

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).config('newPay.sign');
        $order->subject = '积分充值';
//        $order->content = '在'.config('app.name').'充值积分'.$amount;
        $order->content = sprintf('在%s充值积分%d', config('app.name'), $amount * $this->ratio);
        $order->amount = $amount;
        $order->product_code = 'JSAPI';
        $order->user_id = $user->id;
        $order->from = $from;
        $order->type = 'wechat';
        $walletCharge = $this->createChargeModel($request, 'Wechat-Native');
        $currencyOrder = $this->createOrderModel($user->id, intval($amount), 'Wechat-Alipay', $order->subject);
        $wechatOrder = [
            'body'              => $order->content,
            'out_trade_no'      => $order->out_trade_no,
            'total_fee'         => $amount,
            'spbill_create_ip'  => $request->getClientIp(),
            'fee_type'          => 'CNY',
            'openid'           => $openId,
        ];
        $request = $gateWay->purchase($wechatOrder)->send();

        if ($request->isSuccessful()) {
            $order->save();

            $walletCharge->charge_id = $order->id;
            $currencyOrder->target_id = $order->id;
            $walletCharge->save();
            $currencyOrder->save();

            return $response->json(['message' => '订单创建成功', 'data' => $request->getJsOrderData()], 201);
        }

        return $response->json(['message' => '创建微信订单失败'], 422);
    }

    public function wechatNotify(CurrencyOrderModel $currencyOrderModel, NativePayOrder $orderModel, ResponseFactory $response)
    {
        $data = file_get_contents('php://input');
        $config = array_filter(config('newPay.wechatPay'));
        // 微信配置必须包含, appId, apiKey, mchId, 缺一不可
        if (count($config) < 3) {
            return $response->json(['message' => '系统错误,请联系小助手'], 500);
        }
        $gateway = Omnipay::create('WechatPay_App');
        $gateway->setAppId($config['appId']);
        $gateway->setMchId($config['mchId']);
        $gateway->setApiKey($config['apiKey']);

        $res = $gateway->completePurchase([
            'request_params' => $data,
        ])->send();
        if ($res->isPaid()) {
            $requestData = $res->getRequestData();
            $payOrder = $orderModel->where('out_trade_no', $requestData['out_trade_no'])
                ->first();
            if (! $payOrder || ($payOrder->amount != $requestData['total_fee'])) {
                die('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
            }
            $currencyOrder = $currencyOrderModel->where('target_id', $payOrder->id)
                ->first();
            $data = [
                'trade_no' => $requestData['transaction_id'],
                'buyer_id' => $requestData['openid'],
            ];

            $this->resolveNativePayOrder($payOrder, $data);
            $this->resolveWalletCharge($payOrder->walletCharge, $data);
            $this->resolveUserCurrency($payOrder);
            if ($currencyOrder) {
                $this->resolveCurrencyOrder($currencyOrder, $data);
            }

            die('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
        } else {
            die('<xml><return_code><![CDATA[FAIL]]></return_code></xml>');
        }
    }

    protected function createChargeModel(Request $request, string $channel): WalletChargeModel
    {
        $charge = new WalletChargeModel();
        $charge->user_id = $request->user()->id;
        $charge->channel = $channel;
        $charge->action = 1; // 充值都是为 增项
        $charge->amount = intval($request->input('amount'));
        $charge->subject = '积分充值';
        $charge->body = '积分充值';
        $charge->status = 0; // 待操作状态

        return $charge;
    }

    protected function createOrderModel(int $owner, int $amount, string $target_type, string $title): CurrencyOrderModel
    {
        $recharge = new Recharge();
        $order = $recharge->createOrder($owner, $amount * $this->ratio);

        return $order;
    }

    protected function resolveNativePayOrder(NativePayOrder $order, $data)
    {
        $order->status = 1;
        $order->trade_no = $data['trade_no'];
        $order->save();
    }

    protected function resolveWalletCharge(WalletChargeModel $order, $data)
    {
        $order->status = 1;
        $order->transaction_no = $data['trade_no'];
        $order->account = $data['buyer_id'] ?? '';
        $order->save();
    }

    protected function resolveCurrencyOrder(CurrencyOrderModel $order, $data)
    {
        $order->target_id = $data['trade_no'];
        $order->state = 1;
        $order->save();
    }

    protected function resolveUserCurrency(NativePayOrder $order)
    {
        $order->user->currency()->increment('sum', $order->amount);
    }
}
