<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\Conversation;
use Zhiyi\Plus\Http\Controllers\Controller;

class SystemController extends Controller
{
    /**
     * 获取扩展包安装状态
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function getComponentStatus()
    {
        $config = CommonConfig::select('namespace')->groupBy('namespace')->pluck('namespace')->toArray();

        $status = [
            'im' => in_array('im', $config),
        ];

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $status,
        ]))->setStatusCode(200);
    }

    /**
     * 获取扩展包配置信息.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getComponentConfig(Request $request)
    {
        $configData = CommonConfig::where('namespace', $request->component)->select(['name', 'value'])->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $configData,
        ]))->setStatusCode(200);
    }

    /**
     * 用户反馈.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
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
        if (! $list->isEmpty()) {
            $datas = $list->toArray();
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }
}
