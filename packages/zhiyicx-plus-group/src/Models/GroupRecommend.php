<?php

namespace Zhiyi\PlusGroup\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class GroupRecommend extends Model
{

    protected $fillable = ['group_id', 'disable', 'sort_by', 'referrer'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_recommends';

    /**
     * The user of post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     * @author BS <414606094@qq.com>
     */
    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    /**
     * 推荐人
     */
    public function referrer()
    {
        return $this->hasOne(User::class, 'id', 'referrer');
    }
}
