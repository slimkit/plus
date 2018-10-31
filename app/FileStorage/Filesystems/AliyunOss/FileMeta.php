<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\FileStorage\Filesystems\AliyunOss;

use Closure;
use OSS\OssClient;
use OSS\Core\MimeTypes;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\FileStorage\ImageDimension;
use Zhiyi\Plus\FileStorage\FileMetaAbstract;
use Zhiyi\Plus\FileStorage\Pay\PayInterface;
use Zhiyi\Plus\FileStorage\ResourceInterface;
use Zhiyi\Plus\FileStorage\Traits\HasImageTrait;
use Zhiyi\Plus\FileStorage\ImageDimensionInterface;

class FileMeta extends FileMetaAbstract
{
    use HasImageTrait;

    protected $oss;
    protected $resource;
    protected $bucket;
    protected $dimension;
    protected $cachedMeta;

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

        $url = $this->oss->signUrl($this->bucket, $this->resource->getPath(), 3600, 'GET', [
            OssClient::OSS_PROCESS => 'image/info',
        ]);
        $result = file_get_contents($url);
        $json = json_decode($result, false);

        return $this->dimension = new ImageDimension(
            (float) $json->ImageWidth->value,
            (float) $json->ImageHeight->value
        );
    }

    /**
     * Get the file size (Byte).
     * @return int
     */
    public function getSize(): int
    {
        if (! $this->cachedMeta) {
            $this->cachedMeta = $this->oss->getObjectMeta($this->bucket, $this->resource->getPath());
        }

        return (int) $this->cachedMeta['content-length'];
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
}
