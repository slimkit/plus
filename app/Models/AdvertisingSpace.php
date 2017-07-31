<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisingSpace extends Model
{
    protected $table = 'advertising_space';

    protected $fillable = ['channel', 'space', 'alias', 'allow_type', 'format'];

    public function getFormatAttribute($format)
    {
        return json_decode($format);
    }

    public function advertising()
    {
        return $this->hasMany(Advertising::class, 'space_id');
    }
}
