<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Digg extends Model
{
    protected $table = 'diggs';

    protected $fillable = ['component', 'digg_table', 'digg_id', 'user_id', 'to_user_id', 'reply_to_user_id', 'source_table', 'source_id', 'source_content', 'source_cover'];
}
