<?php

namespace SlimKit\PlusID\Admin\Controllers;

use SlimKit\PlusID\Models\Client as ClientModel;
use SlimKit\PlusID\Admin\Requests\CreateClientRequest;
use SlimKit\PlusID\Admin\Requests\UpdateClientRequest;

class ClientsController
{
    /**
     * Get all clients.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index()
    {
        return response()->json(ClientModel::all(), 200);
    }

    /**
     * Store a client.
     *
     * @param \SlimKit\PlusID\Admin\Requests\CreateClientRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(CreateClientRequest $request)
    {
        $name = $request->input('name');
        $url = $request->input('url');
        $key = $request->input('key');
        $syncLogin = (bool) $request->input('sync_login', false);

        $client = new ClientModel();
        $client->name = $name;
        $client->url = $url;
        $client->key = $key;
        $client->sync_login = $syncLogin;

        $client->save();

        return response()->json($client, 201);
    }

    /**
     * Destory a client.
     *
     * @param \SlimKit\PlusID\Models\Client $client
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(ClientModel $client)
    {
        $client->delete();

        return response('', 204);
    }

    /**
     * Update a client.
     *
     * @param \SlimKit\PlusID\Admin\Requests\UpdateClientRequest $request
     * @param \SlimKit\PlusID\Models\Client $client
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(UpdateClientRequest $request, ClientModel $client)
    {
        $name = $request->input('name');
        $url = $request->input('url');
        $key = $request->input('key');

        $client->name = $name;
        $client->url = $url;
        $client->key = $key;
        $client->save();

        return response('', 204);
    }
}
