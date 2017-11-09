<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisingSpace extends Model
{
    protected $table = 'advertising_space';

    protected $fillable = ['channel', 'space', 'alias', 'allow_type', 'format', 'rule', 'message'];

    protected $casts = [
        'format' => 'array',
        'rule' => 'array',
        'message' => 'array',
    ];

    public function advertising()
    {
        return $this->hasMany(Advertising::class, 'space_id');
    }
}
