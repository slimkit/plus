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
 * Zhiyi\Plus\Models\UserUnreadCount.
 *
 * @property int $user_id 用户ID
 * @property int $unread_comments_count 未读评论数
 * @property int $unread_likes_count 未读点赞数
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount whereUnreadCommentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount whereUnreadLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnreadCount whereUserId($value)
 * @mixin \Eloquent
 */
class UserUnreadCount extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    protected $table = 'user_unread_counts';
}
