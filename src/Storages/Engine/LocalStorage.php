<?php

namespace Ts\Storages\Engine;

use App\Exceptions\MessageResponseBody;
use App\Models\StorageTask;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Ts\Interfaces\Storage\StorageEngineInterface;
use Illuminate\Filesystem\Filesystem;
use Image;

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

    public function url(string $filename, array $process = [])
    {
        $path = $this->markProcessFilename($filename, $process);

        return url($path);
    }

    protected function markProcessFilename(string $filename, array $process = [])
    {
        $filesystem = app(Filesystem::class);
        $name = $filesystem->name($filename);
        $dir = 'process/'.$filesystem->dirname($filename);
        $ext = $filesystem->extension($filename);

        if (!in_array(strtolower($ext), ['png', 'jpg', 'jpge', 'webp'])) {
            return Storage::url($filename);
        }

        $crop = array_get($process, 'crop');
        $crop_w = array_get($crop, 'w');
        $crop_h = array_get($crop, 'h');
        $crop_x = array_get($crop, 'x', null);
        $crop_y = array_get($crop, 'y', null);

        $quality = array_get($process, 'quality', 100);

        $resize = array_get($process, 'resize');
        $resize_w = array_get($resize, 'w');
        $resize_h = array_get($resize, 'h');

        $e = implode('-', [
            'c_w'.$crop_w,
            'c_h'.$crop_h,
            'c_x'.$crop_x,
            'c_y'.$crop_y,
            'q'.$quality,
            'r_w'.$resize_w,
            'r_h'.$resize_h,
        ]);

        $newfilename = $dir.'/'.$name.'/'.$e.'.'.$ext;
        $newpath = Storage::url($newfilename);
        if ($this->exists($newfilename)) {
            return $newpath;
        }

        $fullpath = storage_path('app/public/'.$filename);
        $image = Image::make($fullpath);

        // if ($quality) {
        //     $image->encode($ext, $quality);
        // }

        if ($crop_w && $crop_h) {
            $image->crop($crop_w, $crop_h, $crop_x, $crop_y);
        }

        if ($resize_w || $resize_h) {
            $image->resize($resize_w, $resize_h);
        }

        $savepath = storage_path('app/public/'.$newfilename);
        Storage::makeDirectory(dirname($this->getPath($newfilename)));
        $image->save($savepath, $quality);

        return $newpath;
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
