<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatPinneds;

class UserController extends BaseController
{
    /**
     * 找伙伴.
     * @param Request $request
     * @param int|int $type [类型]
     * @return mixed
     * @throws \Throwable
     * @author Foreach
     */
    public function users(Request $request, int $type = 1)
    {
        if ($request->isAjax) {
            $type = $request->query('type');
            $limit = $request->query('limit') ?: 10;
            $offset = $request->query('offset') ?: 0;

            $params = [
                'limit' => $limit,
                'offset' => $offset,
            ];

            if ($type == 1) { // 热门
                $api = '/api/v2/user/populars';
            } elseif ($type == 2) { // 最新
                $api = '/api/v2/user/latests';
            } elseif ($type == 3) { // 推荐
                $api = '/api/v2/user/find-by-tags';
            } else { // 地区
                $params = [
                    'page' => $offset,
                    'limit' => $limit,
                    'latitude' => $request->query('latitude'),
                    'longitude' => $request->query('longitude'),
                ];
                $api = '/api/v2/around-amap';
            }

            $data['users'] = api('GET', $api, $params);
            // 推荐用户 = 后台推荐的用户 + 相同标签用户
            if ($type == 3 && $offset == 0) {
                $recommends = api('GET', '/api/v2/user/recommends', ['offset' => 0]);
                $data['users'] = formatPinneds($data['users'], $recommends, 'id');
            }

            $html = view('pcview::templates.user', $data, $this->PlusData)->render();

            return response()->json([
                'status'  => true,
                'data' => $html,
                'count' => count($data['users']),
            ]);
        }

        $data['type'] = $type;

        $this->PlusData['current'] = 'people';

        return view('pcview::user.users', $data, $this->PlusData);
    }

    /**
     * 地区搜索.
     * @author 28youth
     * @return mixed
     */
    public function area()
    {
        $data['area'] = api('GET', '/api/v2/locations/hots');
        $this->PlusData['current'] = 'users';

        return view('pcview::user.area', $data, $this->PlusData);
    }

    /**
     * 用户粉丝.
     * @author Foreach
     * @param  Request     $request
     * @param  int|int $type    [类型]
     * @param  int|int $user_id [用户id]
     * @return mixed
     */
    public function follower(Request $request, int $user_id = 0)
    {
        if ($request->isAjax) {
            $user_id = $request->query('user_id');

            $params = [
                'offset' => $request->query('offset'),
                'limit' => $request->query('limit'),
            ];

            // 判断是否为自己
            $self = ! empty($this->PlusData['TS']['id']) && $user_id == $this->PlusData['TS']['id'] ? 1 : 0;

            $api = $self ? '/api/v2/user/followers' : '/api/v2'.'/users/'.$user_id.'/followers';

            $users = api('GET', $api, $params);
            $data['users'] = $users;

            $html = view('pcview::templates.user', $data, $this->PlusData)->render();

            return response()->json([
                'status'  => true,
                'data' => $html,
                'count' => count($users),
            ]);
        }

        $data['type'] = 1;
        $data['user_id'] = ! empty($this->PlusData['TS']['id']) && $user_id == 0 ? $this->PlusData['TS']['id'] : $user_id;

        return view('pcview::user.follows', $data, $this->PlusData);
    }

    /**
     * 用户关注.
     * @author Foreach
     * @param  Request     $request
     * @param  int|int $type    [类型]
     * @param  int|int $user_id [用户id]
     * @return mixed
     */
    public function following(Request $request, int $user_id = 0)
    {
        if ($request->isAjax) {
            $user_id = $request->query('user_id');

            $params = [
                'offset' => $request->query('offset'),
                'limit' => $request->query('limit'),
            ];

            // 判断是否为自己
            $self = ! empty($this->PlusData['TS']['id']) && $user_id == $this->PlusData['TS']['id'] ? 1 : 0;

            $api = $self ? '/api/v2/user/followings' : '/api/v2'.'/users/'.$user_id.'/followings';

            $users = api('GET', $api, $params);
            $data['users'] = $users;

            $html = view('pcview::templates.user', $data, $this->PlusData)->render();

            return response()->json([
                'status'  => true,
                'data' => $html,
                'count' => count($users),
            ]);
        }

        $data['type'] = 2;
        $data['user_id'] = ! empty($this->PlusData['TS']['id']) && $user_id == 0 ? $this->PlusData['TS']['id'] : $user_id;

        return view('pcview::user.follows', $data, $this->PlusData);
    }
}
