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
