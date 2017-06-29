<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class OAuthApiClientController extends Controller
{
    /**
     * Get API client.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ApplicationContract $app, ResponseContract $response)
    {
        return $response->json([
            'id' => $app->config->get('auth.api-client.id'),
            'secret' => $app->config->get('auth.api-client.secret'),
        ])->setStatusCode(200);
    }

    /**
     * Update API client.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Support\Configuration $store
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, ResponseContract $response, Configuration $store)
    {
        $client = $request->only(['id', 'secret']);
        $store->set('auth.api-client', $client);

        return $response->json(['message' => ['设置成功']])->setStatusCode(201);
    }
}
