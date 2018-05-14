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

use Log;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\Models\NativePayOrder;
use Illuminate\Contracts\Routing\ResponseFactory;

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
        $user = $request->user('api');
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
        $gateWay->setNotifyUrl('http://test-plus.zhibocloud.cn/ssh');

        $order->out_trade_no = date('YmdHis').mt_rand(1000, 9999).'Thinksns-plus';
        $order->subject = '测试内容';
        $order->content = '在'.config('app.name').'充值'.$amount / 100 .'元';
        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id ?? 0;
        $order->from = $from;
        $order->type = 'Alipay';

        $result = $gateWay->purchase()->setBizContent([
            'subject'      => $order->subject,
            'out_trade_no' => $order->out_trade_no,
            'total_amount' => $order->amount / 100,
            'product_code' => $order->product_code,
            'body' => $order->content,
            'timeout_express' => '10m',
        ])->send();
        if ($result->isSuccessful()) {
            Log::debug($result->getOrderString());
            $order->save();

            return $response->json($result->getOrderString(), 201);
        }

        return $response->json(['message' => '创建支付宝订单失败'], 422);
    }

    public function getAlipayWapOrder(Request $request, ResponseFactory $response, NativePayOrder $order)
    {
        $user = $request->user();
        $amount = $request->input('amount', 0);
        $redirect = $request->input('redirect', '');
        $from = $request->input('from', 3);
        $isUrl = $request->input('url', 1);

        if (! $amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $config = array_filter(config('newPay.alipay'));
        // 支付宝配置必须包含signType, appId, secretKey, publicKey, 缺一不可
        if (count($config) < 4) {
            return $response->json(['message' => '系统错误,请联系小助手'], 500);
        }
        $gateWay = Omnipay::create('Alipay_AopWap');
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
        $order->subject = '测试内容';
        $order->body = '在'.config('app.name').'充值'.$amount / 100 .'元';

        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id ?? 0;
        $order->from = $from;

        $result = $gateWay->purchase()->setBizContent([
            'subject'      => $order->subject,
            'out_trade_no' => $order->out_trade_no,
            'total_amount' => $order->amount / 100,
            'product_code' => $order->product_code,
            'body' => $order->content,
            'timeout_express' => '10m',
        ])->send();

        if ($result->isSuccessful()) {
            Log::debug($result->getRedirectUrl());

            return $response->json(($isUrl ? $result->getRedirectUrl() : $result->getRedirectData()), 201);
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
        $gateWay = Omnipay::create('Alipay_AopWap');
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
                $order->status = true;
                $order->save();

                die('success');
            } else {
                die('fail');
            }
        } catch (Exception $e) {
            die('fail');
        }
    }

    /**
     * @param Request  $request
     * @param Carbon   $dateTime
     * @param Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWechatOrder(Request $request, ResponseFactory $response, NativePayOrder $order)
    {
        $user = $request->user('api');
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
        $order->subject = '测试内容';
        $order->content = '在'.config('app.name').'充值'.$amount / 100 .'元';
        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id ?? 0;
        $order->from = $from;
        $order->type = 'WechatPay_App';
        $wechatOrder = [
            'body'              => $order->content,
            'out_trade_no'      => $order->out_trade_no,
            'total_fee'         => $amount,
            'spbill_create_ip'  => $request->getClientIp(),
            'fee_type'          => 'CNY',
        ];
        $request = $gateWay->purchase($wechatOrder)->send();

        if ($request->isSuccessful()) {
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
        $user = $request->user('api');
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
        $order->subject = '测试内容';
        $order->content = '在'.config('app.name').'充值'.$amount / 100 .'元';
        $order->amount = $amount;
        $order->product_code = 'FAST_INSTANT_TRADE_PAY';
        $order->user_id = $user->id ?? 0;
        $order->from = $from;
        $order->type = 'WechatPay_App';
        $wechatOrder = [
            'body'              => $order->content,
            'out_trade_no'      => $order->out_trade_no,
            'total_fee'         => $amount,
            'spbill_create_ip'  => $request->getClientIp(),
            'fee_type'          => 'CNY',
        ];
        $request = $gateWay->purchase($wechatOrder)->send();

        if ($request->isSuccessful()) {
            return $response->json($request->getJsOrderData(), 201);
        }

        return $response->json(['message' => '创建微信订单失败'], 422);
    }
}
