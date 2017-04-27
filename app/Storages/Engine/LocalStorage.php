<?php

namespace Zhiyi\Plus\Storages\Engine;

use Image;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\StorageTask;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Zhiyi\Plus\Storages\StorageTaskResponse;
use Zhiyi\Plus\Traits\CreateJsonResponseData;
use Zhiyi\Plus\Interfaces\Storage\StorageEngineInterface;

class LocalStorage implements StorageEngineInterface
{
    use CreateJsonResponseData;

    public function createStorageTask(StorageTask $storateTask, User $user): StorageTaskResponse
    {
        $tokenRow = $user->tokens()->orderByDesc()->first();
        if (! $tokenRow) {
            throw new \Exception('No authentication information associated with the user was found.');
        }

        // save task.
        $storateTask->save();

        $response = new StorageTaskResponse();
        $response->setURI(route('storage/upload', [$storateTask->id]))
            ->setMethod('POST')
            ->setHeaders(['Authorization' => $tokenRow->token]);

        return $response;
    }

    public function notice(string $message, string $filename)
    {
        unset($message);
        if (! $this->exists($filename)) {
            return response()->json(static::createJsonData([
                'status' => false,
            ]));
        }

        return true;
    }

    public function url(string $filename, int $process = 100): string
    {
        $path = $this->markProcessFilename($filename, $process);

        return url($path);
    }

    protected function markProcessFilename(string $filename, int $process)
    {
        // make process.
        $process = max($process, 1);
        $process = min($process, 100);

        // base info.
        $filesystem = app(Filesystem::class);
        $name = $filesystem->name($filename);
        $dir = 'process/'.$filesystem->dirname($filename);
        $ext = $filesystem->extension($filename);

        // create new image filename.
        $processFilename = sprintf('%s/%s/%s.%s', $dir, $name, $process, $ext);

        //  return origin.
        if (
            ! in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'webp'])
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
