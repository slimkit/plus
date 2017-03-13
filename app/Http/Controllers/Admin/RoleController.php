<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Role;

class RoleController extends Controller
{
    /**
     * Get roles.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function roles()
    {
        $roles = Role::all();

        return response()->json($roles)->setStatusCode(200);
    }
}
