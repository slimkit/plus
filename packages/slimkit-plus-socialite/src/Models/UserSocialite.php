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

namespace SlimKit\PlusSocialite\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zhiyi\Plus\Models\User;

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
     * @return BelongsTo
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Set type.
     *
     * @param  string  $type
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setTypeAttribute(string $type): void
    {
        $this->attributes['type'] = strtolower($type);
    }

    /**
     * Scope socialite provider.
     *
     * @param  Builder  $query
     * @param  string  $type
     * @param  string  $unionId
     * @return Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function scopeProvider(Builder $query, string $type, string $unionId): Builder
    {
        return $query->where('type', strtolower($type))
            ->where('union_id', $unionId);
    }

    /**
     * Scope socialite provider to user.
     *
     * @param  Builder  $query
     * @param  string  $type
     * @param  string|int  $userID
     * @return Builder
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function scopeProviderToUser(Builder $query, string $type, $userID): Builder
    {
        return $query->where('type', strtolower($type))
            ->where('user_id', $userID);
    }
}
