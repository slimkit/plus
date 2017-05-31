<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Http\Controllers\Controller;

class WalletCashTypeController extends Controller
{
    /**
     * 获取提现类型.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show()
    {
        $types = CommonConfig::byNamespace('wallet')
            ->byName('cash')
            ->value('value') ?: '[]';

        return response()
            ->json()
            ->setJson($types)
            ->setStatusCode(200);
    }

    /**
     * 更新提现设置.
     *
     * @param Request $request
     * @return mexed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request)
    {
        $model = CommonConfig::firstOrNew(
            ['name' => 'cash', 'namespace' => 'wallet'],
            ['value' => '[]']
        );

        $rules = [
            'types' => 'array|in_array:alipay,wechat',
        ];
        $messages = [
            'types.array' => '提交的数据有误，请刷新重试',
            'types.in_array' => '提交的数据不合法，请刷新重试',
        ];

        $types = $request->input('types', []);
        $model->value = json_encode($types);

        if ($model->save()) {
            return response()
                ->json(['messages' => ['更新成功']])
                ->setStatusCode(201);
        }

        return response()
            ->json(['messages' => ['更新失败']])
            ->setStatusCode(500);
    }
}
