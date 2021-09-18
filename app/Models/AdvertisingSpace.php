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

/**
 * Zhiyi\Plus\Models\AdvertisingSpace.
 *
 * @property int $id
 * @property string $channel 广告位频道
 * @property string $space 广告位位置标识
 * @property string $alias 广告位别名
 * @property string $allow_type 允许的广告类型
 * @property array $format 广告数据格式
 * @property array $rule 广告位表单验证规则
 * @property array $message 广告位表单验证提示
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zhiyi\Plus\Models\Advertising[] $advertising
 * @property-read int|null $advertising_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereAllowType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereSpace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdvertisingSpace whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdvertisingSpace extends Model
{
    protected $table = 'advertising_space';

    protected $fillable = ['channel', 'space', 'alias', 'allow_type', 'format', 'rule', 'message'];

    protected $casts = [
        'format' => 'array',
        'rule' => 'array',
        'message' => 'array',
    ];

    public function advertising()
    {
        return $this->hasMany(Advertising::class, 'space_id');
    }
}
