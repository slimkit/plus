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

namespace SlimKit\PlusSocialite\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserSocialite extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Set type.
     *
     * @param string $type
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setTypeAttribute(string $type)
    {
        $this->attributes['type'] = strtolower($type);
    }

    /**
     * Scope socialite provider.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @param string $unionid
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function scopeProvider(Builder $query, string $type, string $unionid)
    {
        return $query->where('type', strtolower($type))
            ->where('union_id', $unionid);
    }

    /**
     * Scope socialite provider to user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @param string|int $userID
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function scopeProviderToUser(Builder $query, string $type, $userID)
    {
        return $query->where('type', strtolower($type))
            ->where('user_id', $userID);
    }
}
