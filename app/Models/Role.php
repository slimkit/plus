<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The role bind users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * The role bind permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function perms()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    /**
     * Boot the role model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($role) {
            if (! method_exists($role, 'bootSoftDeletes')) {
                $role->users()->sync([]);
                $role->perms()->sync([]);
            }

            return true;
        });
    }

    public function cachedPermissions()
    {
        $rolePrimaryKey = $this->primaryKey;
        $cacheKey = 'permissions_for_role_'.$this->$rolePrimaryKey;
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('permission_role_table')->remember($cacheKey, Config::get('cache.ttl', 60), function () {
                return $this->perms()->get();
            });
        }

        return $this->perms()->get();
    }

    public function save(array $options = [])
    {   //both inserts and updates
        if (! parent::save($options)) {
            return false;
        }

        $this->flushPermissionRoleTableCeche();

        return true;
    }

    public function delete(array $options = [])
    {   //soft or hard
        if (! parent::delete($options)) {
            return false;
        }

        $this->flushPermissionRoleTableCeche();

        return true;
    }

    public function restore()
    {   //soft delete undo's
        if (! parent::restore()) {
            return false;
        }

        $this->flushPermissionRoleTableCeche();

        return true;
    }

    protected function flushPermissionRoleTableCeche()
    {
        if (Cache::getStore() instanceof TaggableStore) {
            Cache::tags('permission_role_table')->flush();
        }
    }
}
