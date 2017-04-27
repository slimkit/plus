<?php

namespace Zhiyi\Plus\Storages;

use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Response;
use Zhiyi\Plus\Models\StorageTask;
use Illuminate\Filesystem\Filesystem;
use Zhiyi\Plus\Traits\CreateJsonResponseData;
use Zhiyi\Plus\Models\Storage as StorageModel;
use Zhiyi\Plus\Services\Storage as StorageService;
use Zhiyi\Plus\Interfaces\Storage\StorageEngineInterface;

class Storage
{
    use CreateJsonResponseData;

    protected $aliases = [];

    /**
     * 设置所有存储引擎.
     *
     * @param StorageService $service
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(StorageService $service)
    {
        foreach ($service->getEngines() as $engine => $value) {
            // Set engine alias.
            $this->aliases[$engine] = $value['engine'];
        }
    }

    /**
     * Get engine.
     *
     * @param string $engine
     * @return [type] [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function engine(string $engine): StorageEngineInterface
    {
        return app(
            $this->getAlise($engine)
        );
    }

    /**
     * Get storage engine alise.
     *
     * @param string $abstrace
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAlise(string $abstrace): string
    {
        if (! isset($this->aliases[$abstrace])) {
            throw new \InvalidArgumentException(sprintf('The "%s" storage engine does not exist.', $abstrace));
        }

        return $this->aliases[$abstrace];
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
        if (! $storage) { // 储存不存在，新建储存.
            return $this->newStorageTask($user, $origin_filename, $hash, $mimeType, $width, $height, $engine);
        }

        $task = new StorageTask();
        $task->hash = $storage->hash;
        $task->filename = $storage->filename;
        $task->origin_filename = $storage->origin_filename;
        $task->mime_type = $storage->mime;
        $task->width = $storage->width;
        $task->height = $storage->height;
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

        $response = $this->engine($engine)->createStorageTask($task, $user);

        // 依旧切换为后置保存，本地存储可以自行先保存后再做处理。
        $task->save();

        return array_merge($response->toArray(), [
            'storage_task_id' => $task->id,
        ]);
    }

    public function notice(string $message, StorageTask $task, string $engine = 'local')
    {
        $response = $this->engine($engine)->notice($message, $task->filename);

        if ($response instanceof Response) {
            return $response;
        }

        // 保存任务附件.
        $storage = StorageModel::byHash($task->hash)->first();
        if (! $storage) {
            $storage = new StorageModel();
            $storage->hash = $task->hash;
            $storage->origin_filename = $task->origin_filename;
            $storage->filename = $task->filename;
            $storage->mime = $task->mime_type;
            $storage->extension = app(Filesystem::class)->extension($task->origin_filename);
            $storage->image_width = $task->width;
            $storage->image_height = $task->height;
            $storage->save();
        }

        return response()->json(static::createJsonData([
            'status' => true,
        ]))->setStatusCode(201);
    }

    public function url(string $filename, int $process = 100, string $engine = 'local')
    {
        // 处理转换值，保证范围是1-100
        $process <= 0 && $process = 100;
        $process = min($process, 100);

        return $this->engine($engine)->url($filename, $process);
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
