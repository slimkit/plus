<?php

namespace SlimKit\PlusID\Actions\Auth;

use SlimKit\PlusID\Actions\Action;
use SlimKit\PlusID\Support\Message;

class Resolve extends Action
{
    protected function getSignAction(): array
    {
        return [
            'action' => 'auth/resolve',
            'app' => $this->client->id,
            'time' => (int) $this->request->time,
        ];
    }

    public function check()
    {
        if (($response = parent::check()) !== true) {
            return $response;
        }

        if ($this->request->user('web') === null) {
            return $this->response(new Message(10002, 'fail'));
        }

        return true;
    }

    public function dispatch()
    {
        $action = [
            'app' => $this->client->id,
            'action' => 'auth/resolve',
            'user' => $user = $this->request->user('web')->id,
            'time' => $time = time(),
        ];

        return $this->response(new Message(200, 'success', [
            'sign' => $this->server->sign($action),
            'user' => $user,
            'time' => $time,
        ]));
    }
}
