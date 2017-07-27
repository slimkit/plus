<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Contracts\Model\ShouldAvatar;

class Advertising extends Model
{
    protected $fillable = ['space_id', 'type', 'image', 'data'];
}
