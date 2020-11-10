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
 * Zhiyi\Plus\Models\Advertising.
 *
 * @property int $id
 * @property int $space_id 广告位id
 * @property string $title 广告标题
 * @property string $type 类型
 * @property array|null $data 相关参数
 * @property int $sort 广告位排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\AdvertisingSpace $space
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising query()
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereSpaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Advertising whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Advertising extends Model
{
    protected $table = 'advertising';

    protected $fillable = ['space_id', 'type', 'title', 'data', 'sort'];

    protected $casts = [
        'data' => 'array',
    ];

    public function space()
    {
        return $this->belongsTo(AdvertisingSpace::class);
    }
}
