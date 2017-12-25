<?php

namespace Zhiyi\PlusGroup\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_categories';

    protected $fillable = ['name', 'sort_by', 'status'];

    /**
     * The category groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'category_id');
    }
}
