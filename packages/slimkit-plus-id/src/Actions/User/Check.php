<?php

namespace SlimKit\PlusID\Actions\User;

use SlimKit\PlusID\Actions\Action;
use SlimKit\PlusID\Support\Message;
use Zhiyi\Plus\Models\User as UserModel;

class Check extends Action
{
    public function getSignAction(): array
    {
        return [
            'app' => $this->client->id,
            'action' => 'user/check',
            'time' => (int) $this->request->time,
        ];
    }

    public function dispatch()
    {
        $map = $this->request->only(['phone', 'name', 'email']);
        $map['id'] = $this->request->user;
        foreach ($map as $key => &$value) {
            if (! $value) {
                $value = null;
                continue;
            }

            $value = (bool) UserModel::where($key, $value)->first();
        }

        return $this->response(new Message(200, 'success', $map));
    }
}
