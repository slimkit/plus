<?php

namespace Ts\Storages;

use Ts\Interfaces\Storage\StorageEngineInterface;

class Storage
{
    /**
     * 储存器列表
     *
     * @var array
     */
    protected static $storages = [];

    /**
     * 初始化
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
     * 设置储存引擎
     *
     * @param string $engine 储存引擎名称
     * @param StorageEngineInterface $storage 储存引擎
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
     * @param string $fileHash 文件hash值
     * @param string $engine 文件储存引擎
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function createStorageTask(string $fileHash, string $engine = 'local')
    {
        var_dump($fileHash, $engine);exit;
    }
}
