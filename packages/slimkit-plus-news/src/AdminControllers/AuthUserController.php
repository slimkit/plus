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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;
use Zhiyi\Plus\Http\Controllers\Controller;

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
