<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'test_group_worker_projects';

    /**
     * the project issues.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function isseus(): HasMany
    {
        return $this->hasMany(Issue::class, 'project_id', 'id');
    }
}
