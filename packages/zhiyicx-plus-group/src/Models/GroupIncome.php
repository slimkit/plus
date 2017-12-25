<?php

namespace Zhiyi\PlusGroup\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class GroupIncome extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_incomes';

    /**
     * 收入来源者.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     * @author BS <414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
