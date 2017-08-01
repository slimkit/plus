<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    protected $table = 'advertising';

    protected $fillable = ['space_id', 'type', 'title', 'data', 'sort'];

    protected $casts = [
        'data' => 'array',
    ];
}
