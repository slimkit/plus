<?php

namespace Ts\Interfaces\Storage;

interface StorageEngineInterface
{
    /**
     * 创建一个储存任务.
     *
     * @param string $fileName 文件原始名称
     * @param string $fileHash 文件hash值
     * @param array $options 更多参数
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    // public function createStorageTask($fileName, $fileHash, $options = []);

    /**
     * 保存储存.
     *
     * @param array $options 参数信息
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    // public function saveStorage($options = []);

    /**
     * 任务回掉.
     *
     * @param array $options 参数信息
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    // public function taskCallback($options = []);
}
