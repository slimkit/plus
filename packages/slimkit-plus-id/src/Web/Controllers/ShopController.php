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

namespace SlimKit\PlusID\Web\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SlimKit\PlusID\Models\Client as ClientModel;
use Zhiyi\Plus\Models\User;

class ShopController
{
    public function toShop(Request $request, ClientModel $client)
    {
        $user = $request->user('api');
        $user = $user->makeVisible('phone')->makeVisible('email');

        $params['t'] = Carbon::now()->timestamp;
        $params['pid'] = $user->id;

        // 获取商城用户是否被禁用
        $httpData['headers'] = ['Content-Type' => 'application/json'];
        $httpData['http_errors'] = true;
        $isP = $params;
        ksort($isP);
        $isP['checkCode'] = md5(implode('', $isP).md5($client->key));
        $httpData['body'] = json_encode($isP);
        $httpClient = new \GuzzleHttp\Client();
        $result = $httpClient->request('post', $client->url.'/Home/Api/userCheckByPlus', $httpData);
        $return = (string) ($result->getBody()->getContents());
        $return = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $return), true);
        if ($return['status'] !== 1) {
            return response()->json(['message' => $return['msg']], 403);
        }

        $params['phone'] = $user->phone ?: '';
        $params['email'] = $user->email ?: '';
        $params['username'] = $user->name ?: '';
        $params['sex'] = $user->sex ?: 0;
        $params['avatar'] = $user->avatar ? $user->avatar->url : '';
        ksort($params);
        $params['checkCode'] = md5(implode('', $params).md5($client->key));

        return response()->json(['url' => $client->url, 'params' => $params], 200);
    }
}
