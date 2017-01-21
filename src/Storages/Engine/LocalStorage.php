<?php

namespace Ts\Storages\Engine;

use App\Exceptions\MessageResponseBody;
use App\Models\StorageTask;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Image;
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
            'method'          => 'POST',
            'storage_task_id' => $storateTask->id,
            'headers'         => [
                'ACCESS-TOKEN' => $token->token,
            ],
            'options' => [],
        ];
    }

    public function notice(string $message, string $filename, MessageResponseBody $response)
    {
        return $response->setStatus($this->exists($filename));
    }

    public function url(string $filename, int $process = 100)
    {
        $path = $this->markProcessFilename($filename, $process);

        return url($path);
    }

    protected function markProcessFilename(string $filename, int $process)
    {
        // base info.
        $filesystem = app(Filesystem::class);
        $name = $filesystem->name($filename);
        $dir = 'process/'.$filesystem->dirname($filename);
        $ext = $filesystem->extension($filename);

        // create new image filename.
        $processFilename = sprintf('%s/%s/%s.%s', $dir, $name, $process, $ext);

        //  return origin.
        if (
            !in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'webp'])
            || $process === 100
        ) {
            return Storage::url($filename);
        } elseif ($this->exists($processFilename)) {
            return Storage::url($processFilename);
        }

        // create image resource.
        $fullpath = storage_path('app/public/'.$filename);
        $image = Image::make($fullpath);

        // get origin image size.
        $width = $image->width();
        $height = $image->height();

        // Calculate new size.
        $processWidth = intval($width / 100 * $process);
        $processHeight = intval($height / 100 * $process);

        // Setting new image size.
        $image->resize($processWidth, $processHeight);

        // 打包新文件.
        $image->encode();

        // 保存转换文件
        Storage::disk('public')
            ->put($processFilename, $image, 'public');

        return Storage::url($processFilename);
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
