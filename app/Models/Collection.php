<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];
}
