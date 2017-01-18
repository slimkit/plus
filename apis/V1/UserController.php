<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use App\Models\Storage as StorageModel;
use App\Models\StoragesTask;
use App\Models\StorageUserLink;
use App\Models\User;
use App\Models\UserProfileSetting;
use Illuminate\Http\Request;
use Ts\Storages\Storage;

class UserController extends Controller
{
    protected static $storage;

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
        $user = $request->attributes->get('user');
        $user->createPassword($password);
        $user->save();

        return app(MessageResponseBody::class, [
            'status' => true,
        ])->setStatusCode(201);
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
        $user = $request->attributes->get('user');
        $profileData = $request->all();
        $profileSettings = UserProfileSetting::whereIn('profile', array_keys($profileData))->get();
        $datas = [];
        foreach ($profileSettings as $profile) {
            $datas[$profile->id] = $request->input($profile->profile);
        }
        $user->syncData($datas);

        return app(MessageResponseBody::class, [
            'code'    => 0,
            'status'  => true,
        ])->setStatusCode(201);
    }

    /**
     * [get description]
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-18T17:54:59+0800
     * @param    User                       $user [description]
     * @return   [type]                           [description]
     */
    public function get(int $user)
    {
        $user = User::find($user);
        if (!$user or !$user instanceof User) {
            
            return app(MessageResponseBody::class, [
                'status'  => false,
                'code'    => 1005,
            ])->setStatusCode(404);
        }
        $userDatas = $user->datas;
        $datas = [];
        foreach ($userDatas as $value) {
            $datas[$value->profile] = $value->pivot->user_profile_setting_data;
        }

        return app(MessageResponseBody::class, [
            'status'  => true,
            'data'    => $datas,
        ])->setStatusCode(201);
    }

    public function settings()
    {
        var_dump(123);
    }

    public function setAvatar(Request $request)
    {
        $task = StoragesTask::find($request->input('storage_task_id'));
        if (!$task) {
            return app(MessageResponseBody::class, [
                'code'    => 2000,
                'message' => '上传任务不存在',
            ])->setStatusCode(404);
        }

        $user = $request->attributes->get('user');

        // 附件
        $storage = StorageModel::byHash($task->hash)->first();
        if (!$storage) {
            return app(MessageResponseBody::class, [
                'code' => 2004,
            ]);
        }

        $link = $user->storagesLinks()->where('storage_id', $storage->id)->first();
        if (!$link) {
            $link = new StorageUserLink();
            $link->storage_id = $storage->id;
            $link->user_id = $user->id;
        }

        $link->save();

        return app(MessageResponseBody::class, [
            'status' => true,
        ]);
    }

    protected function storage()
    {
        if (!static::$storage instanceof Storage) {
            static::$storage = new Storage();
        }

        return static::$storage;
    }
}
