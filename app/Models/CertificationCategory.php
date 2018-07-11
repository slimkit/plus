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

class CertificationCategory extends Model
{
    use Concerns\HasAvatar;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = ['icon'];

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
        return $this->getKey();
    }
}
