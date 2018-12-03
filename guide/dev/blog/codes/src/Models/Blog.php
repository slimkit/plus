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

namespace SlimKit\Plus\Packages\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\FileStorage\Traits\EloquentAttributeTrait as FileStorageEloquentAttributeTrait;

class Blog extends Model
{
    use FileStorageEloquentAttributeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The blog has many articles.
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'blog_id');
    }

    /**
     * Get the logo.
     *
     * @param null|string $logo
     *
     * @return null|mixed
     */
    protected function getLogoAttribute($logo)
    {
        if (!$logo) {
            return;
        }

        return $this->getFileStorageResourceMeta($logo);
    }
}
