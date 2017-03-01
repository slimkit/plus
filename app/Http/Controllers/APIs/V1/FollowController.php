<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\User;

class FollowController extends Controller
{
    public function __construct(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user or !$user instanceof User) {
            return response()->json(static::createJsonData([
                'status'  => false,
                'code'    => 1005,
            ]))->setStatusCode(404);
        }
    }

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
        Following::create([
            'user_id'           => $user_id,
            'following_user_id' => $follow_user_id,
        ]);
        Followed::create([
            'user_id'          => $follow_user_id,
            'followed_user_id' => $user_id,
        ]);

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '关注成功',
        ]))->setStatusCode(201);
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

        // 我关注的
        Following::where([
                ['user_id', $user_id],
                ['following_user_id', $follow_user_id],
            ])
            ->delete();

        // 目标用户我的粉丝
        Followed::where([
            ['user_id', $follow_user_id],
            ['followed_user_id', $user_id],
        ])
        ->delete();

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '成功取关',
        ]))->setStatusCode(200);
    }

    /**
     * 关注的用户.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function follows(int $user_id, int $max_id = 0)
    {
        if (!User::find($user_id)) {
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
            ->take(15)
            ->with('followed')
            ->get();
        $datas['follows'] = [];
        foreach ($follows as $follow) {
            $data = [];
            $data['id'] = $follow->id;
            $data['user_id'] = $follow->following_user_id;
            $data['my_follow_status'] = 1; //我关注的列表  关注状态始终为1
            $data['follow_status'] = $follow->followed->where('followed_user_id', $follow->following_user_id)->isEmpty() ? 0 : 1;
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
    public function followeds(int $user_id, int $max_id = 0)
    {
        if (!User::find($user_id)) {
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
            ->take(15)
            ->with('following')
            ->get();
        $datas['followeds'] = [];
        foreach ($followeds as $followed) {
            $data = [];
            $data['id'] = $followed->id;
            $data['user_id'] = $followed->followed_user_id;
            $data['my_follow_status'] = $followed->following->where('following_user_id', $followed->followed_user_id)->isEmpty() ? 0 : 1;

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
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getFollowStatus(Request $request)
    {
        $user_id = $request->user()->id;
        $ids = explode(',', $request->user_ids);
        $data = [];
        if (is_array($ids) && $request->user_ids) {
            foreach ($ids as $key => $value) {
                $return = [];
                $following = Following::where('user_id', $value)->where('following_user_id', $user_id)->get()->isEmpty();
                $followed = Followed::where('user_id', $value)->where('followed_user_id', $user_id)->get()->isEmpty();

                $return['user_id'] = $value;
                if ($following && $followed) {
                    $return['followstatus'] = 0;// 双方未关注
                } elseif (!$following && $followed) {
                    $return['followstatus'] = 1;// 该用户关注当前用户  当前用户未关注该用户
                } elseif ($following && !$followed) {
                    $return['followstatus'] = 2;// 该用户未关注当前用户  当前用户关注该用户
                } elseif (!$following && !$followed) {
                    $return['followstatus'] = 3;// 双方互相关注
                }

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
}
