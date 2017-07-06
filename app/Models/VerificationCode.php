<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationCode extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * Get the notification routing information for the given driver.
     *
     * @return mixed
     */
    public function routeNotificationFor()
    {
        return $this->account;
    }

    /**
     * Has User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
