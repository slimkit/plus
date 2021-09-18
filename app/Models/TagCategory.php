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
 * Zhiyi\Plus\Models\TagCategory.
 *
 * @property int $id
 * @property string $name 标签类别
 * @property int $weight 权重,排序用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zhiyi\Plus\Models\Tag[] $tags
 * @property-read int|null $tags_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCategory whereWeight($value)
 * @mixin \Eloquent
 */
class TagCategory extends Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Has tags of the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function tags()
    {
        return $this->hasMany(Tag::class, 'tag_category_id', 'id')
            ->orderBy('weight', 'desc');
    }
}
