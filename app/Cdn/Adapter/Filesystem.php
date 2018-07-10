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

namespace Zhiyi\Plus\Cdn\Adapter;

use Zhiyi\Plus\Cdn\Refresh;
use Intervention\Image\Image;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;
use Zhiyi\Plus\Contracts\Cdn\UrlGenerator as FileUrlGeneratorContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactoryContract;

class Filesystem implements FileUrlGeneratorContract
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The Filesystem instance.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The filesystem disk.
     *
     * @var string
     */
    protected $disk = 'public';

    /**
     * Create the CDN generator.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app, FilesystemFactoryContract $files)
    {
        $this->app = $app;
        $this->files = $files->disk(
            $this->disk = config('cdn.generators.filesystem.disk')
        );
    }

    /**
     * Generator a URL.
     *
     * @param string $filename
     * @param array $extra
     * @throws \Exception
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function url(string $filename, array $extra = []): string
    {
        if ($this->files->exists($filename) === false) {
            throw new \Exception("Unable to find a file at path [{$filename}].");
        }

        return $this->validateImageAnd($filename, function (string $filename) use ($extra) {
            return $this->validateProcessAnd($filename, $extra, function (Image $image, array $extra = []) use ($filename) {
                if ($extra['blur']) {
                    $image->blur($extra['blur']);
                }

                $this->processSize($image, $extra);

                $quality = intval($extra['quality'] ?? 90) ?: 90;
                $quality = min($quality, 90);

                $image->encode($image->extension, $quality);

                return $this->putProcessFile(
                    $image,
                    $this->makeProcessFilename($filename, $this->makeProcessFingerprint($extra))
                );
            });
        });
    }

    /**
     * Refresh the cdn files and dirs.
     *
     * @param \Zhiyi\Plus\Cdn\Refresh $refresh
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function refresh(Refresh $refresh)
    {
        $this->files->delete($refresh->getFiles());
        foreach ($refresh->getDirs() as $dir) {
            if ($this->files->exists($dir)) {
                $this->files->deleteDirectory($dir);
            }
        }
    }

    /**
     * 保存转换后的文件并返回地址.
     *
     * @param \Intervention\Image\Image $image
     * @param string $filename
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function putProcessFile(Image $image, string $filename): string
    {
        if (! $image->isEncoded() || ! $this->files->put($filename, $image)) {
            throw new \Exception('The file encode error.');
        }

        return $this->makeUrl($filename);
    }

    /**
     * 处理文件尺寸.
     *
     * @param \Intervention\Image\Image $image
     * @param array $extra
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function processSize(Image $image, array $extra)
    {
        $width = $image->width();
        $height = $image->height();

        $processWidth = floatval($extra['width']);
        $processHeight = floatval($extra['height']);

        if (($width <= $processWidth || $height <= $processHeight) || (! $processWidth && ! $processHeight)) {
            return;
        }

        $minSide = min($processWidth, $processHeight);

        if (($minSide === $processWidth && $processWidth) || ((bool) $processWidth && ! $processHeight)) {
            $image->resize($processWidth, null, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
        } elseif (($minSide === $processHeight && $processWidth) || ((bool) $processHeight && ! $processWidth)) {
            $image->resize(null, $processHeight, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
        }
    }

    /**
     * 验证文件是否需要处理，如果需要则执行回调.
     *
     * @param string $filename
     * @param array $extra
     * @param callable $call
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function validateProcessAnd(string $filename, array $extra, callable $call): string
    {
        $width = floatval($extra['width'] ?? 0.0);
        $height = floatval($extra['height'] ?? 0.0);
        $quality = intval($extra['quality'] ?? 0);
        $blur = intval($extra['blur'] ?? 0);

        if (! $width && ! $height && ! $quality && ! $blur) {
            return $this->makeUrl($filename);
        }

        return $this->validateFingerprint($filename, $call, [
            'width' => $width,
            'height' => $height,
            'quality' => $quality,
            'blur' => $blur,
        ]);
    }

    /**
     * 验证文件指纹，如果指纹文件存在，则直接返回，否则执行回调.
     *
     * @param string $filename
     * @param callable $call
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function validateFingerprint(string $filename, callable $call, array $extra): string
    {
        $processFilename = $this->makeProcessFilename($filename, $this->makeProcessFingerprint($extra));

        if ($this->files->exists($processFilename)) {
            return $this->makeUrl($processFilename);
        }

        return $call(
            $this->makeImage($this->app['config']['filesystems.disks.public.root'].'/'.$filename),
            $extra
        );
    }

    /**
     * Make Image.
     *
     * @param string $filename
     * @return \Intervention\Image\Image
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeImage(string $filename): Image
    {
        return $this->app->make(ImageManager::class)->make($filename);
    }

    /**
     * 生成文件转换信息指纹.
     *
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeProcessFingerprint(array $extra): string
    {
        return md5(implode('|', array_filter($extra)));
    }

    /**
     * 生成转换后的文件路径.
     *
     * @param string $filename
     * @param string $fingerprint
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeProcessFilename(string $filename, string $fingerprint): string
    {
        $processPath = str_replace(sprintf('.%s', $ext = pathinfo($filename, PATHINFO_EXTENSION)), '/', $filename);

        return $processPath.$fingerprint.'.'.$ext;
    }

    /**
     * 获取支持的文件 mimeType.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getSupportMimeTypes(): array
    {
        $mimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
        ];

        if ($this->app['config']['image.driver'] === 'imagick') {
            return array_merge($mimes, [
                'image/tiff',
                'image/bmp',
                'image/x-icon',
                'image/vnd.adobe.photoshop',
            ]);
        }

        return $mimes;
    }

    /**
     * 验证是否是图片，如果是并且执行回调.
     *
     * @param string $filename
     * @param callable $call
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function validateImageAnd(string $filename, callable $call): string
    {
        if (in_array($this->files->mimeType($filename), $this->getSupportMimeTypes())) {
            return $call($filename);
        }

        return $this->makeUrl($filename);
    }

    /**
     * Make public URL.
     *
     * @param string $filename
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeUrl(string $filename): string
    {
        if ($this->disk === 'local') {
            return sprintf('%s/%s', config('cdn.generators.filesystem.public'), $filename);
        }

        return $this->files->url($filename);
    }
}
