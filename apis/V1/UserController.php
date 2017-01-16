<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoragesTask;
use App\Models\Storage as StorageModel;
use Ts\Storages\Storage;
use App\Models\StorageUserLink;

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
