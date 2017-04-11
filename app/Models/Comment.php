<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['component', 'comment_id', 'user_id', 'to_user_id', 'reply_to_user_id', 'comment_table', 'source_table', 'source_id'];
}
