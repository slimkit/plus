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
use Zhiyi\Plus\FileStorage\Traits\EloquentAttributeTrait;

class Topic extends Model
{
    use EloquentAttributeTrait;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /**
     * Get topic avatar attribute.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarAttribute(?string $resource)
    {
        if (! $resource) {
            return null;
        }

        return $this->parseFile($resource);
    }

    /**
     * Set the topic avatar.
     * @param mixed $resource
     * @return self
     */
    public function setAvatarAttribute($resource)
    {
        $this->attributes['avatar'] = (string) $resource;

        return $this;
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
}
