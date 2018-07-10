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

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Area extends Model
{
    /**
     * Scope func by pid.
     *
     * @param Builder $query
     * @param int     $pid
     *
     * @return Illuminate\Database\Eloquent\Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByPid(Builder $query, int $pid): Builder
    {
        return $query->where('pid', $pid);
    }

    /**
     * 父地区.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function parent()
    {
        return $this->hasOne(__CLASS__, 'id', 'pid');
    }

    /**
     * 子地区.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function items()
    {
        return $this->hasMany(__CLASS__, 'pid');
    }
}
