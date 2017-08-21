<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class JWTCache extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jwt_caches';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
    ];
}
