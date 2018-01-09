<?php declare(strict_types=1);

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

class UserProfileSettingLink extends Model
{
    protected $fillable = [
        'user_id',
        'user_profile_setting_id',
        'user_profile_setting_data',
    ];

    protected $hidden = [
        'user_profile_setting_id',
        'user_profile_setting_data',
    ];

    /**
     * 通过用户id查找扩展资料.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-18T00:26:33+0800
     *
     * @param Builder $query   [description]
     * @param int     $user_id [description]
     *
     * @return [type] [description]
     */
    public function scopeByUserId(Builder $query, int $user_id): Builder
    {
        return $query->where('user_id', $user_id);
    }

    public function name()
    {
        return $this->belongsTo(UserProfileSetting::class, 'user_profile_setting_id', 'id');
    }
}
