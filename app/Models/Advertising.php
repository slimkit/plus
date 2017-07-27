<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    protected $fillable = ['space_id', 'type', 'image', 'data'];
}
