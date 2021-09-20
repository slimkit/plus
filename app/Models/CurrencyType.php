<?php

declare(strict_types=1);

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

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\CacheNames;

/**
 * Zhiyi\Plus\Models\CurrencyType.
 *
 * @property int $id
 * @property string $name 货币名称
 * @property string $unit 货币单位
 * @property int|null $enable 货币是否被启用
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyType query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyType whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyType whereUnit($value)
 * @mixin \Eloquent
 */
class CurrencyType extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 获取当前使用的积分名称.
     *
     * @param  null  $field
     * @return mixed
     */
    public static function current($field = null)
    {
        $gold = Cache::rememberForever(CacheNames::CURRENCY_NAME, function () {
            $current = self::query()->where('enable', 1)
                ->select('id', 'name', 'unit')
                ->first();

            return collect($current ?
                $current->toArray() :
                ['id' => 999, 'name' => '金币', 'unit'=> '个']);
        });

        return $field ? $gold->get($field) : $gold;
    }

    /**
     * 设置当前使用的积分名称.
     *
     * @param  string  $name
     * @param  string  $unit
     */
    public static function setCurrent(string $name, string $unit)
    {
        Cache::forever(CacheNames::CURRENCY_NAME, collect([
            'name' => $name,
            'unit' => $unit,
        ]));
    }
}
