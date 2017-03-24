<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

class UserController extends Controller
{
    public function users(Request $request)
    {
        $sort = $request->query('sort');
        $userId = $request->query('userId');
        $email = $request->query('email');
        $name = $request->query('name');
        $phone = $request->query('phone');
        $role = $request->query('role');
        $perPage = $request->query('perPage', 10);
        $showRole = $request->has('show_role');

        $builder = with(new User())->newQuery();

        // user id
        if ($userId && $users = $builder->where('id', $userId)->paginate($perPage)) {
            return response()->json($users);
        }

        foreach ([
            'email' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $email),
                'condition' => boolval($email),
            ],
            'name' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $name),
                'condition' => boolval($name),
            ],
            'phone' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $phone),
                'condition' => boolval($phone),
            ],
        ] as $key => $data) {
            if ($data['condition']) {
                $builder->where($key, $data['operator'], $data['value']);
            }
        }

        // build sort.
        $builder->orderBy('id', ($sort === 'down' ? 'desc' : 'asc'));

        $role && $builder->whereHas('roles', function ($query) use ($role) {
            $query->where('id', $role);
        });

        $data = [];
        $data['page'] = $builder->paginate($perPage);

        if ($showRole) {
            $data['roles'] = Role::all();
        }

        return response()->json($data)->setStatusCode(200);
    }
}
