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

class Certification extends Model
{
    use Concerns\HasAvatar;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['icon'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['category', 'user'];

    /**
     * Get icon url.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getIconAttribute()
    {
        return $this->avatar();
    }

    /**
     * Get avatar trait.
     *
     * @return string|int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarKey()
    {
        return $this->certification_name;
    }

    /**
     * avatar extensions.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarExtensions(): array
    {
        return ['png', 'jpg', 'jpeg', 'bmp'];
    }

    /**
     * Avatar prefix.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarPrefix(): string
    {
        return 'certifications';
    }

    /**
     * Has certification caregory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function category()
    {
        return $this->hasOne(CertificationCategory::class, 'name', 'certification_name');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
