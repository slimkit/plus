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
use Zhiyi\Plus\Models\Report;
use Zhiyi\Plus\Models\Reward;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\BlackList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zhiyi\Plus\Models\FileWith as FileWithModel;

class Post extends Model
{
    use SoftDeletes,
        Relations\PostHasLike,
        Relations\PostHasCollect,
        Relations\PostHasReward;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_posts';

    protected $fillable = ['user_id', 'group_id', 'title', 'body', 'summary'];

    /**
     * The group of post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     * @author BS <414606094@qq.com>
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * The user of post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     * @author BS <414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * black list of current user
     * @Author   Wayne
     * @DateTime 2018-04-14
     * @Email    qiaobin@zhiyicx.com
     * @return   [type]              [description]
     */
    public function blacks()
    {
        return $this->hasMany(BlackList::class, 'target_id', 'user_id');
    }

    /**
     * Comments of post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphToMany
     * @author BS <414606094@qq.com>
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Has post pinned.
     *
     * @return [type] [description]
     * @author BS <414606094@qq.com>
     */
    public function pinned()
    {
        return $this->hasOne(Pinned::class, 'target', 'id')
            ->where('channel', 'post');
    }

    /**
     * images.
     */
    public function images()
    {
        return $this->hasMany(FileWithModel::class, 'raw', 'id')->where('channel', 'group:post:image');
    }

    /**
     * latest pinned.
     */
    public function latestPinned()
    {
        return $this->hasOne(Pinned::class, 'target', 'id')
            ->where('channel', 'post')->orderBy('created_at', 'desc');
    }

    /**
     * 被举报记录.
     *
     * @return morphMany
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
