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

namespace SlimKit\PlusID\Actions\User;

use SlimKit\PlusID\Actions\Action;
use SlimKit\PlusID\Support\Message;
use Validator;
use Zhiyi\Plus\Models\User as UserModel;

class Update extends Action
{
    public function getSignAction(): array
    {
        return [
            'app' => $this->client->id,
            'action' => 'user/update',
            'time' => (int) $this->request->time,
            'user' => (int) $this->request->user,
        ];
    }

    public function check()
    {
        if (($response = parent::check()) !== true) {
            return $response;
        } elseif (! UserModel::find($this->request->user)) {
            return $this->response(new Message(10003, 'fail'));
        }

        $validator = Validator::make($this->request->all(), [
            'name' => 'nullable|string|username|display_length:2,12|unique:users,name',
            'phone' => 'nullable|cn_phone|unique:users,phone',
            'email' => 'nullable|email|max:128|unique:users,email',
        ]);

        if (! $validator->fails()) {
            return true;
        }

        return $this->response(new Message(422, 'fail', $validator->errors()->toArray()));
    }

    public function dispatch()
    {
        $map = $this->request->only(['phone', 'name', 'email']);
        $user = UserModel::find($this->request->user);

        foreach ($map as $key => $value) {
            if ($value) {
                $user->$key = $value;
            }
        }

        $user->save();

        return $this->response(new Message(200, 'success'));
    }
}
