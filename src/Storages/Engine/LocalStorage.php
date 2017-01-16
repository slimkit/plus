<?php

namespace Ts\Storages\Engine;

use App\Exceptions\MessageResponseBody;
use App\Models\StorageTask;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Ts\Interfaces\Storage\StorageEngineInterface;

class LocalStorage implements StorageEngineInterface
{
    public function createStorageTask(StorageTask $storateTask, User $user)
    {
        $token = $user->tokens()->orderByDesc()->first();
        if (!$token) {
            throw new \Exception('No authentication information associated with the user was found.');
        }

        $storateTask->save();

        return [
            'uri'             => route('storage/upload', [$storateTask->id]),
            'method'          => 'PUT',
            'storage_task_id' => $storateTask->id,
            'headers'         => [
                'ACCESS-TOKEN' => $token->token,
            ],
            'options' => [],
        ];
    }

    public function notice(string $message, string $filename, MessageResponseBody $response)
    {
        $response->setStatus($this->exists($filename));
    }

    public function url(string $filename)
    {
        $path = Storage::url($this->getPath($filename));
        return url($path);
    }

    /**
     * 判断文件是否存在.
     *
     * @param string $filename 文件名
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function exists(string $filename): bool
    {
        return Storage::exists($this->getPath($filename));
    }

    /**
     * 获取文件mimeType信息.
     *
     * @param string $filename 文件名
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function mimeType(string $filename): string
    {
        return Storage::mimeType($this->getPath($filename));
    }

    /**
     * 获取文件完整路径.
     *
     * @param string $path 文件名
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getPath(string $path): string
    {
        return 'public/'.$path;
    }
}
