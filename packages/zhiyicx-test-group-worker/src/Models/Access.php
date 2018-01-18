<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'test_group_worker_accesses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'owner';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
