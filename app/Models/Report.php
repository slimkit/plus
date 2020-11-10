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

/**
 * Zhiyi\Plus\Models\Report.
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $target_user 对方用户ID
 * @property string $subject 被举报资源名
 * @property string $reportable_type
 * @property int $reportable_id
 * @property int $status 处理状态 0-待处理 1-已处理 2-已驳回
 * @property string|null $reason 举报理由
 * @property string|null $mark 处理标记
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $reportable
 * @property-read \Zhiyi\Plus\Models\User|null $target
 * @property-read \Zhiyi\Plus\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereTargetUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 * @mixin \Eloquent
 */
class Report extends Model
{
    public function reportable()
    {
        return $this->morphTo('reportable');
    }

    /**
     * 举报者.
     *
     * @return hasOne
     * @author BS <414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 被举报者.
     *
     * @return hasOne
     * @author BS <414606094@qq.com>
     */
    public function target()
    {
        return $this->hasOne(User::class, 'id', 'target_user');
    }
}
