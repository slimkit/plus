<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VerifyCode;
use App\Exceptions\MessageResponseBody;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getPhoneCode(Request $request)
    {
        $vaildSecond = 60;
        $phone = $request->input('phone');
        $verfiy = VerifyCode::byAccount($phone)->byValid($vaildSecond)->first();


        if ($verfiy) {
            return app(MessageResponseBody::class, [
                'code' => 1008,
                'data' => $verfiy->makeSurplusSecond($vaildSecond),
            ]);
        }

        $verify = new VerifyCode();
        $verify->accound = $phone;
        $verify->makeVerifyCode();
        $verify->data = [
            'code' => $verify->code,
        ];

        var_dump($verify);

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
