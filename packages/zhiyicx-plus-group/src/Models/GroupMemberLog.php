<?php

namespace Zhiyi\PlusGroup\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class GroupMemberLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_member_logs';

    protected $fillable = ['group_id', 'user_id', 'status', 'auditer', 'audit_at'];

    /**
     * The user of log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     * @author BS <414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 所属圈子.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     * @author BS <414606094@qq.com>
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * 审核者信息.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     * @author BS <414606094@qq.com>
     */
    public function audit_user()
    {
        return $this->hasOne(User::class, 'id', 'auditer');
    }

    /**
     * 关联圈子成员信息.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     * @author BS <414606094@qq.com>
     */
    public function member_info()
    {
        return $this->belongsTo(GroupMember::class, 'member_id', 'id');
    }
}
