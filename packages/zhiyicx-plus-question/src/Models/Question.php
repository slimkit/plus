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

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Report;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SlimKit\PlusQuestion\Services\Markdown as MarkdownService;

class Question extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * Has topics for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'question_topic')
            ->using(QuestionTopic::class);
    }

    /**
     * Has invitation users for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function invitations()
    {
        return $this->belongsToMany(User::class, 'question_invitation');
    }

    /**
     * Has answers for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    /**
     * Has the user for question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Has watch users for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function watchers()
    {
        return $this->belongsToMany(User::class, 'question_watcher')
            ->using(QuestionWatcher::class)
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * A question may have one or more applications.
     *
     * @author bs<414606094@qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     */
    public function applications()
    {
        return $this->hasMany(QuestionApplication::class, 'question_id', 'id');
    }

    /**
     * 过滤body字段的非法标签.
     *
     * @author bs<414606094@qq.com>
     * @param  $body
     */
    public function setBodyAttribute($body)
    {
        if (is_null($body)) {
            $this->attributes['body'] = null;

            return $this;
        }

        $this->attributes['body'] = app(MarkdownService::class)->safetyMarkdown($body);
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
