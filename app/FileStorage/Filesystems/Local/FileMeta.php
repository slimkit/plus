<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Filesystems\Local;

use Closure;
use Exception;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\FileStorage\ImageDimension;
use Zhiyi\Plus\FileStorage\Pay\PayInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\FileMetaInterface;
use Zhiyi\Plus\FileStorage\Traits\HasImageTrait;
use Zhiyi\Plus\FileStorage\ImageDimensionInterface;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class FileMeta implements FileMetaInterface
{
    use HasImageTrait;

    protected $filesystem;
    protected $resource;
    protected $dimension;

    public function __construct(FilesystemContract $filesystem, ResourceInterface $resource)
    {
        $this->filesystem = $filesystem;
        $this->resource = $resource;
    }

    protected function useCustomTypes(): ?Closure
    {
        return function () {
            return [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'image/webp',
            ];
        };
    }

    /**
     * Has the file is image.
     * @return bool
     */
    public function hasImage(): bool
    {
        return $this->hasImageType(
            $this->getMimeType()
        );
    }

    /**
     * Get image file dimension.
     * @return \Zhiyi\Plus\FileStorage\ImageDimensionInterface
     */
    public function getImageDimension(): ImageDimensionInterface
    {
        if (! $this->hasImage()) {
            throw new Exception('调用的资源并非图片或者是不支持的图片资源');
        } elseif ($this->dimension instanceof ImageDimensionInterface) {
            return $this->dimension;
        };

        $realPath = $this->filesystem->path(
            $this->resource->getPath()
        );
        list($width, $height) = getimagesize($realPath);

        return $this->dimension = new ImageDimension((float) $width, (float) $height);
    }

    /**
     * Get the file size (Byte).
     * @return int
     */
    public function getSize(): int
    {
        return $this->filesystem->getSize(
            $this->resource->getPath()
        );
    }

    /**
     * Get the resource mime type.
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->filesystem->mimeType(
            $this->resource->getPath()
        );
    }

    /**
     * Get the resource pay info.
     * @param \Zhiyi\Plus\Models\User $user
     * @return \Zhiyi\Plus\FileStorage\Pay\PayInterface
     */
    public function getPay(User $user): ?PayInterface
    {
        return null;
    }

    /**
     * Get the storage vendor name.
     * @return string
     */
    public function getVendorName(): string
    {
        return 'local';
    }

    public function url(): string
    {
        return route('storage:local-get', [
            'channel' => $this->resource->getChannel(),
            'path' => base64_encode($this->resource->getPath()),
        ]);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [1, 2];
    }
}
