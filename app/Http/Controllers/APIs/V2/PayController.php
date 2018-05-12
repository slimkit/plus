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
    public function checkStatus(Request $request) {
        Log::debug($request->all());
    }
    /**
     * @param Request  $request
     * @param Carbon   $dateTime
     * @param Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlipayOrder(Request $request, Carbon $dateTime, ResponseFactory $response, NativePayOrder $order ) {
        $user = $request->user('api');
        $amount = $request->input('amount', 0);
        $from = $request->input('from');

        if(!$amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $gateWay = Omnipay::create('Alipay_AopApp');
        $gateWay->setSignType('RSA2');
        $gateWay->setAppId('2017052307316062');
        $gateWay->setPrivateKey('MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCdeMJutB/RztUDGcx6+SAPRt8Fj5ufePVv/FeTC+7drPwCLYArJPAGQiLXolV1MCFydK75WSQjd2b1UHj6Vjxcf79NMVIvXBIFCLa1Gdj+yOZwAMHVj0RrVh4uaJkDXHXKKkm7MU4AcTzZATsfnYZBgFJArFWUJCJWNm2Duf/67S0P2uUjqSo77PF2XXuhN6AI4t296phoZhpx6pfS1ih3haBqK83pEUYIPIh2KE0cgA3xtTer0kwcPorqnswW+BHjYoAuBheAnuh195CiL2DT0T8Jamo2Gpc6Hxd29uAeQMmnrS5JuvsPf4QUcBSADKz1lkGIfGjoldkhVLtNzdUlAgMBAAECggEAMCUMpRYTKG1pOPJ5Txu9zo3nf+bBOMedmqh3CfE7DbFmty+8IPTBIdi0mGL8Z8DYaQr9uCSl5OYLp3L8GeWlvM4d6fbrXqlypPFeEw6dBoDb22CP4sBTtdF1ltDJ/3oUVUQKmN3hMEduyBYVQnxB4OLfwwAthgZqLRuk7gI/HjNJHzrkVXBblpEjt7eIHOWhml44GpU19TnasCeyfl78nujryj4MtzEV5pZmyK8SL+2LJMy4ZjyK9I8LB4Ebx0MkeU4P89+oXWzx3z1f9/DQC2LDlO0+UlzjfjTG6G/sHtGoq+VmJ4oMrEWLtal8wOnnGMIsJ/opc8rAwm3kd6UAQQKBgQD/TqquTnii3AqVzwTnYBDCdLRH2c3ujdChbJG9a75jbQE5otASGiSKbxFlTTMRoQ6kuFvpqbTGR8HepH0EoW3+Pkiz5ateqmCS2smUOhXi/Pp1mGh8Ri2hj0JaSLSTo8f5eJap4Qb95AIsxCFNmKbvoEg4H9IB+3cHmcRg6ehpkQKBgQCd5iMs0xxn2zE/mUAgmO2/sebt/JMeeI2hD9gmBm77M4+L2omnBKBXfKXWODpAfZSBvMs3WzfqkgL4dxbWxfRuOT+mq3p33jZEykUo3UY/UmoZSO4pRpJ99udazMpPDL0SMTvFv4QP5Vt748hjzifdD67YJ3Av/QzFbZGEXdJIVQKBgEhBh2TVqKbPB9/mO0kQky21weAj8Hh3gnhtNcIaYEPbceFSBvlYlMbpME8vTijLIE3WL40uDo+fd2r/urI4zdyK3CCt+5ZLOhHWAf8FgXRAjNIDVG73nap/1ROgSBsQ22PrkRh7K3NnuIXa7GH9tiFTh5z6xIDzHnj0N/QFv1VBAoGAPFiTQvNgHNUp6kuQtaSc4LGGN5hbRb7/KfobOtUknz7icqnQCBP9j9Iks02D/dfA5SCZbgufwDeTiRBCm0zGkUWx7OoGgT9c8Ed5zRdcXKELyaQU2ZOOMzQk0ZAJFdMhg41rcbUzLLwUjbHNiU9l7teqlBPmjYCh2+Z3QeZ1ko0CgYA2s/JjrV9dkLcLSOvT9mHohFxw9Rz2tA358ekswNA838qv+cTKrgv6F4PjLnrLzy8k8j96xsMQhzMs0eaTPoYxvh1v5+1pwaeDre2DqwoQ1V0WRckNoknf2rTNBkLDgX8csI/stiHYimFaM0WuaMACA79qVttn1MwarntAqm9HgA==');
        $gateWay->setAlipayPublicKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnXjCbrQf0c7VAxnMevkgD0bfBY+bn3j1b/xXkwvu3az8Ai2AKyTwBkIi16JVdTAhcnSu+VkkI3dm9VB4+lY8XH+/TTFSL1wSBQi2tRnY/sjmcADB1Y9Ea1YeLmiZA1x1yipJuzFOAHE82QE7H52GQYBSQKxVlCQiVjZtg7n/+u0tD9rlI6kqO+zxdl17oTegCOLdveqYaGYaceqX0tYod4WgaivN6RFGCDyIdihNHIAN8bU3q9JMHD6K6p7MFvgR42KALgYXgJ7odfeQoi9g09E/CWpqNhqXOh8XdvbgHkDJp60uSbr7D3+EFHAUgAys9ZZBiHxo6JXZIVS7Tc3VJQIDAQAB');
        $gateWay->setNotifyUrl('http://test-plus.zhibocloud.cn/ssh');

        $order->out_trade_no = date('YmdHis') . mt_rand(1000, 9999) . 'Thinksns-plus';
        $order->subject = '测试内容';
        $order->content = '在' . config('app.name') . '充值'. $amount / 100 . '元';
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
        if($result->isSuccessful()) {
            Log::debug($result->getOrderString());
            $order->save();
            return $response->json($result->getOrderString(), 201);
        }
        return $response->json(['message' => '创建支付宝订单失败'], 422);
    }

    public function getAlipayWapOrder(Request $request, Carbon $dateTime, ResponseFactory $response, NativePayOrder $order)
    {
        $user = $request->user();
        $amount = $request->input('amount', 0);
        $redirect = $request->input('redirect', '');
        $from = $request->input('from', 3);
        $isUrl = $request->input('url', 1);

        if(!$amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $gateWay = Omnipay::create('Alipay_AopWap');
        $gateWay->setSignType('RSA2');
        $gateWay->setAppId('2017052307316062');
        $gateWay->setPrivateKey('MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCdeMJutB/RztUDGcx6+SAPRt8Fj5ufePVv/FeTC+7drPwCLYArJPAGQiLXolV1MCFydK75WSQjd2b1UHj6Vjxcf79NMVIvXBIFCLa1Gdj+yOZwAMHVj0RrVh4uaJkDXHXKKkm7MU4AcTzZATsfnYZBgFJArFWUJCJWNm2Duf/67S0P2uUjqSo77PF2XXuhN6AI4t296phoZhpx6pfS1ih3haBqK83pEUYIPIh2KE0cgA3xtTer0kwcPorqnswW+BHjYoAuBheAnuh195CiL2DT0T8Jamo2Gpc6Hxd29uAeQMmnrS5JuvsPf4QUcBSADKz1lkGIfGjoldkhVLtNzdUlAgMBAAECggEAMCUMpRYTKG1pOPJ5Txu9zo3nf+bBOMedmqh3CfE7DbFmty+8IPTBIdi0mGL8Z8DYaQr9uCSl5OYLp3L8GeWlvM4d6fbrXqlypPFeEw6dBoDb22CP4sBTtdF1ltDJ/3oUVUQKmN3hMEduyBYVQnxB4OLfwwAthgZqLRuk7gI/HjNJHzrkVXBblpEjt7eIHOWhml44GpU19TnasCeyfl78nujryj4MtzEV5pZmyK8SL+2LJMy4ZjyK9I8LB4Ebx0MkeU4P89+oXWzx3z1f9/DQC2LDlO0+UlzjfjTG6G/sHtGoq+VmJ4oMrEWLtal8wOnnGMIsJ/opc8rAwm3kd6UAQQKBgQD/TqquTnii3AqVzwTnYBDCdLRH2c3ujdChbJG9a75jbQE5otASGiSKbxFlTTMRoQ6kuFvpqbTGR8HepH0EoW3+Pkiz5ateqmCS2smUOhXi/Pp1mGh8Ri2hj0JaSLSTo8f5eJap4Qb95AIsxCFNmKbvoEg4H9IB+3cHmcRg6ehpkQKBgQCd5iMs0xxn2zE/mUAgmO2/sebt/JMeeI2hD9gmBm77M4+L2omnBKBXfKXWODpAfZSBvMs3WzfqkgL4dxbWxfRuOT+mq3p33jZEykUo3UY/UmoZSO4pRpJ99udazMpPDL0SMTvFv4QP5Vt748hjzifdD67YJ3Av/QzFbZGEXdJIVQKBgEhBh2TVqKbPB9/mO0kQky21weAj8Hh3gnhtNcIaYEPbceFSBvlYlMbpME8vTijLIE3WL40uDo+fd2r/urI4zdyK3CCt+5ZLOhHWAf8FgXRAjNIDVG73nap/1ROgSBsQ22PrkRh7K3NnuIXa7GH9tiFTh5z6xIDzHnj0N/QFv1VBAoGAPFiTQvNgHNUp6kuQtaSc4LGGN5hbRb7/KfobOtUknz7icqnQCBP9j9Iks02D/dfA5SCZbgufwDeTiRBCm0zGkUWx7OoGgT9c8Ed5zRdcXKELyaQU2ZOOMzQk0ZAJFdMhg41rcbUzLLwUjbHNiU9l7teqlBPmjYCh2+Z3QeZ1ko0CgYA2s/JjrV9dkLcLSOvT9mHohFxw9Rz2tA358ekswNA838qv+cTKrgv6F4PjLnrLzy8k8j96xsMQhzMs0eaTPoYxvh1v5+1pwaeDre2DqwoQ1V0WRckNoknf2rTNBkLDgX8csI/stiHYimFaM0WuaMACA79qVttn1MwarntAqm9HgA==');
        $gateWay->setAlipayPublicKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnXjCbrQf0c7VAxnMevkgD0bfBY+bn3j1b/xXkwvu3az8Ai2AKyTwBkIi16JVdTAhcnSu+VkkI3dm9VB4+lY8XH+/TTFSL1wSBQi2tRnY/sjmcADB1Y9Ea1YeLmiZA1x1yipJuzFOAHE82QE7H52GQYBSQKxVlCQiVjZtg7n/+u0tD9rlI6kqO+zxdl17oTegCOLdveqYaGYaceqX0tYod4WgaivN6RFGCDyIdihNHIAN8bU3q9JMHD6K6p7MFvgR42KALgYXgJ7odfeQoi9g09E/CWpqNhqXOh8XdvbgHkDJp60uSbr7D3+EFHAUgAys9ZZBiHxo6JXZIVS7Tc3VJQIDAQAB');
        $gateWay->setNotifyUrl('http://test-plus.zhibocloud.cn/ssh');
        $gateWay->setReturnUrl($redirect);

        $order->out_trade_no = date('YmdHis') . mt_rand(1000, 9999) . 'Thinksns-plus';
        $order->subject = '测试内容';
        $order->body = '在' . config('app.name') . '充值'. $amount / 100 . '元';;
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
        if($result->isSuccessful()) {
            Log::debug($result->getRedirectData());

            return $response->json(($isUrl ? $result->getRedirectUrl() : $result->getRedirectData()), 201);
        }
        return $response->json(['message' => '创建支付宝订单失败'], 422);
    }

    /**
     * @param Request  $request
     * @param Carbon   $dateTime
     * @param Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWechatOrder(Request $request, Carbon $dateTime, ResponseFactory $response, NativePayOrder $order ) {
        $user = $request->user('api');
        $amount = $request->input('amount', 0);
        $from = $request->input('from');

        if(!$amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $gateWay = Omnipay::create('WechatPay_App');
        $gateWay->setAppId('wx970d230ad5ab3b23');
        $gateWay->setApiKey('IbcUF7KWIG7dklsx9O9n7eF2I6LS29WS');
        $gateWay->setMchId('1486014122');
        $gateWay->setNotifyUrl('http://test-plus.zhibocloud.cn/ssh/wechat');

        $order->out_trade_no = date('YmdHis') . mt_rand(1000, 9999) . 'Thinksns-plus';
        $order->subject = '测试内容';
        $order->content = '在' . config('app.name') . '充值'. $amount / 100 . '元';
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
            'fee_type'          => 'CNY'
        ];
        $request  = $gateWay->purchase($wechatOrder)->send();

        if($request->isSuccessful()) {

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
    public function getWechatWapOrder(Request $request, Carbon $dateTime, ResponseFactory $response, NativePayOrder $order ) {
        $user = $request->user('api');
        $amount = $request->input('amount', 0);
        $from = $request->input('from');

        if(!$amount) {
            return $response->json(['message' => '提交的信息不完整'], 422);
        }

        $gateWay = Omnipay::create('WechatPay_Js');
        $gateWay->setAppId('wx970d230ad5ab3b23');
        $gateWay->setApiKey('IbcUF7KWIG7dklsx9O9n7eF2I6LS29WS');
        $gateWay->setMchId('1486014122');
        $gateWay->setNotifyUrl('http://test-plus.zhibocloud.cn/ssh/wechat');

        $order->out_trade_no = date('YmdHis') . mt_rand(1000, 9999) . 'Thinksns-plus';
        $order->subject = '测试内容';
        $order->content = '在' . config('app.name') . '充值'. $amount / 100 . '元';
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
            'fee_type'          => 'CNY'
        ];
        $request  = $gateWay->purchase($wechatOrder)->send();

        if($request->isSuccessful()) {

            return $response->json($request->getJsOrderData(), 201);
        }

        return $response->json(['message' => '创建微信订单失败'], 422);
    }
}