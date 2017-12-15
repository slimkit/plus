<?php

namespace SlimKit\PlusID\Actions\Auth;

use SlimKit\PlusID\Actions\Action;
use SlimKit\PlusID\Support\Message;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\User as UserModel;

class Login extends Action
{
    public function getSignAction(): array
    {
        return [
            'app' => $this->client->id,
            'action' => 'auth/login',
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

        return true;
    }

    public function dispatch()
    {
        Auth::loginUsingId($this->request->user, true);

        return $this->response(new Message(200, 'success'));
    }
}
