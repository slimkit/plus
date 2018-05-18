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
use Log;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\Models\NativePayOrder;
use Illuminate\Contracts\Routing\ResponseFactory;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;

class PayController
{
    public function checkStatus(Request $request)
    {
        Log::debug($request->all());
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

        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }
        $config = array_filter(config('newPay.alipay'));
        // 支付宝配置必须包含signType, appId, secretKey, publicKey, 缺一不可
        if (count($config) < 4) {
            return $response->json(['message' => '系统错误,请联系小助手'], 500);
        }
        $gateWay = Omnipay::create('Alipay_AopApp');
        $gateWay->setSignType($config['signType']);
        $gateWay->setAppId($config['appId']);
        $gateWay->setPrivateKey($config['secretKey']);
        $gateWay->setAlipayPublicKey($config['publicKey']);
        $gateWay->setNotifyUrl(config('app.url', '/ap2/v2/alipay/notify'));

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).'Thinksns-plus';
        $order->subject = '钱包充值';
        $order->content = '在'.config('app.name').'充值余额'.$amount / 100 .'元';
        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id;
        $order->from = $from;
        $order->type = 'alipay';
        $walletCharge = $this->createChargeModel($request, 'Alipay-Native');
        $walletOrder = $this->createOrderModel($user->id, intval($amount), 'Native-Alipay', $order->subject);

        $result = $gateWay->purchase()->setBizContent([
            'subject'      => $order->subject,
            'out_trade_no' => $order->out_trade_no,
            'total_amount' => $order->amount / 100,
            'product_code' => $order->product_code,
            'body' => $order->content,
            'timeout_express' => '10m',
        ])->send();

        if ($result->isSuccessful()) {
            return DB::transaction(function () use ($order, $walletCharge, $response, $result, $walletOrder) {
                try {
                    $order->save();
                    $walletOrder->target_id = $order->id;
                    $walletCharge->charge_id = $order->id;
                    $walletCharge->save();
                    $walletOrder->save();

                    return $response->json($result->getOrderString(), 201);
                } catch (Exception $e) {
                    DB::rollback();

                    return $response->json(['message' => '创建支付宝订单失败'], 422);
                }
            }, 1);
        }

        return $response->json(['message' => '创建支付宝订单失败'], 422);
    }

    public function getAlipayWapOrder(Request $request, ResponseFactory $response, NativePayOrder $order)
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
        $gateWay->setNotifyUrl(config('app.url').'/api/v2/alipay/notify');
        // 支付成功后返回地址
        $gateWay->setReturnUrl($redirect);

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).'Thinksns-plus';
        $order->subject = '钱包充值';
        $order->content = '在'.config('app.name').'充值余额'.$amount / 100 .'元';
        $order->type = 'alipay';
        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id ?? 0;
        $order->from = $from;
        $walletCharge = $this->createChargeModel($request, 'Alipay-Native');
        $walletOrder = $this->createOrderModel($user->id, intval($amount), 'Native-Alipay', $order->subject);

        $result = $gateWay->purchase()->setBizContent([
            'subject'      => $order->subject,
            'out_trade_no' => $order->out_trade_no,
            'total_amount' => $order->amount / 100,
            'product_code' => $order->product_code,
            'body' => $order->content,
            'timeout_express' => '10m',
        ])->send();

        if ($result->isSuccessful()) {
            return DB::transaction(function () use ($order, $walletCharge, $response, $isUrl, $result, $walletOrder) {
                try {
                    $order->save();
                    $walletOrder->target_id = $order->id;
                    $walletCharge->charge_id = $order->id;
                    $walletCharge->save();
                    $walletOrder->save();

                    return $response->json(($isUrl ? $result->getRedirectUrl() : $result->getRedirectData()), 201);
                } catch (\Exception $e) {
                    DB::rollback();

                    return $response->json(['message' => '创建支付宝订单失败'], 422);
                }
            }, 1);
        }

        return $response->json(['message' => '创建支付宝订单失败'], 422);
    }

    /**
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
        $gateWay = Omnipay::create('Alipay_AopApp');
        // 签名方法
        $gateWay->setSignType($config['signType']);
        // appId
        $gateWay->setAppId($config['appId']);
        // 密钥
        $gateWay->setPrivateKey($config['secretKey']);
        // 公钥
        $gateWay->setAlipayPublicKey($config['publicKey']);

        $res = $gateWay->completePurchase();
        $res->setParams($_POST);
        try {
            $response = $res->send();
            if ($response->isPaid()) {
                $order = $orderModel->where('out_trade_no', $data['out_trade_no'])
                    ->first();
                if (! $order || $order->status === 1) {
                    die('fail');
                }
                $walletOrder = WalletOrderModel::where('target_id', $order->id)->first();
                if ($walletOrder) {
                    $walletOrder->target_id = $data['trade_no'];
                    $walletOrder->state = 1;
                }
                $order->status = 1;
                $order->trade_no = $data['trade_no'];
                $order->save();
                $order->walletCharge->status = 1;
                $order->walletCharge->transaction_no = $data['trade_no'];
                $order->walletCharge->account = $data['buyer_id'];
                $order->walletCharge->save();
                $walletOrder->save();
                $order->user->newWallet()->increment('balance', $order->amount);
                $order->user->newWallet()->increment('total_income', $order->amount);
                die('success');
            } else {
                die('fail');
            }
        } catch (Exception $e) {
            die('fail');
        }
    }

    public function getOrder(NativePayOrder $order, ResponseFactory $response)
    {
        $order->load('user', 'walletCharge');

        return $response->json($order, 200);
    }

    /**
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

        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $gateWay = Omnipay::create('WechatPay_App');
        $gateWay->setAppId('wx970d230ad5ab3b23');
        $gateWay->setApiKey('IbcUF7KWIG7dklsx9O9n7eF2I6LS29WS');
        $gateWay->setMchId('1486014122');
        $gateWay->setNotifyUrl('http://test-plus.zhibocloud.cn/ssh/wechat');

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).'Thinksns-plus';
        $order->subject = '钱包充值';
        $order->content = '在'.config('app.name').'充值余额'.$amount / 100 .'元';
        $order->amount = $amount;
        $order->product_code = 'APP';
        $order->user_id = $user->id;
        $order->from = $from;
        $order->type = 'wechat';
        $walletCharge = $this->createChargeModel($request, 'Wechat-Native');
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
            $walletCharge->save();

            return $response->json($request->getAppOrderData(), 201);
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

        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $gateWay = Omnipay::create('WechatPay_Js');
        $gateWay->setAppId('wx970d230ad5ab3b23');
        $gateWay->setApiKey('IbcUF7KWIG7dklsx9O9n7eF2I6LS29WS');
        $gateWay->setMchId('1486014122');
        $gateWay->setNotifyUrl('http://test-plus.zhibocloud.cn/ssh/wechat');

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).'Thinksns-plus';
        $order->subject = '钱包充值';
        $order->content = '在'.config('app.name').'充值余额'.$amount / 100 .'元';
        $order->amount = $amount;
        $order->product_code = 'JSAPI';
        $order->user_id = $user->id;
        $order->from = $from;
        $order->type = 'wechat';
        $walletCharge = $this->createChargeModel($request, 'Wechat-Native');
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
            $walletCharge->save();

            return $response->json($request->getJsOrderData(), 201);
        }

        return $response->json(['message' => '创建微信订单失败'], 422);
    }

    protected function createChargeModel(Request $request, string $channel): WalletChargeModel
    {
        $charge = new WalletChargeModel();
        $charge->user_id = $request->user()->id;
        $charge->channel = $channel;
        $charge->action = 1; // 充值都是为 增项
        $charge->amount = intval($request->input('amount'));
        $charge->subject = '余额充值';
        $charge->body = '账户余额充值';
        $charge->status = 0; // 待操作状态

        return $charge;
    }

    protected function createOrderModel(int $owner, int $amount, string $target_type, string $title): WalletOrderModel
    {
        $order = new WalletOrderModel();
        $order->owner_id = $owner;
        $order->target_type = $target_type;
        $order->target_id = 0;
        $order->title = $title;
        $order->body = '余额充值';
        $order->type = 1;
        $order->amount = $amount;
        $order->state = 0;

        return $order;
    }
}
