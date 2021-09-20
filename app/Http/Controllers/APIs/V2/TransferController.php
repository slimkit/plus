<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Http\Requests\API2\Transfer as TransferRequest;
use Zhiyi\Plus\Packages\Wallet\Order;
use Zhiyi\Plus\Packages\Wallet\TypeManager;

class TransferController extends Controller
{
    /**
     * 用户之间转账.
     *
     * @param  TransferRequest  $request
     * @param  TypeManager  $manager
     * @return mixed
     *
     * @author BS <414606094@qq.com>
     */
    public function transfer(TransferRequest $request, TypeManager $manager)
    {
        $user = $request->user();
        $target = $request->input('user');
        $amount = $request->input('amount');

        if ($manager->driver(Order::TARGET_TYPE_USER)->transfer($user, $target, $amount) === true) {
            return response()->json(['message' => ['转账成功']], 201); // 成功
        }

        return response()->json(['message' => ['操作失败，请稍后重试']], 500); // 失败
    }
}
