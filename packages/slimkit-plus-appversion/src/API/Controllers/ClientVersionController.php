<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
        // $version_code = $request->query('version_code', 0);
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
