<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\UserProfileSetting;

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
            $datas[$profile->id] = $request->input($profile->profile);
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
        $datas = $users = User::whereIn('id', $request->user_ids)
            ->with('datas', 'counts')
            ->get()
            ->toArray();
        if (!$datas) {
            return response()->json([
                'status'  => false,
                'message' => '没有相关用户',
                'code'    => 1019,
                'data'    => null,
            ])->setStatusCode(404);
        }

        foreach ($datas as &$user) {
            foreach ($user['datas'] as &$profile) {
                $create_time = new Carbon($profile['pivot']['created_at']);
                $profile['pivot']['created_at'] = $create_time->timestamp;
                $update_time = new Carbon($profile['pivot']['updated_at']);
                $profile['pivot']['updated_at'] = $update_time->timestamp;
            }
        }

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ])->setStatusCode(201);
    }
}
