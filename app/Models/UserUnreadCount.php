<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class UserUnreadCount extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    protected $table = 'user_unread_counts';
}
