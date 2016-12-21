<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Sms;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getPhoneCode(Request $request)
    {
        $phone = $request->input('phone');
        $data = Sms::byPhone($phone)->byDesc()->first();
        var_dump($data);
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
