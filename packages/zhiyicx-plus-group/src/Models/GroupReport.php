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
use Zhiyi\Plus\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class GroupReport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_reports';

    /**
     * Has user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Has target user.
     *
     * @return mixed
     */
    public function target()
    {
        return $this->hasOne(User::class, 'id', 'target_id');
    }

    /**
     * Has resource.
     *
     * @return mixed
     */
    public function resource()
    {
        if ($this->type === 'post') {
            return $this->hasOne(Post::class, 'id', 'resource_id');
        } else {
            return $this->hasOne(Comment::class, 'id', 'resource_id');
        }
    }

    /**
     * group.
     */
    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }
}
