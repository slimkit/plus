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

    /**
     * 获取用户的系统会话列表.
     *
     * @author bs<414606094@qq.com>
     *
     * @return [type] [description]
     */
    public function getConversations(Request $request)
    {
        $uid = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = $request->input('max_id', 0);
        $list = Conversation::where(function ($query) use ($uid) {
            $query->where(function ($query) use ($uid) {
                $query->where('type', 'system')->whereIn('to_user_id', [0, $uid]);
            })->orWhere(['type' => 'feedback', 'user_id' => 1]);
        })
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->orderBy('created_at', 'desc')
        ->take($limit)
        ->get();
        $datas = [];
        if (!$list->isEmpty()) {
            $datas = $list->toArray();
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }
}
