<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Closure;
use Illuminate\Http\Request;
use Zhiyi\Plus\Storages\Storage;
use Illuminate\Http\UploadedFile;
use Zhiyi\Plus\Models\StorageTask;
use Illuminate\Filesystem\Filesystem;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Storage as StorageModel;
use Zhiyi\Plus\Services\Storage as ServiceStorage;

class StorageController extends Controller
{
    protected static $engine;
    protected static $service;

    public function __construct(ServiceStorage $service)
    {
        static::$service = $service;
        static::$engine = $service->getEngineSelect();
    }

    /**
     * 从URL请求中获取指定key变为数组.
     *
     * @param Request $request
     * @param string $key
     * @param string $delimiter
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getQueryItemToArray(Request $request, string $key, string $delimiter = ','): array
    {
        $value = $request->query($key, []);

        if (! is_array($value)) {
            $value = explode($delimiter, $value);
        }

        return $value;
    }

    /**
     * 获取资源真实地址.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getStorageLinks(Request $request)
    {
        $ids = $this->getQueryItemToArray($request, 'id', ',');
        $process = $this->getQueryItemToArray($request, 'process', ',');
        $source = array_reduce($ids, function (array $carry, $item) use (&$process) {
            if (intval($item)) {
                $carry[$item] = intval(current($process) ?: 100);
            }
            next($process);

            return $carry;
        }, []);

        $storages = StorageModel::whereIn('id', array_keys($source))->get();
        $links = $storages->reduce(Closure::bind(
            function (array $carry, StorageModel $storage) use ($source) {
                $id = $storage->id;
                $filename = $storage->filename;
                $process = $source[$id];
                $carry[$id] = $this->storage()->url($filename, $process, static::$engine);

                return $carry;
            },
            $this
        ), []);

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $links,
        ]))->setStatusCode(200);
    }

    /**
     * 获取储存资源.
     *
     * @param StorageModel $storage
     * @param int $process
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function get(StorageModel $storage, int $process = 100)
    {
        $url = $this->storage()->url($storage->filename, $process, static::$engine);

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

        if (! $originFilename || ! $hash || ! $mimeType) {
            return response()->json(static::createJsonData([
                'status'  => false,
                'message' => '发送参数错误',
            ]));
        }

        $storage = $this->storage()->createStorageTask($user, $originFilename, $hash, $mimeType, $width, $height, static::$engine);

        return response()->json(static::createJsonData([
            'status' => true,
            'data'   => $storage,
        ]))->setStatusCode(201);
    }

    public function notice(Request $request, int $storage_task_id)
    {
        $task = StorageTask::find($storage_task_id);
        if (! $task) {
            return response()->json(static::createJsonData([
                'code'    => 2000,
                'message' => '上传任务不存在',
            ]))->setStatusCode(404);
        }

        $message = strval($request->input('message', ''));

        return $this->storage()->notice($message, $task, static::$engine);
    }

    public function delete(Request $request, int $storage_task_id)
    {
        $task = StorageTask::find($storage_task_id);
        if (! $task) {
            return response()->json(static::createJsonData([
                'code'    => 2000,
                'message' => '上传任务不存在',
            ]))->setStatusCode(404);
        }

        $user = $request->user();
        $user->storages()->detach(
            $user->storages()->where('hash', $task->hash)->get()
        );
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
        if (! $task) {
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

        return $this->uploadIsLimitedSize($task, $file);
    }

    protected function uploadIsLimitedSize(StorageTask $task, UploadedFile $file)
    {
        $size = $file->getClientSize();
        if ($size >= 1024 * 1024 * 9) {
            return response()->json(static::createJsonData([
                'message' => '上传文件大小超过服务器限制',
            ]))->setStatusCode(502);
        }

        return $this->runUploadAction($task, $file);
    }

    protected function runUploadAction(StorageTask $task, UploadedFile $file)
    {
        $filesystem = app(Filesystem::class);
        $path = $filesystem->dirname($task->filename);
        $name = $filesystem->basename($task->filename);

        if (! $file->storePubliclyAs($path, $name, 'public')) {
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
        return static::$service->getStorage();
    }
}
