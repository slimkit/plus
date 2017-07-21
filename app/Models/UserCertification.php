<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    //
    // protected $with = [
    // 	'certification'
    // ];

    protected $hidden = [
    	'uid',
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'data' => 'json'
    ];
}
