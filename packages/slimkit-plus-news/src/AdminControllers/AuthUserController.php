<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;

/**
 * 用户认证管理.
 */
class AuthUserController extends Controller
{
    public function getAuthUserList(Request $request)
    {
        $key = $request->key;
        $datas = UserVerified::orderBy('id', 'desc')
            ->where(function ($query) use ($key) {
                if ($key) {
                    $query->where('realname', 'like', '%'.$key.'%');
                }
            })
            ->with('user')
            ->get();

        return response()->json($datas)->setStatusCode(200);
    }

    /**
     * 认证用户.
     *
     * @param  Request $request [description]
     * @param  int     $aid     [description]
     * @return [type]           [description]
     */
    public function audit(Request $request, int $aid)
    {
        $state = $request->state;
        $verified = UserVerified::find($aid);
        if ($verified) {
            UserVerified::where('id', $aid)->update(['verified' => $state]);

            return response()->json([
                'message' => ['认证成功'],
            ])->setStatusCode(200);
        }
    }

    /**
     * 删除用户认证信息.
     *
     * @param  int    $aid [description]
     * @return [type]      [description]
     */
    public function delAuthInfo(int $aid)
    {
        $verified = UserVerified::find($aid);
        if ($verified) {
            $verified->delete();

            return response()->json([
                'message' => ['删除成功'],
            ])->setStatusCode(200);
        }
    }
}
