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
 * Zhiyi\Plus\Models\GoldType.
 *
 * @property int $id
 * @property string $name 类型名称
 * @property string $unit 单位
 * @property int $status 状态:1-开启 0-关闭
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoldType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoldType extends Model
{
    public $table = 'gold_types';

    public $fillable = ['name', 'unit', 'status'];

    /**
     * 获取当前使用的积分名称.
     *
     * @param  null  $field
     * @return mixed
     */
    public static function current($field = null)
    {
        $gold = Cache::rememberForever(CacheNames::CURRENCY_NAME, function () {
            return self::query()->where('status', 1)
               ->select('name', 'unit')
               ->first() ?? collect(['name' => '金币', 'unit' => '个']);
        });

        return $field ? $gold->$field : $gold;
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
