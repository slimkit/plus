<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\FileStorage\Filesystems\AliyunOss;

use Closure;
use OSS\Core\MimeTypes;
use OSS\OssClient;
use Zhiyi\Plus\FileStorage\FileMetaAbstract;
use Zhiyi\Plus\FileStorage\ImageDimension;
use Zhiyi\Plus\FileStorage\ImageDimensionInterface;
use Zhiyi\Plus\FileStorage\Pay\PayInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\Traits\HasImageTrait;
use Zhiyi\Plus\Models\User;

class FileMeta extends FileMetaAbstract
{
    use HasImageTrait;

    protected $oss;
    protected $resource;
    protected $bucket;
    protected $dimension;
    protected $metaData;

    /**
     * Create a file meta.
     * @param \OSS\OssClient $oss
     * @param \Zhiyi\Plus\FileStorage\ResourceInterface $resource
     * @param string $bucket
     */
    public function __construct(OssClient $oss, ResourceInterface $resource, string $bucket)
    {
        $this->oss = $oss;
        $this->resource = $resource;
        $this->bucket = $bucket;
        $this->getSize();
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
        }

        $meta = $this->getFileMeta();

        return new ImageDimension(
            (float) $meta->ImageWidth->value,
            (float) $meta->ImageHeight->value
        );
    }

    /**
     * Get the file size (Byte).
     * @return int
     */
    public function getSize(): int
    {
        $meta = $this->getFileMeta();

        return (int) ($meta->FileSize->value ?? $meta->{'content-length'});
    }

    /**
     * Get the resource mime type.
     * @return string
     */
    public function getMimeType(): string
    {
        return MimeTypes::getMimetype($this->resource->getPath()) ?: 'application/octet-stream';
    }

    /**
     * Get the storage vendor name.
     * @return string
     */
    public function getVendorName(): string
    {
        return 'aliyun-oss';
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
     * Get the resource url.
     * @return string
     */
    public function url(): string
    {
        return route('storage:get', [
            'channel' => $this->resource->getChannel(),
            'path' => base64_encode($this->resource->getPath()),
        ]);
    }

    /**
     * Custom using MIME types.
     * @return null\Closure
     */
    protected function useCustomTypes(): ?Closure
    {
        return function () {
            return [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'image/tiff',
                'image/webp',
            ];
        };
    }

    protected function getFileMeta(): object
    {
        if (! $this->metaData) {
            if (! $this->hasImage()) {
                $this->metaData = Cache::rememberForever((string) $this->resource, function () {
                    return (object) $this->oss->getObjectMeta($this->bucket, $this->resource->getPath());
                });
            }

            $this->metaData = Cache::rememberForever((string) $this->resource, function () {
                $url = $this->oss->signUrl($this->bucket, $this->resource->getPath(), 3600, 'GET', [
                    OssClient::OSS_PROCESS => 'image/info',
                ]);
                $result = file_get_contents($url);

                return json_decode($result, false);
            });
        }

        return $this->metaData;
    }
}
