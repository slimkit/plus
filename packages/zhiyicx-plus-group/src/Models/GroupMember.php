<?php

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
        return $this->hasMany(GroupMemberLog::class, 'id', 'member_id');
    }
}
