<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Report;
use Zhiyi\Plus\Models\Reward;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SlimKit\PlusQuestion\Services\Markdown as MarkdownService;

class Answer extends Model
{
    use SoftDeletes,
        Relations\AnswerHasLike,
        Relations\AnswerHasCollect;

    /**
     * Has the question for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }

    /**
     * Has user for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Has onlookers for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function onlookers()
    {
        return $this->belongsToMany(User::class, 'answer_onlooker', 'answer_id', 'user_id')
            ->using(AnswerOnlooker::class)
            ->withTimestamps();
    }

    /**
     * Has rewarders for answer.
     *
     * @return [type] [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rewarders()
    {
        return $this->morphMany(Reward::class, 'rewardable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * 过滤body字段的非法标签
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

        $this->attributes['body'] = app(MarkdownService::class)->safetyMarkdown(
            strval($body)
        );
    }

    /**
     * 被举报记录
     *
     * @return morphMany
     * @author BS <414606094@qq.com>
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
