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

    protected function getConfig($callback)
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
            $user_id = $request->user_id ?: $request->user()->id;
            $options['username'] = $user_id;
            $options['password'] = $this->getImPwdHash($user_id);
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
            $user_id = $request->user_id ?: $request->user()->id;
            $url = $this->url.'users';
            $options = [
                'username' => $user_id,
                'password' => $this->getImPwdHash($user_id),
            ];
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
     * 批量注册用户.
     *
     * @param Request $request
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function createUsers(Request $request)
    {
        $callback = function () use ($request) {
            $user_ids = $request->user_ids ?: $request->input('user_ids');
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
            // 判断用户是否注册过环信，兼容未注册用户
            $user_id = $request->user_id ?: $request->user()->id;
            $url = $this->url.'users/'.$user_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;

            $Client = new Client();
            $result = $Client->request('get', $url, $data);

            if ($result->getStatusCode() == 404) {
                // 用户不存在时去注册环信用户
                $result_ = $this->createUser($request);

                if ($result_->getStatusCode() != 201) {
                    return response()->json([
                        'message' => ['未注册成功'],
                    ])->setStatusCode(500);
                }
            } elseif ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            } else {
                // 用户存在时，重置环信密码
                $user_id = $request->user_id ?: $request->user()->id;
                $url = $this->url.'users/'.$user_id.'/password';
                $options = [
                    'oldpassword' => $request->old_pwd_hash,
                    'newpassword' => $this->getImPwdHash($user_id),
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
            $user_id = $request->user_id ?: $request->user()->id;
            $url = $this->url.'users/'.$user_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;

            $Client = new Client();
            $result = $Client->request('get', $url, $data);

            if ($result->getStatusCode() == 404) {
                $result_ = $this->createUser($request);
                if ($result_->getStatusCode() != 201) {
                    return response()->json([
                        'message' => ['注册失败'],
                    ])->setStatusCode(500);
                }
            } elseif ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            return response()->json([
                'message' => ['成功'],
                'im_pwd_hash' => $this->getImPwdHash($user_id),
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
            $user_id = $request->user_id ?: $request->user()->id;
            $url = $this->url.'users/'.$user_id;
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

    /**
     * 发送普通消息 [群组相关时需要].
     *
     * @param string $content       消息内容
     * @param array  $target        消息发送对象
     * @param string $from          消息发送者
     * @param string $target_type   users 给用户发消息。chatgroups: 给群发消息，chatrooms: 给聊天室发消息
     * @param array  $ext           扩展信息
     * @return bool
     * @author ZsyD<1251992018@qq.com>
     */
    public function sendCmd(string $content = '', array $target = [], string $from = 'admin', string $target_type = 'chatgroups', array $ext = [])
    {
        $url = $this->url.'messages';
        $data['headers'] = [
            'Authorization' => $this->getToken(),
        ];
        $data['http_errors'] = false;
        $option = [
            'target_type' => $target_type,
            'target' => $target,
            'msg' => [
                'type' => 'txt',
                'msg' => $content,
            ],
            'from' => $from,
            'ext' => (object) $ext,
        ];
        $data['body'] = json_encode($option);
        $Client = new Client();
        $result = $Client->request('post', $url, $data);

        if ($result->getStatusCode() != 200) {
            return false;
        }

        return true;
    }

    /**
     * 为未注册环信用户注册环信（兼容老用户）.
     *
     * @author ZsyD<1251992018@qq.com>
     * @param Request $request
     * @return mixed
     */
    public function registerOldUsers(Request $request)
    {
        $callback = function () use ($request) {
            $url = $this->url.'users?limit=100000';
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;

            $Client = new Client();
            $result = $Client->request('get', $url, $data);
            $reCon = json_decode($result->getBody()->getContents());

            if ($result->getStatusCode() != 200) {
                return response()->json([
                    'message' => [
                        $reCon->error_description,
                    ],
                ])->setStatusCode(500);
            }

            $registered = collect($reCon->entities)->pluck('username')->filter();
            $ids = User::whereNull('deleted_at')->pluck('id');
            $unregistered = $ids->diff($registered);

            if (! $unregistered->isEmpty()) {
                if ($unregistered->count() > 20) {
                    $unregistered_chuck = $unregistered->chunk(20);
                    foreach ($unregistered_chuck as $v) {
                        $request->user_ids = $v->toArray();
                        $result_ = $this->createUsers($request);

                        if ($result_->getStatusCode() != 201) {
                            return response()->json([
                                'message' => [json_decode($result_->getContent())->message],
                                'unregistered' => $unregistered,
                            ])->setStatusCode(500);
                        }
                        sleep(10);
                    }
                } else {
                    $request->user_ids = $unregistered->toArray();
                    $result_ = $this->createUsers($request);

                    if ($result_->getStatusCode() != 201) {
                        return response()->json([
                            'message' => [json_decode($result_->getContent())->message],
                            'unregistered' => $unregistered,
                        ])->setStatusCode(500);
                    }
                }
            }

            return response()->json([
                'message' => ['注册成功'],
            ])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    public function getMessage()
    {
        $callback = function () {
            $date = (int) date('YmdH');
            $time = (string) ($date % 100 == 0 ? ($date - 77) : ($date - 1));
            $time = '2017122613';
            $url = $this->url.'chatmessages/'.$time;

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
            $url = json_decode($result->getBody()->getContents())->data[0]->url;

            return response()->json([
                'message' => ['获取成功'],
                'url' => $url,
            ])->setStatusCode(500);
        };

        return $this->getConfig($callback);
    }
}
