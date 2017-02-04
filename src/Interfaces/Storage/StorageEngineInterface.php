<?php

namespace Zhiyi\Plus\Interfaces\Storage;

use App\Models\StorageTask;
use App\Models\User;

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
    public function createStorageTask(StorageTask $storageTask, User $user);

    /**
     * 验证文件是否存在.
     *
     * @param string $filename 文件名
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function exists(string $filename): bool;

    /**
     * 获取文件完整的mimeType信息.
     *
     * @param string $filename 文件名
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function mimeType(string $filename): string;
}
