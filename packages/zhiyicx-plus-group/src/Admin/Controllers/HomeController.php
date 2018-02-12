<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\Post;
use Zhiyi\Plus\Auth\JWTAuthToken;
use Zhiyi\PlusGroup\Models\Group;
use Zhiyi\Plus\Support\Configuration;

class HomeController
{
    public function index(Request $request, JWTAuthToken $jwt)
    {
        config('jwt.single_auth', false);

        return view('plus-group::admin', [
            'api_token' => $jwt->create($request->user()),
        ]);
    }

    public function statistics()
    {
        $items = [
            'all' => $this->countGroupByMode('all'),
            'pub' => $this->countGroupByMode('public'),
            'pri' => $this->countGroupByMode('private'),
            'pai' => $this->countGroupByMode('paid'),
            'post' => Post::count(),
        ];

        return response()->json($items, 200);
    }

    public function countGroupByMode(string $mode = 'all')
    {
        return Group::where('audit', 1)
                 ->when($mode !== 'all', function ($query) use ($mode) {
                     $query->where('mode', $mode);
                 })
                 ->count();
    }

    public function config(Request $request, Configuration $config)
    {
        $method = strtolower($request->method());

        if ($method == 'get') {
            return response()->json(config('plus-group'), 200);
        } else {
            $status = (bool) $request->input('status');
            $verified = (bool) $request->input('need_verified');
            $role = $request->input('report_handle');

            $config->set([
                'plus-group.group_reward.status' => $status,
                'plus-group.group_create.need_verified' => $verified,
                'plus-group.report_handle' => $role,
            ]);

            return response()->json(['message' => '设置成功'], 201);
        }
    }
}
