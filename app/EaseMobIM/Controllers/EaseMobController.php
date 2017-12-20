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

namespace Zhiyi\Plus\EaseMobIm;

use GuzzleHttp\Client;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;

class EaseMobController
{
    // Client ID
    protected $client_id;

    // Client Secret
    protected $client_secret;

    // OrgName
    protected $org_name;

    // AppName
    protected $app_name;

    // 环信请求地址
    protected $url;

    // 环信注册类型  0-开放注册，1-授权注册
    protected $register_type;

    // 环信是否开启
    protected $open = false;

    private function getConfig($callback)
    {
        $this->open = config('easemob.open');
        $this->client_id = config('easemob.client_id');
        $this->client_secret = config('easemob.client_secret');

        if (! $this->open || ! $this->client_id || ! $this->client_secret) {
            return response()->json([
                'message' => ['环信未配置'],
            ])->setStatusCode(201);
        }
        $this->open = config('easemob.open');
        $this->client_id = config('easemob.client_id');
        $this->client_secret = config('easemob.client_secret');
        // 应用标识
        $app_key = explode('#', config('easemob.app_key'));
        $this->org_name = $app_key[0];
        $this->app_name = $app_key[1];
        $this->register_type = config('easemob.register_type');
        if (! empty($this->org_name) && ! empty($this->app_name)) {
            $this->url = 'https://a1.easemob.com/'.$this->org_name.'/'.$this->app_name.'/';
        }

        return $callback();
    }

    public function getToken()
    {
        $options = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ];
        $url = $this->url.'token';
        $data['body'] = json_encode($options);

        $Client = new Client();
        $tokenResult = $Client->request('post', $url, $data);
        $token = json_decode($tokenResult->getBody()->getContents());

        return 'Bearer '.$token->access_token;
    }

    /**
     * 开放注册模式.
     *
     * @param Request $request
     * @return string
     * @author ZsyD<1251992018@qq.com>
     */
    public function openRegister(Request $request)
    {
        $callback = function () use ($request) {
            $options['username'] = $request->user_id;
            $options['password'] = $this->getImPwdHash($request->user_id);
            $url = $this->url.'users';

            $data['headers'] = ['Content-Type' => 'application/json'];
            $data['body'] = json_encode($options);
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('post', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            return response()->json([])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 获取用户密码.
     *
     * @param $user_id
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function getImPwdHash($user_id)
    {
        $user = User::where('id', $user_id)->select('password')->first();

        return $user->getImPwdHash();
    }

    /**
     * 授权注册.
     *
     * @param Request $request
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function createUser(Request $request)
    {
        $callback = function () use ($request) {
            if ($this->register_type == 0) {
                return $this->openRegister($request);
            }

            $url = $this->url.'users';
            $options = [
                'username' => $request->user_id,
                'password' => $this->getImPwdHash($request->user_id),
            ];
            $data['body'] = json_encode($options);
            $data['headers'] = [$this->getToken()];
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('post', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            return response()->json([])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 批量注册用户.
     *
     * @param Request $request
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function createUsers(Request $request)
    {
        $callback = function () use ($request) {
            $user_ids = $request->input('user_ids');
            $user_ids = is_array($user_ids) ? $user_ids : explode(',', $user_ids);
            $options = [];
            $users = User::when($user_ids, function ($query) use ($user_ids) {
                return $query->whereIn('id', $user_ids);
            })
                ->select('id', 'password')
                ->get();

            foreach ($users as $user) {
                $options[] = [
                    'username' => $user->id,
                    'password' => $user->getImPwdHash(),
                ];
            }
            $url = $this->url.'users';
            $data['body'] = json_encode($options);
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;

            $Client = new Client();
            $result = $Client->request('post', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            return response()->json([])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 重置环信密码.
     *
     * @param Request $request
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function resetPassword(Request $request)
    {
        $callback = function () use ($request) {
            $url = $this->url.'users/'.$request->user_id.'/password';

            $options = [
                'oldpassword' => $request->old_pwd_hash,
                'newpassword' => $this->getImPwdHash($request->user_id),
            ];
            $data['body'] = json_encode($options);
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;

            $Client = new Client();
            $result = $Client->request('put', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            return response()->json([])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 获取环信用户信息，无则新注册一个.
     *
     * @param Request $request
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function getPassword(Request $request)
    {
        $callback = function () use ($request) {
            $url = $this->url.'users/'.$request->user_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;

            $Client = new Client();
            $result = $Client->request('get', $url, $data);

            if ($result->getStatusCode() == 404) {
                $result = $this->createUser($request);
            }

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            return response()->json([
                'message' => ['成功'],
                'im_pwd_hash' => $this->getImPwdHash($request->user_id),
            ])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 删除单个用户.
     *
     * @param Request $request
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function deleteUser(Request $request)
    {
        $callback = function () use ($request) {
            $url = $this->url.'users/'.$request->user_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;

            $Client = new Client();
            $result = $Client->request('get', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            return response()->json([])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }
}
