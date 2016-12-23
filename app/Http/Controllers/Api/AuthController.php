<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MessageResponseBody;
use App\Handler\SendMessage;
use App\Http\Controllers\Controller;
use App\Models\VerifyCode;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getPhoneCode(Request $request)
    {
        $vaildSecond = 600;
        $phone = $request->input('phone');
        $verify = VerifyCode::byAccount($phone)->byValid($vaildSecond)->orderByDesc()->first();

        if ($verify) {
            return app(MessageResponseBody::class, [
                'code' => 1008,
                'data' => $verify->makeSurplusSecond($vaildSecond),
            ]);
        }

        $verify = new VerifyCode();
        $verify->account = $phone;
        $verify->makeVerifyCode();
        $verify->data = [
            'code' => $verify->code,
        ];
        $verify->save();

        return app(SendMessage::class, [ $verify, 'type' => 'phone' ])->send();
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
