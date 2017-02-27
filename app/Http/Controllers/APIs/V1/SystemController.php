<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Zhiyi\Plus\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function getImServerConfig()
    {
        $data['url'] = 'ws://192.168.2.222:9900';

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $data,
        ])->setStatusCode(200);
    }
}
