<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Conversation;

class SystemController extends Controller
{
    public function getImServerConfig()
    {
        $data['url'] = '192.168.2.222';
        $data['port'] = '9900';

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $data,
        ])->setStatusCode(200);
    }

    public function createFeedback(Request $request)
    {
        $feedback = new Conversation();
        $feedback->type = 'feedback';
        $feedback->content = $request->input('content');
        $feedback->user_id = $request->user()->id;
        $feedback->save();

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '反馈成功',
        ]))->setStatusCode(201);
    }
}
