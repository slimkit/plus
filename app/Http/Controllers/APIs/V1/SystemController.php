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
    // 允许查询的扩展配置
    protected $allowedNamespace = ['im'];

    /**
     * 获取扩展包安装状态
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
     * 获取扩展包配置信息.
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

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $list,
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
        $key = $request->input('key') ?? 'diggs,follows,comments';
        is_string($key) && $key = explode(',', $key);
        $time = $request->input('time');
        $time = Carbon::createFromTimestamp($time)->toDateTimeString();
        $return = [];
        if (in_array('diggs', $key)) {
            $diggs = Digg::where('to_user_id', $uid)->where('created_at', '>', $time)->orderBy('id', 'desc')->get();

            $digg_return['key'] = 'diggs';
            $digg_return['uids'] = implode(',', $diggs->pluck('user_id')->toArray());
            $digg_return['count'] = $diggs->count();
            $digg_return['time'] = $diggs->count() > 0 ? $diggs->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $digg_return['max_id'] = $diggs->count() > 0 ? $diggs->toArray()[0]['id'] : 0;

            $return[] = $digg_return;
        }
        if (in_array('follows', $key)) {
            $follows = Following::where('following_user_id', $uid)->where('created_at', '>', $time)->orderBy('id', 'desc')->get();

            $follow_return['key'] = 'follows';
            $follow_return['uids'] = implode(',', $follows->pluck('user_id')->toArray());
            $follow_return['count'] = $follows->count();
            $follow_return['time'] = $follows->count() > 0 ? $follows->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $follow_return['max_id'] = $follows->count() > 0 ? $follows->toArray()[0]['id'] : 0;

            $return[] = $follow_return;
        }
        if (in_array('comments', $key)) {
            $comments = Comment::where(function ($query) use ($uid) {
                $query->where('to_user_id', $uid)->orWhere('reply_to_user_id', $uid);
            })
            ->where('user_id', '!=', $uid)
            ->where('created_at', '>', $time)
            ->orderBy('id', 'desc')
            ->get();

            $comment_return['key'] = 'comments';
            $comment_return['uids'] = implode(',', $comments->pluck('user_id')->toArray());
            $comment_return['count'] = $comments->count();
            $comment_return['time'] = $comments->count() > 0 ? $comments->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $comment_return['max_id'] = $comments->count() > 0 ? $comments->toArray()[0]['id'] : 0;

            $return[] = $comment_return;
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $return,
        ]))->setStatusCode(200);
    }
}
