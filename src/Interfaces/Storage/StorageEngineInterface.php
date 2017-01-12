<?php

namespace Ts\Interfaces\Storage;

use App\Models\StorageTask;

interface StorageEngineInterface
{
    /**
     * 创建一个任务
     *
     * @param StorageTask $storageTask 任务记录保存容器
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function createStorageTask(StorageTask $storageTask);

    /*
     * 保存储存.
     *
     * @param array $options 参数信息
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    // public function saveStorage($options = []);

    /*
     * 任务回掉.
     *
     * @param array $options 参数信息
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    // public function taskCallback($options = []);
}
