<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class ImConversation extends Model
{
    /**
     * 定义表名.
     *
     * @var string
     */
    protected $table = 'im_conversations';

    /**
     * 定义允许更新的字段.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'cid', 'name', 'pwd', 'type', 'uids', 'created_at'];

    /**
     * 定义隐藏的字段.
     *
     * @var array
     */
    protected $hidden = ['id', 'is_disabled', 'created_at', 'updated_at'];

    public function setUidsAttribute($uids)
    {
        $uids = is_array($uids) ? $uids : explode(',', $uids);
        sort($uids);
        $this->attributes['uids'] = implode(',', $uids);
    }
}
