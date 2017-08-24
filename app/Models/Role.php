<?php

namespace Zhiyi\Plus\Models;

// use Illuminate\Cache\TaggableStore;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Get all abilities of the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'ability_role', 'role_id', 'ability_id');
    }

    /**
     * Get all users of the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * Get or check The role ability.
     *
     * @param string $ability
     * @return false|\User\Plus\Models\Ability
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function ability(string $ability)
    {
        return $this->abilities->keyBy('name')->get($ability, false);
    }
}
