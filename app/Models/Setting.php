<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The table name.
     * @var string
     */
    protected $table = 'settings';

    /**
     * Where by namespace scope.
     */
    public function scopeByNamespace($query, string $namespace)
    {
        return $query->where('namespace', '$namespace');
    }

    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    public function setContentsAttribute($contents)
    {
        $this->attributes['contents'] = serialize($contents);

        return $this;
    }

    public function getContentsAttribute(string $contents)
    {
        return unserialize($contents);
    }
}
