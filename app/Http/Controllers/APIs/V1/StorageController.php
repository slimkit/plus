<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Storage as StorageModel;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Storages\Storage;

class StorageController extends Controller
{
    protected static $storage;

    public function get(Request $request, StorageTask $storage, int $process = 100)
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
    public function create(Request $request)
    {
        $user = $request->user();

        $originFilename = $request->input('origin_filename');
        $hash = $request->input('hash');
        $mimeType = $request->input('mime_type');
        $width = (float) $request->input('width', 0);
        $height = (float) $request->input('height', 0);

        if (!$originFilename || !$hash || !$mimeType) {
            return response()->json(static::createJsonData([
                'status'  => false,
                'message' => '发送参数错误',
            ]));
        }

        $storage = $this->storage()->createStorageTask($user, $originFilename, $hash, $mimeType, $width, $height);

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

        $user = $request->user();
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
