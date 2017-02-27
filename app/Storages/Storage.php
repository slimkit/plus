<?php

namespace Zhiyi\Plus\Storages;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Response;
use Zhiyi\Plus\Interfaces\Storage\StorageEngineInterface;
use Zhiyi\Plus\Models\Storage as StorageModel;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class Storage
{
    use CreateJsonResponseData;
    /**
     * 储存器列表.
     *
     * @var array
     */
    protected static $storages = [];

    /**
     * 初始化.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function __construct($storages = [])
    {
        foreach ($storages as $engine => $storage) {
            $this->setStorageEngine($engine, $storage);
        }

        if (!isset(static::$storages['local'])) {
            $storage = new Engine\LocalStorage();
            $this->setStorageEngine('local', $storage);
        }
    }

    /**
     * 设置储存引擎.
     *
     * @param string                 $engine  储存引擎名称
     * @param StorageEngineInterface $storage 储存引擎
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function setStorageEngine(string $engine, StorageEngineInterface $storage)
    {
        static::$storages[$engine] = $storage;

        return $this;
    }

    /**
     * 创建储存任务.
     *
     * @param User   $user            用户模型
     * @param string $origin_filename 原始文件名
     * @param string $hash            文件hash
     * @param string $engine          储存引擎
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function createStorageTask(User $user, string $origin_filename, string $hash, string $mimeType, float $width, float $height, $engine = 'local'): array
    {
        // 删除同hash任务
        StorageTask::where('hash', $hash)->delete();

        // 查询储存
        $storage = StorageModel::byHash($hash)->first();
        if (!$storage) { // 储存不存在，新建储存.
            return $this->newStorageTask($user, $origin_filename, $hash, $mimeType, $width, $height, $engine);
        }

        $task = new StorageTask();
        $task->hash = $storage->hash;
        $task->filename = $storage->filename;
        $task->origin_filename = $storage->origin_filename;
        $task->save();

        return [
            'storage_id'      => $storage->id,
            'storage_task_id' => $task->id,
        ];
    }

    /**
     * 新建储存任务
     *
     * @param User   $user            用户信息
     * @param string $origin_filename 原始文件名
     * @param string $hash            文件hash
     * @param string $engine          储存引擎
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function newStorageTask(User $user, string $origin_filename, string $hash, string $mimeType, float $width, float $height, string $engine): array
    {
        $task = new StorageTask();
        $task->hash = $hash;
        $task->origin_filename = $origin_filename;
        $task->filename = static::createStorageFilename($origin_filename, $hash);
        $task->mime_type = $mimeType;
        $task->width = $width;
        $task->height = $height;

        return static::$storages[$engine]->createStorageTask($task, $user);
    }

    public function notice(string $message, StorageTask $task, string $engine = 'local')
    {
        $response = static::$storages[$engine]->notice($message, $task->filename);

        if ($response instanceof Response) {
            return $response;
        }

        // 保存任务附件.
        $storage = StorageModel::byHash($task->hash)->first();
        if (!$storage) {
            $storage = new StorageModel();
            $storage->hash = $task->hash;
            $storage->origin_filename = $task->origin_filename;
            $storage->filename = $task->filename;
            $storage->mime = $task->mime_type ?? $this->mimeType($task->filename, $engine);
            $storage->extension = app(Filesystem::class)->extension($task->origin_filename);
            $storage->image_width = $task->width;
            $storage->image_height = $task->height;
            $storage->save();
        }

        return response()->json(static::createJsonData([
            'status' => true,
        ]));
    }

    public function url(string $filename, int $process = 100, string $engine = 'local')
    {
        return static::$storages[$engine]->url($filename, $process);
    }

    /**
     * 判断文件是否存在.
     *
     * @param string $filename 文件名
     * @param string $engine   储存引擎
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function exists(string $filename, $engine = 'local'): bool
    {
        return static::$storages[$engine]->exists($filename);
    }

    /**
     * 获取文件mimeType信息.
     *
     * @param string $filename 文件名
     * @param string $engine   储存引擎
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function mimeType(string $filename, $engine = 'local'): string
    {
        return static::$storages[$engine]->mimeType($filename);
    }

    /**
     * 创建文件储存路径.
     *
     * @param string $origin_filename 原始文件名
     * @param string $hash            文件hash
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public static function createStorageFilename(string $origin_filename, string $hash): string
    {
        $filename = app(Carbon::class)->format('Y/m/d/Hs/').$hash;
        $extension = app(Filesystem::class)->extension($origin_filename);

        return $filename.'.'.$extension;
    }
}
