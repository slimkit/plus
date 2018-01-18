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

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Has commentable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function commentable()
    {
        return $this->morphTo('commentable');
    }

    /**
     * Has a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_user', 'id');
    }

    public function reply()
    {
        return $this->belongsTo(User::class, 'reply_user', 'id');
    }

    /**
     * 被举报记录.
     *
     * @return morphMany
     * @author BS <414606094@qq.com>
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
