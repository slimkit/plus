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
 * Zhiyi\Plus\Models\CommonConfig.
 *
 * @property int $id config id
 * @property string $name 配置名称
 * @property string $namespace 配置命名空间
 * @property string|null $value 缓存值
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|CommonConfig byName($name)
 * @method static Builder|CommonConfig byNamespace($namespace)
 * @method static Builder|CommonConfig newModelQuery()
 * @method static Builder|CommonConfig newQuery()
 * @method static Builder|CommonConfig query()
 * @method static Builder|CommonConfig whereCreatedAt($value)
 * @method static Builder|CommonConfig whereId($value)
 * @method static Builder|CommonConfig whereName($value)
 * @method static Builder|CommonConfig whereNamespace($value)
 * @method static Builder|CommonConfig whereUpdatedAt($value)
 * @method static Builder|CommonConfig whereValue($value)
 * @mixin \Eloquent
 */
class CommonConfig extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['name', 'namespace', 'value'];

    /**
     * Scope func to namespace.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string                               $namespace
     *
     * @return Illuminate\Database\Eloquent\Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByNamespace(Builder $query, string $namespace): Builder
    {
        return $query->where('namespace', $namespace);
    }

    /**
     * Scope func to name.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string                               $name
     *
     * @return Illuminate\Database\Eloquent\Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }
}
