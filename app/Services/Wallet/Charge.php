<?php

namespace Zhiyi\Plus\Services\Wallet;

use Pingpp\Pingpp as PingppInit;
use Pingpp\Charge as PingppCharge;
use Zhiyi\Plus\Repository\WalletPingPlusPlus;
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
}
