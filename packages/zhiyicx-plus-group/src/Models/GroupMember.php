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
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use Concerns\MemberPermission;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_members';

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

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * 成员申请记录.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     * @author BS <414606094@qq.com>
     */
    public function logs()
    {
        return $this->hasMany(GroupMemberLog::class, 'member_id', 'id');
    }

    /**
     * 是否是正常成员.
     *
     * @return mixed
     */
    public function isNormal(int $groupId, int $userId)
    {
        $count = self::where('audit', 1)
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->where('disabled', 0)
            ->count();

        return boolval($count);
    }

    /**
     * 通过成员所在的圈子,获取相关的帖子
     * @Author   Wayne
     * @DateTime 2018-05-03
     * @Email    qiaobin@zhiyicx.com
     * @return   [type]              [description]
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'group_id', 'group_id');
    }
}
