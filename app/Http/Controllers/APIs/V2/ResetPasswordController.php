<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\VerificationCode as VerificationCodeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class ResetPasswordController extends Controller
{
    /**
     * Reset password.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function reset(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();

        // 用户未设置密码时，只需设置新密码
        if ($user->password === null) {
            return $this->setPassword($request, $user);
        }

        $this->validate($request, $this->resetRules(), $this->resetValidationErrorMessages());

        if (! $user->verifyPassword($request->input('old_password'))) {
            return $response->json(['old_password' => ['账户密码错误']], 422);
        }

        $user->createPassword($request->input('password'));
        $user->save();

        return $response->make('', 204);
    }

    /**
     * Get reset validateion rules.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resetRules(): array
    {
        return [
            'old_password' => 'required|string',
            'password' => 'required|string|different:old_password|confirmed',
        ];
    }

    /**
     * Get reset validation error messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resetValidationErrorMessages(): array
    {
        return [
            'old_password.required' => '请输入账户密码',
            'old_password.string' => '密码必须是字符串',
            'password.required' => '请输入新密码',
            'password.string' => '密码必须是字符串',
            'password.different' => '新密码和旧密码相同',
            'password.confirmed' => '确认输入的新密码不一致',
        ];
    }

    /**
     * Set new password.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  User  $user
     */
    public function setPassword(Request $request, UserModel $user)
    {
        $this->validate($request, [
            'password' => 'required|string|confirmed',
        ], $this->resetValidationErrorMessages());

        $user->createPassword($request->input('password'));
        $user->save();

        return response()->make('', 204);
    }

    /**
     * Retrueve user password.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\VerificationCode $verificationCodeModel
     * @param \Zhiyi\Plus\Models\User $userModel
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function retrieve(Request $request,
                             ResponseFactoryContract $response,
                             VerificationCodeModel $verificationCodeModel,
                             UserModel $userModel)
    {
        if ($request->input('phone') && $request->input('email')) {
            return $response->json(['message' => ['非法请求']], 400);
        }

        $this->validate($request, [
            'verifiable_type' => 'required|in:mail,sms',
            'verifiable_code' => 'required',
            'phone' => 'required_unless:verifiable_type,mail|cn_phone|exists:users,phone',
            'email' => 'required_unless:verifiable_type,sms|email|exists:users,email',
            'password' => 'required|string',
        ]);

        $field = $request->input('phone') ? 'phone' : 'email';
        $user = $userModel->where($field, $account = $request->input($field))->first();
        $verificationCode = $verificationCodeModel->where('channel', $request->input('verifiable_type'))
            ->where('code', $request->input('verifiable_code'))
            ->where('account', $account)
            ->first();

        if (! $verificationCode) {
            return $response->json(['message' => ['验证码错误或者已失效']], 422);
        }

        $user->createPassword($request->input('password'));
        $user->save();
        $verificationCode->delete();

        return $response->make('', 204);
    }
}
