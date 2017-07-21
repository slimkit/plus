<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $hidden = [
    	'created_at',
    	'updated_at',
    	'user_id'
    ];
    //
    public function userCertifications()
    {
    	return $this->hasMany(UserCertification::class, 'Certification');
    }
}
