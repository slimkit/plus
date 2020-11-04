<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeByStatus(Builder $builder, int $status): Builder
    {
        return $builder->where('status', $status);
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
