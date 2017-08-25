<?php

namespace Zhiyi\Plus\Models\Concerns;

use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Services\UserAbility;

trait UserHasAbility
{
    /**
     * Abiliry service instance.
     *
     * @var \Zhiyi\Plus\Services\UserAbility
     */
    protected $ability;

    /**
     * User ability.
     *
     * @param array $parameters
     *        ability();
     *        ability($ability);
     *        ability($role, $ability);
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function ability(...$parameters)
    {
        if (isset($parameters[1])) {
            return ($role = $this->resolveAbility()->roels($parameters[0]))
                ? $role->ability($parameters[1])
                : false;
        } elseif (isset($parameters[0])) {
            return $this->resolveAbility()
                ->all($parameters[0]);
        }

        return $this->resolveAbility();
    }

    /**
     * The user all roles.
     *
     * @param string $role
     * @return mied
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function roles(string $role = '')
    {
        if ($role) {
            return $this->ability()->roles($role);
        }

        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Resolve ability service.
     *
     * @return \Zhiyi\Plus\Services\UserAbility
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveAbility()
    {
        if (! ($this->ability instanceof UserAbility)) {
            $this->ability = new UserAbility();
        }

        return $this->ability->setUser($this);
    }
}
