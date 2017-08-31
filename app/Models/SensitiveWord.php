<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class SensitiveWord extends Model
{
    public $table = 'sensitive_words';

    public $fillable = [
        'name',
        'replace_name',
        'filter_word_category_id',
        'filter_word_type_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filterWordCategory()
    {
        return $this->belongsTo(FilterWordCategory::class);
    }

    public function filterWordType()
    {
        return $this->belongsTo(FilterWordType::class);
    }
}
