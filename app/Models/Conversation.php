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
 * Zhiyi\Plus\Models\Conversation.
 *
 * @property int $id
 * @property string $type 会话类型 system 系统通知 feedback 用户反馈
 * @property int $user_id 用户ID
 * @property int $to_user_id 被发送用户ID
 * @property string $content 会话内容
 * @property string|null $options 给推送平台的额外参数
 * @property int $system_mark 移动端存储标记
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User|null $target
 * @property-read \Zhiyi\Plus\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereSystemMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereToUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereUserId($value)
 * @mixin \Eloquent
 */
class Conversation extends Model
{
    protected $fillable = ['type', 'user_id', 'content', 'options', 'system_mark'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function target()
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }
}
