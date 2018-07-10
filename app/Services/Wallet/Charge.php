<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Services\Wallet;

use Pingpp\Pingpp as PingppInit;
use Pingpp\Charge as PingppCharge;
use Zhiyi\Plus\Repository\WalletPingPlusPlus;
use Zhiyi\Plus\Models\WalletOrder as WalletOrderModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;

class Charge
{
    /**
     * Ping++ app ID.
     *
     * @var string
     */
    protected $appId;

    /**
     * Ping++ secret key.
     *
     * @var string
     */
    protected $secretkey;

    /**
     * 商户私钥.
     *
     * @var string
     */
    protected $privateKey;

    /**
     * The charge prefix.
     *
     * @var string
     */
    private $prefix = 'a';

    /**
     * 支持的订单类型.
     *
     * @var array
     */
    protected $allowType = [
        'applepay_upacp',
        'alipay',
        'alipay_wap',
        'alipay_pc_direct',
        'alipay_qr',
        'wx',
        'wx_wap',
    ];

    /**
     * Create the service instance.
     *
     * @param \Zhiyi\Plus\Repository\WalletPingPlusPlus $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(WalletPingPlusPlus $repository)
    {
        $config = $repository->get();

        $this->appId = $config['app_id'] ?? null;
        $this->secretkey = $config['secret_key'] ?? null;
        $this->privateKey = $config['private_key'] ?? null;

        PingppInit::setAppId($config['app_id'] ?? null);
        PingppInit::setApiKey($config['secret_key'] ?? null);
        PingppInit::setPrivateKey($config['private_key'] ?? null);
    }

    /**
     * query a charge.
     *
     * @param string $charge
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function query(string $charge)
    {
        return PingppCharge::retrieve($charge);
    }

    /**
     * Create charge.
     *
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @param array $extra
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(WalletChargeModel $charge, array $extra = [])
    {
        if (! $charge->id) {
            $charge->save();
        }

        // Request Ping++
        return PingppCharge::create([
            'order_no' => $this->formatChargeId($charge->id),
            'amount' => $charge->amount,
            'app' => ['id' => $this->appId],
            'channel' => $charge->channel,
            'currency' => $charge->currency = 'cny', // 目前只支持 cny
            'client_ip' => request()->getClientIp(),
            'subject' => $charge->subject,
            'body' => $charge->body,
            'extra' => $extra,
        ]);
    }

    /**
     * Create charge for new wallet model.
     *
     * @param WalletOrderModel $order
     * @param string $type
     * @param array $extra
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function newCreate(WalletOrderModel $order, string $type, array $extra = [])
    {
        if (! $order->id) {
            $order->save();
        }

        // Request Ping++
        return PingppCharge::create([
            'order_no' => $this->formatChargeId($order->id),
            'amount' => $order->amount,
            'app' => ['id' => $this->appId],
            'channel' => $type,
            'currency' => 'cny', // 目前只支持 cny
            'client_ip' => request()->getClientIp(),
            'subject' => $order->title,
            'body' => $order->body,
            'extra' => $extra,
        ]);
    }

    /**
     * 不使用任何数据模型创建ping++订单.
     *
     * @param int $id
     * @param string $type
     * @param int $amount
     * @param string $title
     * @param string $body
     * @param array $extra
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function createWithoutModel(int $id, string $type, int $amount, string $title, string $body, array $extra)
    {
        return PingppCharge::create([
            'order_no' => $this->formatChargeId($id),
            'amount' => $amount,
            'app' => ['id' => $this->appId],
            'channel' => $type,
            'currency' => 'cny', // 目前只支持 cny
            'client_ip' => request()->getClientIp(),
            'subject' => $title,
            'body' => $body,
            'extra' => $extra,
        ]);
    }

    /**
     * Format charge id.
     *
     * @param int $chargeId
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function formatChargeId(int $chargeId): string
    {
        return $this->getPrefix().str_pad(strval($chargeId), 19, '0', STR_PAD_LEFT);
    }

    /**
     * Unformat charge id.
     *
     * @param string $chargeId
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function unformatChargeId(string $chargeId): int
    {
        return intval(
            ltrim(
                ltrim($chargeId, $this->getPrefix()), '0'
            )
        );
    }

    /**
     * Get format prefix.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Ser format prefix.
     *
     * @param string $prefix
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setPrefix(string $prefix)
    {
        if (strlen($prefix) > 1) {
            throw new \Exception('Prefix length > 1');
        }

        $this->prefix = $prefix;

        return $this;
    }

    /**
     * 检测支付方式及额外参数.
     *
     * @param string $type
     * @param array $extra
     * @return boolen
     * @author BS <414606094@qq.com>
     */
    public function checkRechargeArgs(string $type, array $extra): bool
    {
        if (in_array($type, $this->allowType)) {
            return $this->{camel_case('check_'.$type.'_extra')}($extra);
        }

        return false;
    }

    protected function checkApplepayUpacpExtra(): bool
    {
        return true;
    }

    protected function checkAlipayExtra(): bool
    {
        return true;
    }

    protected function checkAlipayWapExtra(array $extra): bool
    {
        return array_key_exists('success_url', $extra);
    }

    protected function checkAlipayPcDirectExtra(array $extra): bool
    {
        return array_key_exists('success_url', $extra);
    }

    protected function checkAlipayQrExtra(array $extra): bool
    {
        return array_key_exists('success_url', $extra);
    }

    protected function checkWxExtra(): bool
    {
        return true;
    }

    protected function checkWxWapExtra(array $extra): bool
    {
        return array_key_exists('success_url', $extra);
    }
}
