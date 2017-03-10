<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\User;

class UserController extends Controller
{
    public function users(Request $request)
    {
        $sort = $request->query('sort');
        $userId = $request->query('user_id');
        $email = $request->query('email');
        $name = $request->query('name');
        $phone = $request->query('phone');
        $role = $request->query('role');

        $builder = with(new User)->newQuery();

        // user id
        if ($userId && $user = $builder->where('id', $userId)->get()) {
            return response()->json($user);
        }

        foreach ([
            'email' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $email),
                'condition' => boolval($email)
            ],
            'name' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $name),
                'condition' => boolval($name)
            ],
            'phone' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $phone),
                'condition' => boolval($phone)
            ]
        ] as $key => $data) {
            if ($data['condition']) {
                $builder = $builder->where($key, $data['operator'], $data['value']);
            }
        }

        // build sort.
        $builder = $builder->orderBy('id', ($sort === 'down' ? 'desc' : 'asc'));

        $role && $builder = $builder->whereHas('roles', function ($query) use ($role) {
            $query->where('id', $role);
        });

        return response()->json($builder->get());
    }
}
