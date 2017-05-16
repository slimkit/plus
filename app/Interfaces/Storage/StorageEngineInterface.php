<?php

namespace Zhiyi\Plus\Interfaces\Storage;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Storages\StorageTaskResponse;

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
    public function createStorageTask(StorageTask $storageTask, User $user): StorageTaskResponse;

    /**
     * Notice upload message.
     *
     * @param string $message
     * @param string $filename
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function notice(string $message, string $filename);

    /**
     * Get resource url.
     *
     * @param string $filename
     * @param int $process
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function url(string $filename, int $process = 100): string;
}
