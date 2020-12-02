<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\ServiceProvider;
use Omnipay\Omnipay;
use Zhiyi\Plus\Contracts\PaymentGateway;

class PaymentServiceProvidor extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $factory = '';
        $config = [];
        switch (request()->query('pay_method')) {
            case 'AlipayOrder':
                $factory = 'Alipay_AopApp';
                $config = array_filter(config('newPay.alipay') ?: []);
                break;
            case 'AlipayWapOrder':
                $factory = 'Alipay_AopPage';
                $config = array_filter(config('newPay.alipay') ?: []);
                break;
            case 'WechatOrder':
                $factory = 'WechatPay_App';
                $config = config('newPay.wechatPay');
                break;
            case 'WechatWapOrder':
                $factory = 'WechatPay_Js';
                $config = config('newPay.wechatPay');
                break;
        }

        $this->app->singleton(PaymentGateway::class, static function ($app) use ($factory, $config) {
            $paymentGateway = Omnipay::create($factory);
            if (stripos($factory, 'alipay')) {
                // 签名方法
                $paymentGateway->setSignType($config['signType']);
                // appId
                $paymentGateway->setAppId($config['appId']);
                // 密钥
                $paymentGateway->setPrivateKey($config['secretKey']);
                // 公钥
                $paymentGateway->setAlipayPublicKey($config['publicKey']);
            } elseif (stripos($factory, 'wechat')) {
                $paymentGateway->setAppId($config['appId']);
                $paymentGateway->setApiKey($config['apiKey']);
                $paymentGateway->setMchId($config['mchId']);
            }

            return $paymentGateway;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
