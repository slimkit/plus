<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Models\Storage as StorageModel;
use App\Models\StorageTask;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Zhiyi\Plus\Storages\Storage;

class StorageController extends Controller
{
    protected static $storage;

    public function get(Request $request, StorageModel $storage, int $process = 100)
    {
        $url = $this->storage()->url($storage->filename, $process);

        return redirect($url, 302);
    }

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
    public function create(Request $request, string $hash, string $origin_filename)
    {
        $user = $request->attributes->get('user');
        $storage = $this->storage()->createStorageTask($user, $origin_filename, $hash);

        return response()->json(static::createJsonData([
            'status' => true,
            'data'   => $storage,
        ]))->setStatusCode(201);
    }

    public function notice(Request $request, int $storage_task_id)
    {
        $task = StorageTask::find($storage_task_id);
        if (!$task) {
            return response()->json(static::createJsonData([
                'code'    => 2000,
                'message' => '上传任务不存在',
            ]))->setStatusCode(404);
        }

        $message = $request->input('message');

        return $this->storage()->notice($message, $task);
    }

    public function delete(Request $request, int $storage_task_id)
    {
        $task = StorageTask::find($storage_task_id);
        if (!$task) {
            return response()->json(static::createJsonData([
                'code'    => 2000,
                'message' => '上传任务不存在',
            ]))->setStatusCode(404);
        }

        $user = $request->attributes->get('user');
        $user->storages()->where('hash', $task->hash)->delete();
        $task->delete();

        return response()->json(static::createJsonData([
            'status' => true,
        ]));
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
        $task = StorageTask::find($storage_task_id);
        if (!$task) {
            return response()->json(static::createJsonData([
                'code'    => 2000,
                'message' => '上传任务不存在',
            ]))->setStatusCode(404);
        }

        return $this->uploadIsExister($request, $task);
    }

    protected function uploadIsExister(Request $request, StorageTask $task)
    {
        $storage = StorageModel::byHash($task->hash)->first();
        if ($storage) {
            return response()->json(static::createJsonData([
                'status' => true,
            ]));
        }

        return $this->uploadIsExisteFile($request, $task);
    }

    protected function uploadIsExisteFile(Request $request, StorageTask $task)
    {
        $file = current($request->file());
        if ($file === false) {
            return response()->json(static::createJsonData([
                'code' => 2001,
            ]))->setStatusCode(404);
        }

        return $this->runUploadAction($task, $file);
    }

    protected function runUploadAction(StorageTask $task, $file)
    {
        $filesystem = app(Filesystem::class);
        $path = 'public/'.$filesystem->dirname($task->filename);
        $name = $filesystem->basename($task->filename);

        if (!$file->storePubliclyAs($path, $name)) {
            return response()->json(static::createJsonData([
                'code' => 2002,
            ]))->setStatusCode(422);
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '上传成功',
        ]));
    }

    protected function storage()
    {
        if (!static::$storage instanceof Storage) {
            static::$storage = new Storage();
        }

        return static::$storage;
    }
}
