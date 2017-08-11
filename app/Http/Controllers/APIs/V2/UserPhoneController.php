<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\VerificationCode as VerificationCodeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class UserPhoneController extends Controller
{
    /**
     * 删除用户手机号码.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function delete(Request $request, ResponseFactoryContract $response)
    {
        $rules = [
            'verifiable_code' => 'required|string',
            'password' => 'required|string',
        ];
        $this->validate($request, $rules);

        $verifiable_code = $request->input('verifiable_code');
        $password = $request->input('password');
        $user = $request->user();
        $verify = VerificationCodeModel::where('channel', 'sms')
            ->where('account', $user->phone)
            ->where('code', $verifiable_code)
            ->orderby('id', 'desc')
            ->first();

        if (! $user->verifyPassword($password)) {
            return $response->json(['message' => ['密码错误']], 422);
        } elseif (! $verify) {
            return $response->json(['message' => ['验证码错误或者已失效']], 422);
        }

        $user->getConnection()->transaction(function () use ($user, $verify) {
            $user->phone = null;
            $user->save();
            $verify->delete();
        });

        return $response->make('', 204);
    }
}
