<?php

namespace SlimKit\PlusID;

class Action
{
    protected $actions = [
        'auth/resolve' => \SlimKit\PlusID\Actions\Auth\Resolve::class,
        'auth/login' => \SlimKit\PlusID\Actions\Auth\Login::class,
        'user/check' => \SlimKit\PlusID\Actions\User\Check::class,
        'user/create' => \SlimKit\PlusID\Actions\User\Create::class,
        'user/delete' => \SlimKit\PlusID\Actions\User\Delete::class,
        'user/show' => \SlimKit\PlusID\Actions\User\Show::class,
        'user/update' => \SlimKit\PlusID\Actions\User\Update::class,
    ];

    public function action(string $action)
    {
        if (isset($this->actions[$action])) {
            return new $this->actions[$action];
        }

        abort(404, '不存在的 API.');
    }
}
