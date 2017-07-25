<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
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
}
