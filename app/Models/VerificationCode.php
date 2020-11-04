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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class VerificationCode extends Model
{
    use Notifiable, SoftDeletes, HasFactory;

    /**
     * Get the notification routing information for the given driver.
     *
     * @return mixed
     */
    public function routeNotificationFor()
    {
        return $this->account;
    }

    /**
     * Has User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 设置复用的创建时间范围查询，单位秒.
     *
     * @param Builder $query  查询对象
     * @param int     $second 范围时间，单位秒
     *
     * @return Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByValid(Builder $query, int $second = 300): Builder
    {
        $now = $this->freshTimestamp();
        $sub = clone $now;
        $sub->subSeconds($second);

        return $query->whereBetween('created_at', [$sub, $now]);
    }

    /**
     * 计算距离验证码过期时间.
     *
     * @param int $vaildSecond 验证的总时间
     *
     * @return int 剩余时间
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function makeSurplusSecond(int $vaildSecond = 60): int
    {
        $now = $this->freshTimestamp();
        $differ = $this->created_at->diffInSeconds($now);

        return $vaildSecond - $differ;
    }
}
