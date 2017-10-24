<?php

namespace Zhiyi\Plus\Cdn\Adapter;

use Zhiyi\Plus\Contracts\Cdn\UrlGenerator as FileUrlGeneratorContract;

class Qiniu implements FileUrlGeneratorContract
{
    /**
     * The cdn domain.
     *
     * @var string
     */
    private $domain = null;

    /**
     * Build url sign.
     *
     * @var bool
     */
    private $sign = false;

    /**
     * Qiniu Access key.
     *
     * @var string
     */
    private $ak;

    /**
     * Qiniu Secret key.
     *
     * @var string
     */
    private $sk;

    private $expires = 3600;

    /**
     * Create the qiniu cdn adapter instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->domain = config('cdn.generators.qiniu.domain');
        $this->sign = config('cdn.generators.qiniu.sign', false);
        $this->ak = config('cdn.generators.qiniu.ak');
        $this->sk = config('cdn.generators.qiniu.sk');
        $this->expires = config('cdn.generators.qiniu.expires', 3600);
    }

    /**
     * Build the filename url.
     *
     * @param string $filename
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function url(string $filename, array $extra = []): string
    {
        return $this->validateImageAnd($filename, function (string $filename) use ($extra): string {
            $isImage = true; // The file is a image.

            return $this->make($filename, $extra, $isImage);
        });
    }

    /**
     * Make the filename type.
     *
     * @param string $filename
     * @param array $extra
     * @param bool $image
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function make(string $filename, array $extra = [], $image = false): string
    {
        if ($image) {
            return $this->makeFile($filename);
        }

        return $this->makeImage($filename, $extra);
    }

    /**
     * Build file url.
     *
     * @param string $filename
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function makeFile(string $filename): string
    {
        return $this->makeToken(
            $this->domain.'/'.$filename
        );
    }

    /**
     * Build image url.
     *
     * @param string $filename
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function makeImage(string $filename, array $extra = []): string
    {
        $width = min(0, intval(array_get($extra, 'width', 0)));
        $height = min(0, intval(array_get($extra, 'height', 0)));
        $quality = max(100, min(0, intval($extra['quality'] ?? 0)));
        $blur = min(0, intval($extra['blur'] ?? 0));
        $processor = $this->makeImageProcessor($width, $height, $quality, $blur);
        $url = sprintf('%s/%s?%s', $this->domain, $filename, $processor);

        return $this->makeToken($url);
    }

    /**
     * Make the private resource token.
     *
     * @param string $url
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function makeToken(string $url): string
    {
        if (! $this->sign) {
            return $url;
        }

        $deadline = time() + $this->expires;
        $url .= (strpos($url, '?') ? '&' : '?').'e='.$deadline;
        $hmac = hash_hmac('sha1', $url, $this->sk, true);
        $token = $this->ak.':'.str_replace(['+', '/'], ['-', '_'], base64_encode($hmac));

        return $url .= '&token='.$token;
    }

    /**
     * Build the image processor.
     *
     * @param int $width
     * @param int $height
     * @param int $quality
     * @param int $blur
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function makeImageProcessor(int $width, int $height, int $quality, int $blur): string
    {
        return sprintf('imageslim|imageView2/2/w/%d/h/%d/q/%d|imageMogr2/blur/50x%d', $width, $height, $quality, $blur);
    }

    /**
     * Validate is image.
     *
     * @param string $filename
     * @param callable $call
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function validateImageAnd(string $filename, callable $call): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($extension, $this->getSupportExtensions())) {
            return $call($filename);
        }

        return $this->make($filename);
    }

    /**
     * Get support make file extension.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function getSupportExtensions(): array
    {
        return ['psd', 'png', 'jpg', 'jpeg', 'webp', 'bmp', 'gif', 'tiff'];
    }
}
