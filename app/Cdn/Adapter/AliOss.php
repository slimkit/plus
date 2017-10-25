<?php

namespace Zhiyi\Plus\Cdn\Adapter;

use Zhiyi\Plus\Models\File;
use Zhiyi\Plus\Contracts\Cdn\UrlGenerator as FileUrlGeneratorContract;

class AliOss implements FileUrlGeneratorContract
{
    protected $accessKeyId; // 从OSS获得的AccessKeyId

    protected $accessKeySecret; // 从OSS获得的AccessKeySecret

    protected $bucket;

    protected $endpoint; // 选定的OSS数据中心访问域名，例如oss-cn-hangzhou.aliyuncs.com

    protected $ssl = false; // 是否使用 SSL?默认否

    protected $public = true; // 是否公有读？默认是。

    protected $client; // The AliyunOSS client "\OSS\OssClient".

    const OSS_PROCESS = 'x-oss-process';

    const OSS_HTTP_GET = 'GET';

    /**
     * 构造方法，初始化 AliyunOSS 基本信息.
     *
     * @param \Zhiyi\Plus\Services\Storage $service
     * @param string $key
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->accessKeyId = config('cdn.generators.alioss.AccessKeyId');
        $this->accessKeySecret = config('cdn.generators.alioss.AccessKeySecret');
        $this->bucket = config('cdn.generators.alioss.bucket');
        $this->endpoint = config('cdn.generators.alioss.endpoint');
        $this->ssl = config('cdn.generators.alioss.ssl', false);
        $this->public = config('cdn.generators.alioss.public', true);
    }

    /**
     * Get resource URL.
     *
     * @param string $filename
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function url(string $filename, array $extra = []):string
    {
        // 过滤目录分隔符.
        $filename = $this->filterSlash($filename);

        if ($this->public === false) {
            //
            return $this->makeSignURL($filename, $extra);
        }

        return $this->makePublicURL($filename, $extra);
    }

    /**
     * make private url.
     *
     * @param string $filename
     * @param array $extra
     * @return string
     * @author BS <414606094@qq.com>
     */
    protected function makeSignURL(string $filename, array $extra): string
    {
        return $this->makePublicURL($filename, $extra).$this->makeSign(
            $this->bucket,
            $filename,
            3600, // 获取签字路径，一小时有效期，一小时之内都可以重复使用。
            self::OSS_HTTP_GET, // 获取资源
            $this->getProcess($filename, $extra)
        );
    }

    /**
     * Make public object URL.
     *
     * @param string $filename
     * @param array $extra
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makePublicURL(string $filename, array $extra): string
    {
        return $this->resolveQueryString(
            sprintf('%s/%s', $this->getBaseURI(), $filename),
            $this->getProcess($filename, $extra)
        );
    }

    /**
     * 拼接图片操作参数.
     *
     * @param string $filename
     * @param array $extra
     * @return array
     * @author BS <414606094@qq.com>
     */
    protected function getProcess(string $filename, array $extra): array
    {
        if ($this->isImage($filename)) {
            $width = max(0, intval(array_get($extra, 'width', 0)));
            $height = max(0, intval(array_get($extra, 'height', 0)));
            $quality = min(100, max(0, intval($extra['quality'] ?? 0)));
            $blur = max(0, intval($extra['blur'] ?? 0));

            return [
                self::OSS_PROCESS => sprintf('image/format,jpg/quality,q_%d/crop,w_%d,h_%d/blur,r_%d,s_%d', $quality, $width, $height, $blur, $blur),
            ];
        }

        return [];
    }

    /**
     * Check file not image file.
     *
     * @param string $filename
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function isImage(string $filename): bool
    {
        return in_array(app('files')->extension($filename), ['psd', 'png', 'jpg', 'jpeg', 'webp', 'bmp', 'gif', 'tiff']);
    }

    /**
     * 解析请求地址和请求参数，构造新的正确请求地址返回.
     *
     * @param string $url
     * @param array $query
     * @throws \Exception
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveQueryString(string $url, array $query = []): string
    {
        // -1 表示获取全部。
        $pares = parse_url($url, -1);
        if ($pares === false) {
            throw new \Exception('Parse URL error.');
        }
        $scheme = array_get($pares, 'scheme', $this->getScheme());
        $host = array_get($pares, 'host', '');
        $path = array_get($pares, 'path', '');
        $query = $this->mergeHttpQueryString(
            array_get($pares, 'query', ''),
            http_build_query($query)
        );

        return sprintf('%s://%s%s%s', $scheme, $host, $path, $query);
    }

    /**
     * Merge http query string.
     *
     * @param string $query1 [description]
     * @param string $query2 [description]
     * @return [type] [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function mergeHttpQueryString(string $query1 = '', string $query2 = ''): string
    {
        // default query string.
        $query = $query1 ?: '';
        if ($query1 && $query2) {
            $query = sprintf('%s&%s', $query1, $query2);
        } elseif ($query2) {
            $query = $query2;
        }

        return $query ? '?'.$query : $query;
    }

    /**
     * Filter the slash.
     *
     * @param string $filename
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function filterSlash(string $filename): string
    {
        // 将 windows 系统目录分隔符也去除.
        $filename = str_replace('\\', '/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
        // 删除首位目录分隔符，否则 OSS 报错.
        if (strpos($filename, '/') === 0) {
            return substr($filename, strlen('/'));
        }
        // 如果还存在，则递归调用过滤。
        return strpos($filename, '/') === 0
            ? $this->filterSlash($filename)
            : $filename;
    }

    /**
     * Get base URI.
     *
     * If not $endpoint param, Use $this->endpoint
     *
     * @param string $endpoint
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getBaseURI(string $endpoint = ''): string
    {
        $endpoint = $endpoint ?: $this->endpoint;
        // 去除协议头，保留 hostname 部分。
        if (strpos($endpoint, 'http://') === 0) {
            $endpoint = substr($endpoint, strlen('http://'));
        } elseif (strpos($endpoint, 'https://') === 0) {
            $endpoint = substr($endpoint, strlen('https://'));
            // 如果是携带了 https 协议头，强制使用 SSL，忽略后台设置。
            $this->ssl = true;
        }

        return sprintf('%s://%s', $this->getScheme(), $endpoint);
    }

    /**
     * Get scheme.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getScheme(): string
    {
        if ($this->ssl === true) {
            return 'https';
        }

        return 'http';
    }

    /**
     * sign url.
     *
     * @param string $bucket
     * @param string $filename
     * @param int $timeout
     * @param string $method
     * @param array $process
     * @return string
     * @author BS <414606094@qq.com>
     */
    protected function makeSign(string $bucket, string $filename, int $timeout = 60, string $method = self::OSS_HTTP_GET, array $process)
    {
        $CanonicalizedResource = $bucket.'/'.$filename.'?'.http_build_query($process);
        $filedata = $this->getDataFromFilename($filename);
        $unsigndata = $method.'\n'.$filedata['hash'].'\n'.$filedata['mime'].'\n'.time() + $timeout.$CanonicalizedResource;

        $signature = urlencode(base64(hash_hmac('sha1', $unsigndata, $this->accessKeySecret, true)));

        return sprintf('&OSSAccessKeyId=%s&Expires=%s&Signature=%s', $this->accessKeyId, time() + $timeout, $signature);
    }

    /**
     * get data from filename.
     *
     * @param $filename
     * @return array
     * @author BS <414606094@qq.com>
     */
    protected function getDataFromFilename(string $filename)
    {
        $fileModel = new File();
        if ($file = $fileModel->where('filename', $filename)->first()) {
            return $file;
        }

        throw new \InvalidArgumentException('The file has no record in database.');
    }
}
