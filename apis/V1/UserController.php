<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function resetPassword(Request $request)
    {
        $password = $request->input('new_password', '');
        $user = $request->attributes->get('user');
        $user->createPassword($password);
        $user->save();

        return app(MessageResponseBody::class, [
            'status' => true,
        ])->setStatusCode(201);
    }
}
