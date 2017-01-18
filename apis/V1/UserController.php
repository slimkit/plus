<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use App\Models\Storage as StorageModel;
use App\Models\StoragesTask;
use App\Models\StorageUserLink;
use Illuminate\Http\Request;
use Ts\Storages\Storage;
use App\Models\UserProfileSetting;
use App\Models\UserProfileSettingLink;
use App\Models\User;

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
     * 修改用户资料
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-17T17:25:45+0800
     * @param    Request  $request [description]
     * @return   mixed    返回结果
     */
    public function profile(Request $request) {
        $user = $request->attributes->get('user');
        // $userDatas = $user->datas;
        // foreach ($userDatas as &$value) {
        //     $value->profile_name = $value->name;
        // }
        // dump($userDatas);
        // die;
        $profileData = $request->all();

        dump(array_filter($profileData));
        $settingModel = new UserProfileSetting();
        $settingData = $settingModel
            ->byRequired(1)
            ->byState(1)
            ->get()
        ;
        foreach ($settingData as $key => $value) {
            
        }
        dump($settingData);
        $profileSettinglinks = UserProfileSettingLink::ByUserId($user->id)
            ->get();
        dump($profileSettinglinks);die;
        // return app(MessageResponseBody::class, [
        //     'data' => $profileSettings
        // ])->setStatusCode(201);
    }

    public function get(User $user)
    {
        $userDatas = $user->datas;
        $data = [];
        foreach ($userDatas as $value) {
            $data['user_id'] = $user->id;
            $data['avatar'] = '';
            $data[$value->name->profile] = $value->user_profile_setting_data;
        }
        return app(MessageResponseBody::class, [
                'code'    => 0,
                'status' => true,
                'data' => $data,
            ])->setStatusCode(201);
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
