<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
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
        $type = $request->query('type');
        $limit = $request->query('limit', 15);
        $after = $request->query('after');

        $versions = $versionModel->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->where('type', $type)
        ->orderBy('id', 'desc')
        ->limit($limit)
        ->get();

        return response()->json($versions, 200);
    }
}
