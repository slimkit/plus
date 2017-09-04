<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilterWordCategory extends Model
{
    use SoftDeletes;

    public $table = 'filter_word_categories';

    public $fillable = ['name'];

    public function sensitives()
    {
        return $this->hasMany(SensitiveWord::class);
    }
}
