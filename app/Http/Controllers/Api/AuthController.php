<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getPhoneCode(Request $request)
    {
        $phone = $request->input('phone');
    }

    /**
     * 注册用户.
     *
     * @param Request $request 请求对象
     *
     * @return Response 返回对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function register(Request $request)
    {
        $username = $request->input('username');
        $phone = $request->input('phone');
        $password = $request->input('password');
    }
}
