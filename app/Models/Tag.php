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

class Tag extends Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /**
     * Has the category of tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function category()
    {
        return $this->hasOne(TagCategory::class, 'id', 'tag_category_id');
    }

    /**
     * 统计使用了多少次
     */
    public function taggable()
    {
        return $this->hasMany(Taggable::class, 'tag_id', 'id');
    }
}
