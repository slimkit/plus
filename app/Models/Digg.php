<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Digg extends Model
{
    protected $table = 'diggs';

    protected $fillable = ['component', 'digg_id', 'user_id', 'to_user_id', 'reply_to_user_id'];
}
