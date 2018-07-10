<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
