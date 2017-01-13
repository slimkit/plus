<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use App\Models\Storage as StorageModel;
use App\Models\StorageTask;
use App\Models\StorageUserLink;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FilesystemManager;
use Ts\Storages\Storage;

class StorageController extends Controller
{
    /**
     * 创建上传任务
     *
     * @param Request $request         请求对象
     * @param string  $hash            文件hash
     * @param string  $origin_filename 文件原始名称
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function createStorageTask(Request $request, string $hash, string $origin_filename)
    {
        $user = $request->attributes->get('user');
        $storage = app(Storage::class)->createStorageTask($user, $origin_filename, $hash);

        return app(MessageResponseBody::class, [
            'status' => true,
            'data'   => $storage,
        ])->setStatusCode(201);
    }

    public function notice(Request $request, int $storage_task_id)
    {
        $storageTask = StorageTask::find($storage_task_id);
        if (!$storageTask) {
            return app(MessageResponseBody::class, [
                'code'    => 2000,
                'message' => '上传任务不存在',
            ])->setStatusCode(404);
        }

        if (!app(Storage::class)->exists($storageTask->filename)) {
            return app(MessageResponseBody::class, [
                'code' => 2003,
            ]);
        }

        $user = $request->attributes->get('user');
        $storage = StorageModel::byHash($storageTask->hash)->first();
        if (!$storage) {
            $storage = new StorageModel();
            $storage->hash = $storageTask->hash;
            $storage->origin_filename = $storageTask->origin_filename;
            $storage->filename = $storageTask->filename;
            $storage->mime = app(Storage::class)->mimeType($storageTask->filename);
            $storage->extension = app(Filesystem::class)->extension($storageTask->filename);

            $storage = $user->storages()->save($storage, ['created_at' => $user->freshTimestamp(), 'updated_at' => $user->freshTimestamp()]);
        } elseif (!$user->storagesLinks()->where('storage_id', $storage->id)->first()) {
            $link = new StorageUserLink();
            $link->storage_id = $storage->id;
            $link->user_id = $user->id;
            $link->save();
        }

        $storageTask->delete();

        return app(MessageResponseBody::class, [
            'status' => true,
            'data'   => $storage->id,
        ]);
    }

    /**
     * 上传文件.
     *
     * @param Request $request         请求对象
     * @param int     $storage_task_id 任务id
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function upload(Request $request, int $storage_task_id)
    {
        $storageTask = StorageTask::find($storage_task_id);
        if (!$storageTask) {
            return app(MessageResponseBody::class, [
                'code'    => 2000,
                'message' => '上传任务不存在',
            ])->setStatusCode(404);
        }

        $file = current($request->file());
        if ($file === false) {
            return app(MessageResponseBody::class, [
                'code' => 2001,
            ])->setStatusCode(404);
        }

        $filesystem = app(Filesystem::class);
        $path = 'public/'.$filesystem->dirname($storageTask->filename);
        $name = $filesystem->basename($storageTask->filename);

        if (!$file->storePubliclyAs($path, $name)) {
            return app(MessageResponseBody::class, [
                'code' => 2002,
            ])->setStatusCode(422);
        }

        $url = FilesystemManager::url($storageTask->filename);

        return app(MessageResponseBody::class, [
            'status'  => true,
            'message' => '上传成功',
            'data'    => url($url),
        ]);
    }
}
