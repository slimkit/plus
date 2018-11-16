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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Navigation extends Model
{
    protected $table = 'navigation';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'app_name', 'url', 'target', 'status', 'position', 'parent_id', 'order_sort'];

    /**
     * 获取位置导航.
     *
     * @param  Builder $query
     * @param  int     $pos   0-头部 1-底部
     * @return Builder
     */
    public function scopeByPos(Builder $query, int $pos)
    {
        return $query->where('position', $pos);
    }

    public function scopeByPid(Builder $query, int $pid): Builder
    {
        return $query->where('parent_id', $pid);
    }

    public function parent()
    {
        return $this->hasOne(__CLASS__, 'id', 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }
}
