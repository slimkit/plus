<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Repository\UserWalletCashType;

class WalletCashTypeController extends Controller
{
    /**
     * 获取提现类型.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(UserWalletCashType $repository)
    {
        return response()
            ->json($repository->get())
            ->setStatusCode(200);
    }

    /**
     * 更新提现设置.
     *
     * @param Request $request
     * @return mexed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, UserWalletCashType $repository)
    {
        $rules = [
            'types' => 'array|in:alipay,wechat',
        ];
        $messages = [
            'types.array' => '提交的数据有误，请刷新重试',
            'types.in_array' => '提交的数据不合法，请刷新重试',
        ];

        $this->validate($request, $rules, $messages);
        $repository->store(
            $request->input('types', [])
        );

        return response()
            ->json(['messages' => ['更新成功']])
            ->setStatusCode(201);
    }
}
