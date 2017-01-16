<?php

namespace Ts\Storages;

use App\Models\Storage as StorageModel;
use App\Models\StorageTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Ts\Interfaces\Storage\StorageEngineInterface;

class Storage
{
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
    public function createStorageTask(User $user, string $origin_filename, string $hash, $engine = 'local'): array
    {
        $storageInfo = StorageModel::byHash($hash)->first();
        if ($storageInfo) {
            return [
                'storage_id' => $storageInfo->id,
            ];
        }

        $storageTask = new StorageTask();
        $storageTask->origin_filename = $origin_filename;
        $storageTask->hash = $hash;
        $storageTask->filename = static::createStorageFilename($origin_filename, $hash);

        return static::$storages[$engine]->createStorageTask($storageTask, $user);
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
