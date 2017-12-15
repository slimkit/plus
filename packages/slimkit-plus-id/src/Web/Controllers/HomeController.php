<?php

namespace SlimKit\PlusID\Web\Controllers;

use SlimKit\PlusID\Server;
use Illuminate\Http\Request;
use SlimKit\PlusID\Models\Client as ClientModel;

class HomeController
{
    public function index(Request $request, Server $server, ClientModel $client)
    {
        $action = $request->action;

        return $server->setClient($client)
            ->dispatch($request, $action);
    }
}
