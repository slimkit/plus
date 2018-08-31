<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Filesystems;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Plus\FileStorage\Task;
use Zhiyi\Plus\FileStorage\TaskInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use function Zhiyi\Plus\setting;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class LocalFilesystem implements FilesystemInterface
{
    protected $filesystem;

    public function __construct(FilesystemContract $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function meta(ResourceInterface $resource): FileMetaInterface
    {
        return new Local\FileMeta($this->filesystem, $resource);
    }

    public function url(string $path, ?string $rule = null): string
    {
        //
    }

    public function delete(string $path): bool
    {
        //
    }

    public function createTask(Request $request, ResourceInterface $resource): TaskInterface
    {
        $expiresAt = (new Carbon)->addHours(
            setting('core', 'file:put-signature-expires-at', 1)
        );
        $uri = url()->temporarySignedRoute('storage:local-put', $expiresAt, [
            'channel' => $resource->getChannel(),
            'path' => base64_encode($resource->getPath()),
        ]);
        $user = $this->guard()->user();

        return new Task($resource, $uri, 'PUT', null, null, [
            'Authorization' => 'Bearer '.$this->guard()->login($user),
            'x-plus-storage-filename' => $request->input('filename'),
            'x-plus-storage-hash' => $request->input('hash'),
            'x-plus-storage-size' => $request->input('size'),
            'x-plus-storage-mime-type' => $request->input('mime_type'),
        ]);
    }

    public function put(string $path, $content): bool
    {
        return (bool) $this->filesystem->put($path, $content);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): Guard
    {
        return Auth::guard('api');
    }
}
