<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\Conversation;
use Zhiyi\Plus\Http\Controllers\Controller;

class SystemController extends Controller
{
    // 允许查询的扩展配置
    protected $allowedNamespace = ['im'];

    /**
     * 获取扩展�
     * 安�
     * 状态
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function getComponentStatus()
    {
        $config = CommonConfig::select('namespace')->whereIn('namespace', $this->allowedNamespace)->groupBy('namespace')->pluck('namespace')->toArray();

        $status = [
            'im' => in_array('im', $config),
        ];

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $status,
        ]))->setStatusCode(200);
    }

    /**
     * 获取扩展�
     * �
     * �置信息.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getComponentConfig(Request $request)
    {
        $configData = CommonConfig::where('namespace', $request->component)->whereIn('namespace', $this->allowedNamespace)->select(['name', 'value'])->get();

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
        $feedback->system_mark = $request->input('system_mark', (Carbon::now()->timestamp) * 1000);
        $feedback->save();

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '反馈成功',
            'data' => $feedback->id,
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
        $order = $request->input('order', 0);
        $list = Conversation::where(function ($query) use ($uid) {
            $query->where(function ($query) use ($uid) {
                $query->where('type', 'system')->whereIn('to_user_id', [0, $uid]);
            })->orWhere(['type' => 'feedback', 'user_id' => $uid]);
        })
        ->where(function ($query) use ($max_id, $order) {
            if ($max_id > 0) {
                $query->where('id', $order ? '>' : '<', $max_id);
            }
        })
        ->orderBy('id', 'desc')
        ->take($limit)
        ->get();

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $list,
        ]))->setStatusCode(200);
    }

    /**
     * �
     * �于我们.
     *
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function about()
    {
        return view('about');
    }
}
