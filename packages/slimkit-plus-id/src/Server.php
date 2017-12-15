<?php

namespace SlimKit\PlusID;

use Illuminate\Http\Request;
use SlimKit\PlusID\Models\Client as ClientModel;

class Server
{
    protected $client;
    protected $action;

    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    public function setClient(ClientModel $client)
    {
        $this->client = $client;

        return $this;
    }

    public function dispatch(Request $request, string $action)
    {
        $action = $this->action->action($action)
            ->setRequest($request)
            ->setServer($this)
            ->setClient($this->client);

        if (($response = $action->check()) !== true) {
            return $response;
        }

        return $action->dispatch();
    }

    public function sign(array $action): string
    {
        return $this->client->sign($action);
    }
}
