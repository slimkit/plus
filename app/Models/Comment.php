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
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /**
     * Has commentable.
     *
     * @return MorphTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function commentable()
    {
        return $this->morphTo('commentable');
    }

    /**
     * Has a user.
     *
     * @return BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 被回复者.
     * @Author   Wayne
     * @DateTime 2018-04-14
     * @Email    qiaobin@zhiyicx.com
     * @return   [type]              [description]
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'target_user', 'id');
    }

    public function blacks()
    {
        return $this->hasMany(BlackList::class, 'target_id', 'user_id');
    }

    /**
     * 被回复者.
     * @Author   Wayne
     * @DateTime 2018-04-14
     * @Email    qiaobin@zhiyicx.com
     * @return   [type]              [description]
     */
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
