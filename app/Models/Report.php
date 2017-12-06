<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

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
