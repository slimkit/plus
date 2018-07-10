<?php

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

namespace Zhiyi\Plus\Packages\Currency\Processes;

use DB;
use GuzzleHttp\Client;
use Zhiyi\Plus\Packages\Currency\Order;
use Zhiyi\Plus\Packages\Currency\Process;
use Zhiyi\Plus\Repository\CurrencyConfig;
use Zhiyi\Plus\Models\CommonConfig as CommonConfigModel;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;

class AppStorePay extends Process
{
    /**
     * @var string ApplePay验证沙箱测试URL
     */
    private $testUrl = 'https://sandbox.itunes.apple.com/verifyReceipt';
    /**
     * @var string ApplePay验证URL
     */
    private $url = 'https://buy.itunes.apple.com/verifyReceipt';

    public function __construct($sandbox = null)
    {
        parent::__construct();

        $this->sandbox = (bool) $sandbox;
    }

    /**
     * 验证票据.
     *
     * @param string $receipt
     * @param Zhiyi\Plus\Models\CurrencyOrderModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function verifyReceipt(string $receipt, CurrencyOrderModel $currencyOrder): bool
    {
        $initialData = ['receipt-data' => $receipt];

        try {
            $result = $this->sendReceiptToAppStore($initialData);
            $data = json_decode($result->getBody()->getContents(), true);

            if ($data['status'] === 21007) {
                $this->sandbox = true;

                return $this->verifyReceipt($receipt, $currencyOrder);
            }

            if ($data['status'] !== 0 && $data['status'] !== 21007) {
                throw new \Exception($this->getStatusError($data['status']));
            }

            $this->checkAppleOrder($data, $currencyOrder);

            return $this->complete($currencyOrder);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 发送票据AppStore进行检验.
     *
     * @param $body
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    protected function sendReceiptToAppStore($body)
    {
        $http = new Client(['headers' => ['Content-Type' => 'application/json']]);

        $verifyUrl = $this->sandbox ? $this->testUrl : $this->url;

        $result = $http->request('POST', $verifyUrl, ['json' => $body]);

        return $result;
    }

    /**
     * Create Order.
     *
     * @param int $owner
     * @param int $amount
     * @return Zhiyi\Plus\Models\CurrencyOrderModel
     * @author BS <414606094@qq.com>
     */
    public function createOrder(int $owner_id, int $amount): CurrencyOrderModel
    {
        $user = $this->checkUser($owner_id);

        $config = app(CurrencyConfig::class)->get();

        $title = '积分充值';
        $body = sprintf('充值积分：%s%s%s', $amount, $this->currency_type->unit, $this->currency_type->name);

        $order = new CurrencyOrderModel();
        $order->owner_id = $user->id;
        $order->title = $title;
        $order->body = $body;
        $order->type = 1;
        $order->currency = $this->currency_type->id;
        $order->target_type = Order::TARGET_TYPE_RECHARGE;
        $order->amount = $amount * $config['recharge-ratio'];
        $order->save();

        return $order;
    }

    /**
     * 完成充值操作.
     *
     * @param CurrencyOrderModel $currencyOrderModel
     * @return boolen
     * @author BS <414606094@qq.com>
     */
    private function complete(CurrencyOrderModel $currencyOrderModel): bool
    {
        $currencyOrderModel->state = 1;
        $user = $this->checkUser($currencyOrderModel->user);

        return DB::transaction(function () use ($user, $currencyOrderModel) {
            $currencyOrderModel->save();
            $user->currency->increment('sum', $currencyOrderModel->amount);

            return true;
        });
    }

    /**
     * 验证苹果收据与系统内订单是否一致.
     *
     * @param array $data
     * @param CurrencyOrderModel $order
     * @author BS <414606094@qq.com>
     */
    protected function checkAppleOrder(array $data, CurrencyOrderModel $order)
    {
        $products = CommonConfigModel::where('namespace', 'apple')->where('name', 'product')->first();

        if (! $products) {
            throw new \Exception('苹果IAP商品不存在');
        }
        $product = collect(json_decode($products->value, true))->where('product_id', $data['receipt']['in_app'][0]['product_id'])->first();

        if (! $product) {
            throw new \Exception('订单商品id不匹配');
        }

        if ($product['amount'] != $order->amount) {
            throw new \Exception('订单金额不匹配');
        }

        if ($order->state != 0) {
            throw new \Exception('订单已完成');
        }
    }

    /**
     * 设置状态错误消息.
     * @param $status
     */
    private function getStatusError($status): string
    {
        switch (intval($status)) {
            case 21000:
                $error = 'AppleStore不能读取你提供的JSON对象';
                break;
            case 21002:
                $error = 'receipt-data域的数据有问题';
                break;
            case 21003:
                $error = 'receipt无法通过验证';
                break;
            case 21004:
                $error = '提供的shared secret不匹配你账号中的shared secret';
                break;
            case 21005:
                $error = 'receipt服务器当前不可用';
                break;
            case 21006:
                $error = 'receipt合法，但是订阅已过期';
                break;
            case 21007:
                $error = 'receipt是沙盒凭证，但却发送至生产环境的验证服务';
                break;
            case 21008:
                $error = 'receipt是生产凭证，但却发送至沙盒环境的验证服务';
                break;
            default:
                $error = '未知错误';
        }

        return $error;
    }
}
