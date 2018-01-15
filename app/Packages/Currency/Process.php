<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\Currency;

use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\CurrencyType as CurrencyTypeModel;
use Zhiyi\Plus\Models\CurrencyOrder as CurrencyOrderModel;

abstract class Process
{
    /**
     * 货币类型
     *
     * @var [CurrencyTypeModel]
     */
    protected $currency_type;

    public function __construct($currency_type)
    {
        if (! $currency_type instanceof CurrencyTypeModel) {
            $currency_type = CurrencyTypeModel::findOrFail($currency_type);
        }

        $this->currency_type = $currency_type;
    }

    /**
     * 检测用户模型
     *
     * @param $user
     * @return UserModel
     * @author BS <414606094@qq.com>
     */
    public function checkUser($user): UserModel
    {
        if (is_numeric($user)) {
            return $this->checkWallet(
                UserModel::findOrFail((int) $user)
            );
        } elseif ($user instanceof UserModel) {
            return $this->checkWallet($user);
        }
    }

    /**
     * 检测用户货币模型，防止后续操作出现错误
     *
     * @param UserModel $user
     * @return UserModel
     * @author BS <414606094@qq.com>
     */
    protected function checkWallet(UserModel $user): UserModel
    {
        $currency = $user->Currencies()->where('type', $this->currency_type->id)->first();

        if (! $currency) {
            $user->Currencies()->create(['type' => $this->currency_type->id, 'sum' => 0]);
        }

        return $user;
    }

    abstract protected function createOrder(int $owner_id, string $title, string $body, int $type, int $amount): CurrencyOrderModel;
}
