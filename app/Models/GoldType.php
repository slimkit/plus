<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class GoldType extends Model
{
    public $table = 'gold_types';

    public $fillable = ['name', 'unit', 'status'];
}
