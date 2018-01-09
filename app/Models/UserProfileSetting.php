<?php

declare(strict_types=1);

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
use Illuminate\Database\Eloquent\Builder;

class UserProfileSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'create_user_id', 'profile', 'profile_name', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'create_user_id',
        'required',
        'is_delable',
        'is_show',
        'state',
        'created_at',
        'updated_at',
    ];

    /**
     * Gets the field content of the view layer.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-17T18:28:06+0800
     *
     * @return [type] [description]
     */
    public function scopeByIsShow(Builder $query, int $is_show): Builder
    {
        return $query->where('is_show', $is_show);
    }

    /**
     * Gets the field contents of the specified status.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-17T18:32:19+0800
     *
     * @param Builder $query [description]
     *
     * @return [type] [description]
     */
    public function scopeByState(Builder $query, int $state): Builder
    {
        return $query->where('state', $state);
    }

    /**
     * Get the required content.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-17T18:37:58+0800
     *
     * @param Builder     $query       [description]
     * @param tinyinteger $is_required [description]
     *
     * @return [type] [description]
     */
    public function scopeByRequired(Builder $query, int $is_required): Builder
    {
        return $query->where('required', $is_required);
    }

    public function datas()
    {
        return $this->hasMany(UserProfileSettingLink::class, 'user_profile_setting_id', 'id');
    }
}
