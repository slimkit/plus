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

namespace SlimKit\PlusID\Actions\User;

use Validator;
use SlimKit\PlusID\Actions\Action;
use SlimKit\PlusID\Support\Message;
use Zhiyi\Plus\Models\User as UserModel;

class Create extends Action
{
    public function getSignAction(): array
    {
        return [
            'app' => $this->client->id,
            'action' => 'user/create',
            'time' => (int) $this->request->time,
        ];
    }

    public function check()
    {
        if (($response = parent::check()) !== true) {
            return $response;
        }

        $validator = Validator::make($this->request->all(), [
            'name' => 'required|string|username|display_length:2,12|unique:users,name',
            'phone' => 'nullable|cn_phone|unique:users,phone',
            'email' => 'nullable|email|max:128|unique:users,email',
            'password' => 'required|string',
        ]);

        if (! $validator->fails()) {
            return true;
        }

        return $this->response(new Message(422, 'fail', $validator->errors()->toArray()));
    }

    public function dispatch()
    {
        $user = new UserModel();
        $user->name = $this->request->name;
        $user->createPassword($this->request->password);
        $user->phone = $this->request->phone;
        $user->email = $this->request->email;
        $user->save();

        return $this->response(new Message(200, 'success', ['user' => $user->id]));
    }
}
