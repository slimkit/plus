<?php

namespace Zhiyi\Plus\Cdn\Adapter;

use Zhiyi\Plus\Cdn\Refresh;
use Zhiyi\Plus\Models\File;
use GuzzleHttp\Client as HttpClient;
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

    const OSS_HTTP_DELETE = 'DELETE';

    const OSS_HTTP_POST = 'POST';

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
        $this->expires = config('cdn.generators.alioss.expires', 3600);
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
     * Refresh the cdn files and dirs.
     *
     * @param \Zhiyi\Plus\Cdn\Refresh $refresh
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function refresh(Refresh $refresh)
    {
        $client = new HttpClient();
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Delete></Delete>'); // xml格式批量删除
        $xml->addChild('Quiet', false);
        $date =  gmdate('D, d M Y H:i:s \G\M\T');

        foreach ($refresh->getDirs() as $dir) {
            $opjectxml = $xml->addChild('Object');
            $opjectxml->addChild('Key', $dir);
        }

        $content = $xml->asXML();

        $client->request('POST', $this->getBaseURI(), [
            'headers' => [
                'Host' => $this->endpoint,
                'Date' => $date,
                'Content-Length' => strlen($content),
                'Content-MD5' => base64_encode(md5($content, true)),
                'Authorization' => 'OSS '.$this->getHeaderSign(
                    $this->endpoint,
                    null,
                    $data,
                    self::OSS_HTTP_POST,
                    []
                )
            ],
        ]);
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
        $publicUrl = $this->makePublicURL($filename, $extra);

        return $publicUrl.$this->makeSign(
            $this->bucket,
            $filename,
            $this->expires, // 授权过期时间。
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

            $process = collect([
                'quality,q_%d' => [
                    'confirm' => (bool) $quality,
                    'params' => [$quality],
                ],
                'crop,w_%d,h_%d' => [
                    'confirm' => (bool) $width || (bool) $height,
                    'params' => [$width, $height],
                ],
                'blur,r_50,s_%d' => [
                    'confirm' => (bool) $blur,
                    'params' => [$blur],
                ],
            ])->map(function ($value, $key) {
                if (! $value['confirm']) {
                    return null;
                }

                return sprintf($key, ...$value['params']);
            })->filter()->implode('/');

            if (! $process) {
                return [];
            }

            return [
                self::OSS_PROCESS => 'image/'.$process,
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
        $params = collect($process)->map(function ($value, $key) {
            return $key.'='.$value;
        })->implode('&');

        $CanonicalizedResource = $bucket.'/'.$filename;
        if ($params) {
            $CanonicalizedResource = $CanonicalizedResource.'?'.$params;
        }
        $expireTime = time() + $timeout;
        $unsigndata = $method."\n\n\n".$expireTime."\n/".$CanonicalizedResource;

        $signature = urlencode(base64_encode(hash_hmac('sha1', $unsigndata, $this->accessKeySecret, true)));

        $stringToSign = $params ? '&OSSAccessKeyId=%s&Expires=%s&Signature=%s' : '?OSSAccessKeyId=%s&Expires=%s&Signature=%s';

        return sprintf($stringToSign, $this->accessKeyId, $expireTime, $signature);
    }

    /**
     * get sign for headers.
     *
     * @param string $bucket
     * @param string $filename
     * @param string $date
     * @param string $method
     * @param array $process
     * @return string
     * @author BS <414606094@qq.com>
     */
    protected function getHeaderSign(string $bucket, $filename = null, string $date, string $method = self::OSS_HTTP_GET, array $process) {

        $params = collect($process)->map(function ($value, $key) {
            return $key.'='.$value;
        })->implode('&');

        $CanonicalizedResource = $bucket;
        if ($filename) {
            $CanonicalizedResource = $CanonicalizedResource.'/'.$filename;
        }
        if ($params) {
            $CanonicalizedResource = $CanonicalizedResource.'?'.$params;
        }
        $unsigndata = $method."\n\n\n".$date."\n/".$CanonicalizedResource;

        $signature = urlencode(base64_encode(hash_hmac('sha1', $unsigndata, $this->accessKeySecret, true)));

        return $signature;
    }
}
