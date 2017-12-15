<?php

namespace SlimKit\PlusID\Actions\User;

use SlimKit\PlusID\Actions\Action;
use SlimKit\PlusID\Support\Message;
use Zhiyi\Plus\Models\User as UserModel;

class Delete extends Action
{
    public function getSignAction(): array
    {
        return [
            'app' => $this->client->id,
            'action' => 'user/delete',
            'time' => (int) $this->request->time,
            'user' => (int) $this->request->user,
        ];
    }

    public function dispatch()
    {
        UserModel::where('id', $this->request->user)->delete();

        return $this->response(new Message(200, 'success'));
    }
}
