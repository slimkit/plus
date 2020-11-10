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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Zhiyi\Plus\Models\Area
 *
 * @property int $id
 * @property string $name 名字
 * @property int $pid 父级ID
 * @property string|null $extends 扩展内容
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Area[] $items
 * @property-read int|null $items_count
 * @property-read Area|null $parent
 * @method static Builder|Area byPid($pid)
 * @method static Builder|Area newModelQuery()
 * @method static Builder|Area newQuery()
 * @method static Builder|Area query()
 * @method static Builder|Area whereCreatedAt($value)
 * @method static Builder|Area whereExtends($value)
 * @method static Builder|Area whereId($value)
 * @method static Builder|Area whereName($value)
 * @method static Builder|Area wherePid($value)
 * @method static Builder|Area whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
