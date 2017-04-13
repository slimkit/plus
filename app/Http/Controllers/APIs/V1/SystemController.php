<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Digg;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\Following;
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

    /**
     * 获取我收到的所有评论.
     *
     * @author bs<414606094@qq.com>
     *
     * @param  Request $request [description]
     *
     * @return [type]           [description]
     */
    public function getMyComments(Request $request)
    {
        $uid = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = $request->input('max_id', 0);
        $comment = Comment::where(function ($query) use ($uid) {
            $query->where('to_user_id', $uid)->orWhere('reply_to_user_id', $uid);
        })
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->take($limit)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $comment,
        ]))->setStatusCode(200);
    }

    /**
     * 获取我收到的所有点赞.
     *
     * @author bs<414606094@qq.com>
     *
     * @param  Request $request [description]
     *
     * @return [type]           [description]
     */
    public function getMyDiggs(Request $request)
    {
        $uid = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = $request->input('max_id', 0);
        $digg = Digg::where('to_user_id', $uid)
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->take($limit)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $digg,
        ]))->setStatusCode(200);
    }

    /**
     * 获取最新消息.
     *
     * @author bs<414606094@qq.com>
     *
     * @param  Request $request [description]
     *
     * @return [type]           [description]
     */
    public function flushMessages(Request $request)
    {
        $uid = $request->user()->id;
        $key = $request->input('key', ['diggs', 'follows', 'comments']);
        is_string($key) && $key = explode(',', $key);
        $time = $request->input('time');
        $time = Carbon::createFromTimestamp($time)->toDateTimeString();
        $return = [];
        if (in_array('diggs', $key)) {
            $diggs = Digg::where('to_user_id', $uid)->where('created_at', '>', $time)->pluck('user_id');

            $return['diggs']['uids'] = implode(',', $diggs->toArray());
            $return['diggs']['count'] = $diggs->count();
        }
        if (in_array('follows', $key)) {
            $follows = Following::where('following_user_id', $uid)->where('created_at', '>', $time)->pluck('user_id');

            $return['follows']['uids'] = implode(',', $follows->toArray());
            $return['follows']['count'] = $follows->count();
        }
        if (in_array('follows', $key)) {
            $comments = Comment::where(function ($query) use ($uid) {
                $query->where('to_user_id', $uid)->orWhere('reply_to_user_id', $uid);
            })
            ->where('user_id', '!=', $uid)
            ->where('created_at', '>', $time)
            ->pluck('user_id');

            $return['comments']['uids'] = implode(',', $comments->toArray());
            $return['comments']['count'] = $comments->count();
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $return,
        ]))->setStatusCode(200);
    }
}
