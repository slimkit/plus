<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\Digg;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\UserDatas;
use Zhiyi\Plus\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\UserProfileSetting;
use Zhiyi\Plus\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * 修改用户密码.
     *
     * @param Request $request 请求对象
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function resetPassword(Request $request)
    {
        $password = $request->input('new_password', '');
        $user = $request->user();
        $user->createPassword($password);
        $user->save();

        return response()->json()->setStatusCode(201);
    }

    /**
     * 修改用户资料.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-17T17:25:45+0800
     *
     * @param Request $request [description]
     *
     * @return mixed 返回结果
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $profileData = $request->all();
        $profileSettings = UserProfileSetting::whereIn('profile', array_keys($profileData))->get();
        $datas = [];
        foreach ($profileSettings as $profile) {
            $datas[$profile->id] = $request->input($profile->profile) ?? '';
        }
        $user->syncData($datas);

        return response()->json()->setStatusCode(201);
    }

    /**
     * [get description].
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-18T17:54:59+0800
     *
     * @param User $user [description]
     *
     * @return [type] [description]
     */
    public function getMultiUserInfo(Request $request)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        $uids = explode(',', $request->query('user'));
        $datas = User::whereIn('id', $uids)
            ->with('datas', 'counts')
            ->get();
        if ($datas->isEmpty()) {
            return response()->json([
                'user' => '没有相关用户',
            ])->setStatusCode(404);
        }
        $datas->each(function ($data) use ($uid) {
            $data->is_followed = $data->follows()->where('following_user_id', $uid)->count() > 0 ? 1 : 0;
            $data->is_following = $data->followeds()->where('followed_user_id', $uid)->count() > 0 ? 1 : 0;
        });

        return response()->json($datas)->setStatusCode(200);
    }

    /**
     * get single user info.
     *
     * @author bs<414606094@qq.com>
     *
     * @param  User    $user    [description]
     */
    public function getSingleUserInfo(User $user)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        $data = $user->load('datas', 'counts');
        $data->is_followed = $data->follows()->where('following_user_id', $uid)->count() > 0 ? 1 : 0;
        $data->is_following = $data->followeds()->where('followed_user_id', $uid)->count() > 0 ? 1 : 0;

        return response()->json($data)->setStatusCode(200);
    }

    /**
     * 点赞排行.
     *
     * @author bs<414606094@qq.com>
     *
     * @return [type] [description]
     */
    public function diggsRank(Request $request)
    {
        $limit = $request->input('limit', 15);
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $limit;
        $rank = UserDatas::where('key', 'diggs_count')
        ->select('id', 'user_id', 'value')
        ->orderBy(DB::raw('-value', 'desc'))
        ->skip($skip)
        ->take($limit)
        ->get();

        return response()->json($rank)->setStatusCode(200);
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
        ->where('user_id', '!=', $uid)
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->take($limit)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json($comment)->setStatusCode(200);
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

        return response()->json($digg)->setStatusCode(200);
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
        $key = $request->input('key') ?? 'diggs,follows,comments,notices';
        is_string($key) && $key = explode(',', $key);
        $time = $request->input('time');
        $time = Carbon::createFromTimestamp($time)->toDateTimeString();
        $return = [];
        if (in_array('diggs', $key)) {
            $diggs = Digg::where('to_user_id', $uid)->where('user_id', '!=', $uid)->where('created_at', '>', $time)->orderBy('id', 'desc')->get();

            $digg_return['key'] = 'diggs';
            $digg_return['uids'] = $diggs->pluck('user_id')->toArray();
            $digg_return['count'] = $diggs->count();
            $digg_return['time'] = $diggs->count() > 0 ? $diggs->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $digg_return['max_id'] = $diggs->count() > 0 ? $diggs->toArray()[0]['id'] : 0;

            $return[] = $digg_return;
        }
        if (in_array('follows', $key)) {
            $follows = Following::where('following_user_id', $uid)->where('created_at', '>', $time)->orderBy('id', 'desc')->get();

            $follow_return['key'] = 'follows';
            $follow_return['uids'] = $follows->pluck('user_id')->toArray();
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
            $comment_return['uids'] = $comments->pluck('user_id')->toArray();
            $comment_return['count'] = $comments->count();
            $comment_return['time'] = $comments->count() > 0 ? $comments->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $comment_return['max_id'] = $comments->count() > 0 ? $comments->toArray()[0]['id'] : 0;

            $return[] = $comment_return;
        }

        if (in_array('notices', $key)) {
            $notices = Conversation::where(function ($query) use ($uid) {
                $query->where('type', 'system')->whereIn('to_user_id', [0, $uid]);
            })
            ->where('created_at', '>', $time)
            ->orderBy('id', 'desc')
            ->get();

            $notice_return['key'] = 'notices';
            $notice_return['uids'] = [];
            $notice_return['count'] = $notices->count();
            $notice_return['time'] = $notices->count() > 0 ? $notices->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $notice_return['max_id'] = $notices->count() > 0 ? $notices->toArray()[0]['id'] : 0;

            $return[] = $notice_return;
        }

        return response()->json($return)->setStatusCode(200);
    }
}
