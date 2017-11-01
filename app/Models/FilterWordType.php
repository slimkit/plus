<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class FilterWordType extends Model
{
    public $table = 'filter_word_types';

    public $fillable = ['name', 'status'];

    public $timestamps = false;
}
