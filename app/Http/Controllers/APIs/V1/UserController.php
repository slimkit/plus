<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\Like;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\UserDatas;
use Zhiyi\Plus\Models\Conversation;
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

        return response()->json(static::createJsonData([
            'status' => true,
        ]))->setStatusCode(201);
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

        return response()->json(static::createJsonData([
            'code'    => 0,
            'status'  => true,
        ]))->setStatusCode(201);
    }

    /**
     * Show users.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function get(Request $request, User $model)
    {
        $user = $request->user('api')->id ?? 0;
        $ids = is_string($ids = $request->user_ids) ? explode(',', $ids) : $ids;

        $users = $model->with('datas', 'counts')
            ->whereIn('id', (array) $ids)
            ->get();
        $users = $model->getConnection()->transaction(function () use ($user, $users) {
            return $users->map(function (User $item) use ($user) {
                $item->is_following = $item->hasFollwing($user);
                $item->is_followed = $item->hasFollower($user);

                return $item;
            });
        });

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $users,
        ]), 201);
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

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $rank,
        ])->setStatusCode(200);
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
            $query->where('target_user', $uid)->orWhere('reply_user', $uid);
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

        $return = $comment->map(function ($data) {
            return $this->formmatOldDate($data);
        });

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $return,
        ]))->setStatusCode(200);
    }

    // 解析组装数据以兼容v1接口字段返回
    protected function formmatOldDate(Comment $data)
    {
        $arr = [
            'id' => $data->id,
            'user_id' => $data->user_id,
            'to_user_id' => $data->target_user,
            'reply_to_user_id' => $data->reply_user,
            'comment_id' => $data->target,
            'comment_content' => $data->comment_content,
            'source_cover' => $data->target_image,
            'source_content' => $data->target_title,
            'source_id' => $data->target_id,
            'created_at' => $data->created_at->toDateTimeString(),
            'updated_at' => $data->updated_at->toDateTimeString(),
        ];

        switch ($data->channel) {
            case 'feed':
                $arr['component'] = 'feed';
                $arr['comment_table'] = 'feed_comments';
                $arr['source_table'] = 'feeds';
                break;
            case 'music':
                $arr['component'] = 'music';
                $arr['comment_table'] = 'music_comments';
                $arr['source_table'] = 'musics';
                break;
            case 'music_special':
                $arr['component'] = 'music';
                $arr['comment_table'] = 'music_comments';
                $arr['source_table'] = 'music_specials';
                break;
            case 'news':
                $arr['component'] = 'news';
                $arr['comment_table'] = 'news_comments';
                $arr['source_table'] = 'news';
                break;
        }

        return $arr;
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

        $likes = Like::where('target_user', $uid)
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->take($limit)
        ->with('likeable')
        ->orderBy('id', 'desc')
        ->get();

        $digg = $likes->map(function ($like) {
            return $this->checkOldDigg($like);
        });

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $digg,
        ]))->setStatusCode(200);
    }

    protected function checkOldDigg(Like $data)
    {
        $arr = [
            'id' => $data->id,
            'source_id' => $data->likeable->id,
            'source_cover' => $data->likeable->image[0] ? $data->likeable->image[0] : 0,
            'source_content' => $data->likeable->feed_content,
            'user_id' => $data->user_id,
            'to_user_id' => $data->target_user,
            'created_at' => $data->created_at->toDateTimeString(),
            'updated_at' => $data->updated_at->toDateTimeString(),
        ];

        switch ($data->likeable_type) {
            case 'feeds':
                $arr['component'] = 'feed';
                $arr['digg_table'] = 'feed_diggs';
                $arr['source_table'] = 'feeds';
                $arr['digg_id'] = $data->id;
                break;
        }

        return $arr;
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
        $time = $time ? Carbon::createFromTimestamp($time)->toDateTimeString() : 0;
        $return = [];
        if (in_array('diggs', $key)) {
            $diggs = $time ? Like::where('target_user', $uid)->where('user_id', '!=', $uid)->where('created_at', '>', $time)->orderBy('id', 'desc')->get() :
                Like::where('target_user', $uid)->where('user_id', '!=', $uid)->orderBy('id', 'desc')->take(5)->get();

            $digg_return['key'] = 'diggs';
            $digg_return['uids'] = $diggs->pluck('user_id')->toArray();
            $digg_return['count'] = $time ? $diggs->count() : 0;
            $digg_return['time'] = $diggs->count() > 0 ? $diggs->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $digg_return['max_id'] = $diggs->count() > 0 ? $diggs->toArray()[0]['id'] : 0;

            $return[] = $digg_return;
        }
        if (in_array('follows', $key)) {
            $follows = $time ? DB::table('user_follow')->where('target', $uid)->where('created_at', '>', $time)->orderBy('id', 'desc')->get() :
                DB::table('user_follow')->where('target', $uid)->orderBy('id', 'desc')->take(5)->get();
            $follow_return['key'] = 'follows';
            $follow_return['uids'] = $follows->pluck('user_id')->toArray();
            $follow_return['count'] = $time ? $follows->count() : 0;
            $follow_return['time'] = $follows->count() > 0 ? $follows->toArray()[0]->created_at : Carbon::now()->toDateTimeString();
            $follow_return['max_id'] = $follows->count() > 0 ? $follows->toArray()[0]->id : 0;

            $return[] = $follow_return;
        }
        if (in_array('comments', $key)) {
            $comments = $time ? Comment::where(function ($query) use ($uid) {
                $query->where('target_user', $uid)->orWhere('reply_user', $uid);
            })
            ->where('user_id', '!=', $uid)
            ->where('created_at', '>', $time)
            ->orderBy('id', 'desc')
            ->get() :
            Comment::where(function ($query) use ($uid) {
                $query->where('target_user', $uid)->orWhere('reply_user', $uid);
            })
            ->where('user_id', '!=', $uid)
            ->take(5)
            ->orderBy('id', 'desc')
            ->get();

            $comment_return['key'] = 'comments';
            $comment_return['uids'] = $comments->pluck('user_id')->toArray();
            $comment_return['count'] = $time ? $comments->count() : 0;
            $comment_return['time'] = $comments->count() > 0 ? $comments->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $comment_return['max_id'] = $comments->count() > 0 ? $comments->toArray()[0]['id'] : 0;

            $return[] = $comment_return;
        }

        if (in_array('notices', $key)) {
            $notices = $time ? Conversation::where(function ($query) use ($uid) {
                $query->where('type', 'system')->whereIn('to_user_id', [0, $uid]);
            })
            ->where('created_at', '>', $time)
            ->orderBy('id', 'desc')
            ->get() :
            Conversation::where(function ($query) use ($uid) {
                $query->where('type', 'system')->whereIn('to_user_id', [0, $uid]);
            })
            ->take(5)
            ->orderBy('id', 'desc')
            ->get();

            $notice_return['key'] = 'notices';
            $notice_return['uids'] = [];
            $notice_return['count'] = $time ? $notices->count() : 0;
            $notice_return['time'] = $notices->count() > 0 ? $notices->toArray()[0]['created_at'] : Carbon::now()->toDateTimeString();
            $notice_return['max_id'] = $notices->count() > 0 ? $notices->toArray()[0]['id'] : 0;

            $return[] = $notice_return;
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data'    => $return,
        ]))->setStatusCode(200);
    }
}
