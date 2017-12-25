<?php

namespace Slimkit\PlusAppversion\API\Controllers;

use Illuminate\Http\Request;
use Slimkit\PlusAppversion\Models\ClientVersion;

class ClientVersionController
{
    /**
     * get the list of client versions.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  ClientVersion $versionModel
     * @return mixed
     */
    public function index(Request $request, ClientVersion $versionModel)
    {
        $version_code = $request->query('version_code', 0);
        $type = $request->query('type');
        $limit = $request->query('limit', 15);
        $after = $request->query('after');

        $versions = $versionModel->when($after, function ($query) use ($after) {
            return $query->where('id', '>', $after);
        })
        ->where('type', $type)
        ->orderBy('version_code', 'desc')
        ->limit($limit)
        ->get();

        return response()->json($versions, 200);
    }
}
