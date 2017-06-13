<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class PaidNode extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'node';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
