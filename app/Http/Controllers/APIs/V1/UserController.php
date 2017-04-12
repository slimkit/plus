<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use DB;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\UserDatas;
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
     * [get description].
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-18T17:54:59+0800
     *
     * @param User $user [description]
     *
     * @return [type] [description]
     */
    public function get(Request $request)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        $datas = User::whereIn('id', $request->user_ids)
            ->with('datas', 'counts')
            ->get()
            ->toArray();
        if (! $datas) {
            return response()->json([
                'status'  => false,
                'message' => '没有相关用户',
                'code'    => 1019,
                'data'    => null,
            ])->setStatusCode(404);
        }

        foreach ($datas as &$data) {
            $data['is_following'] = Following::where('user_id', $uid)->where('following_user_id', $data['id'])->get()->isEmpty() ? 0 : 1;
            $data['is_followed'] = Followed::where('user_id', $uid)->where('followed_user_id', $data['id'])->get()->isEmpty() ? 0 : 1;
        }

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ])->setStatusCode(201);
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
}
