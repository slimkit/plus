<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use DB;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Jobs\PushMessage;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\UserDatas;
use Zhiyi\Plus\Http\Controllers\Controller;

class FollowController extends Controller
{
    /**
     * 关注用户.
     *
     * @param  $user_id [被关注者ID]
     *
     * @return [type] [description]
     */
    public function doFollow(Request $request)
    {
        $user_id = $request->user()->id;
        $follow_user_id = $request->user_id;

        $this->checkUserFollowData($follow_user_id, 'followed_count');
        $this->checkUserFollowData($user_id, 'following_count');

        $follow = new Following();
        $follow->user_id = $user_id;
        $follow->following_user_id = $follow_user_id;

        DB::beginTransaction();

        $add_following = $follow->save();
        $add_followed = $follow->syncFollowed();
        $following_count = UserDatas::byKey('following_count')->byUserId($user_id)->increment('value');
        $followed_count = UserDatas::byKey('followed_count')->byUserId($follow_user_id)->increment('value');

        if ($add_following && $add_followed && $following_count && $followed_count) {
            DB::commit();

            $extras = ['action' => 'follow', 'type' => 'user', 'uid' => $user_id];
            $alert = '有人关注了你，去看看吧';
            $alias = $follow_user_id;

            dispatch(new PushMessage($alert, (string) $alias, $extras));

            return response()->json(static::createJsonData([
                'status'  => true,
                'code'    => 0,
                'message' => '成功关注',
            ]))->setStatusCode(201);
        }

        DB::rollBack();

        return response()->json(static::createJsonData([
            'status'  => false,
            'code'    => 1024,
            'message' => '操作失败，请稍后重试',
        ]))->setStatusCode(400);
    }

    /**
     * 取消关注.
     *
     * @param [integer] $user_id [被取消关注的用户ID]
     *
     * @return [type] [description]
     */
    public function doUnFollow(Request $request)
    {
        $user_id = $request->user()->id;
        $follow_user_id = $request->user_id;

        DB::beginTransaction();

        $delete_followed = Followed::where(['user_id' => $follow_user_id, 'followed_user_id' => $user_id])->delete();
        $delete_following = Following::where(['user_id' => $user_id, 'following_user_id' => $follow_user_id])->delete();
        $following_count = UserDatas::byKey('following_count')->byUserId($user_id)->decrement('value');
        $followed_count = UserDatas::byKey('followed_count')->byUserId($follow_user_id)->decrement('value');

        if ($delete_following && $delete_followed && $following_count && $followed_count) {
            DB::commit();

            return response()->json(static::createJsonData([
                'status'  => true,
                'code'    => 0,
                'message' => '成功取关',
            ]))->setStatusCode(204);
        }

        DB::rollBack();

        return response()->json(static::createJsonData([
            'status'  => false,
            'code'    => 1024,
            'message' => '操作失败，请稍后重试',
        ]))->setStatusCode(400);
    }

    /**
     * 关注的用户.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function follows(Request $request, int $user_id, int $max_id = 0)
    {
        $limit = $request->input('limit', 15);
        if (! User::find($user_id)) {
            return response()->json(static::createJsonData([
                'status'  => false,
                'code'    => 1023,
                'message' => '用户未找到',
            ]))->setStatusCode(404);
        }

        $follows = Following::where('user_id', $user_id)
            ->where(function ($query) use ($max_id) {
                if ($max_id > 0) {
                    $query->where('id', '<', $max_id);
                }
            })
            ->orderBy('id', 'DESC')
            ->take($limit)
            ->with('userFollowed')
            ->get();
        $datas['follows'] = [];
        foreach ($follows as $follow) {
            $data = [];
            $data['id'] = $follow->id;
            $data['user_id'] = $follow->following_user_id;
            $data['my_follow_status'] = 1; //我关注的列表  关注状态始终为1
            $data['follow_status'] = $follow->userFollowed->where('followed_user_id', $follow->following_user_id)->isEmpty() ? 0 : 1;
            $datas['follows'][] = $data;
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    /**
     * 查询粉丝.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function followeds(Request $request, int $user_id, int $max_id = 0)
    {
        $limit = $request->input('limit', 15);
        if (! User::find($user_id)) {
            return response()->json(static::createJsonData([
                'status'  => false,
                'code'    => 1023,
                'message' => '用户未找到',
            ]))->setStatusCode(404);
        }
        $followeds = Followed::where('user_id', $user_id)
            ->where(function ($query) use ($max_id) {
                if ($max_id > 0) {
                    $query->where('id', '<', $max_id);
                }
            })
            ->orderBy('id', 'DESC')
            ->take($limit)
            ->with('userFollowing')
            ->get();
        $datas['followeds'] = [];
        foreach ($followeds as $followed) {
            $data = [];
            $data['id'] = $followed->id;
            $data['user_id'] = $followed->followed_user_id;
            $data['my_follow_status'] = $followed->userFollowing->where('following_user_id', $followed->followed_user_id)->isEmpty() ? 0 : 1;

            $data['follow_status'] = 1; //关注我的的列表  对方关注状态始终为1
            $datas['followeds'][] = $data;
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    /**
     * 获取用户的关注状态
     *
     * @author bs<414606094@qq.com>
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function getFollowStatus(Request $request)
    {
        $user_id = $request->user()->id;
        $ids = explode(',', $request->user_ids);
        $data = [];
        if (is_array($ids) && $request->user_ids) {
            foreach ($ids as $key) {
                $return = [];
                $return['follow_status'] = Following::where('user_id', $key)->where('following_user_id', $user_id)->get()->isEmpty() ? 0 : 1;
                $return['my_follow_status'] = Followed::where('user_id', $key)->where('followed_user_id', $user_id)->get()->isEmpty() ? 0 : 1;
                $return['user_id'] = (int) $key;

                $data[] = $return;
            }
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $data,
        ]))->setStatusCode(200);
    }

    protected function checkUserFollowData($user_id, $countKey)
    {
        $allowedKey = ['following_count', 'followed_count'];

        if (in_array($countKey, $allowedKey)) {
            $map = ['key' => $countKey, 'user_id' => $user_id];
            $data = ['key' => $countKey, 'user_id' => $user_id, 'value' => 0];

            UserDatas::firstOrCreate($map, $data);
        }

        return true;
    }
}
