<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\PlusGroup\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Comment as CommentModel;

class Pinned extends Model
{
    protected $table = 'group_pinneds';

    /**
     * Has user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author BS <414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     *  Has post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author BS <414606094@qq.com>
     */
    public function post()
    {
        if ($this->channel === 'comment') {
            return $this->hasOne(Post::class, 'id', 'raw');
        }

        return $this->hasOne(Post::class, 'id', 'target');
    }

    /**
     * Has feed comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author BS <414606094@qq.com>
     */
    public function comment()
    {
        return $this->hasOne(CommentModel::class, 'id', 'target');
    }

    public function averages($type = 'post', $date = '')
    {
        return self::newQuery()
            ->where('channel', $type)
            ->whereNotNull('expires_at')
            ->where('amount', '>', 0)
            ->where('day', '>', 0)
            ->where('created_at', '>', $date)
            ->first([
                \DB::raw('SUM(day) as total_day'),
                \DB::raw('SUM(amount) as total_amount')
            ])
            ->toArray();
    }
}
