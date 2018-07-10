<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

class Reward extends Model
{
    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Has rewardable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rewardable()
    {
        return $this->morphTo();
    }

    /**
     * Has user for the rewardable.
     *
     * @author bs<414606094@qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Has target for the rewardable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function target()
    {
        return $this->hasOne(User::class, 'id', 'target_user');
    }
}
