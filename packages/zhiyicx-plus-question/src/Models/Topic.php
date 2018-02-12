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
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Concerns\HasAvatar;

class Topic extends Model
{
    use HasAvatar;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar'];

    /**
     * Get topic avatar attribute.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    /**
     * Topic followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers()
    {
        return $this->belongsToMany(User::class)
            ->using(TopicUser::class)
            ->withTimestamps();
    }

    /**
     * Topic experts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function experts()
    {
        return $this->belongsToMany(User::class, 'topic_expert')
            ->using(TopicExpert::class)
            ->withTimestamps();
    }

    /**
     * Has questions for the topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_topic')
            ->using(QuestionTopic::class);
    }

    /**
     * Get avatar trait.
     *
     * @return string|int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarKey()
    {
        return $this->getKey();
    }

    /**
     * Avatar prefix.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarPrefix(): string
    {
        return 'question/topics';
    }
}
