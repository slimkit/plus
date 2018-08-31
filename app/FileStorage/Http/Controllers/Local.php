<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage\Http\Controllers;

use function Zhiyi\Plus\setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Zhiyi\Plus\FileStorage\Resource;
use Zhiyi\Plus\FileStorage\StorageInterface;
use Illuminate\Contracts\Cache\Factory as FactoryContract;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Local extends Controller
{
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this
            ->middleware('signed')
            ->only(['put'/* , 'show' */]);
        $this
            ->middleware('auth:api')
            ->only(['put']);
        
        $this->storage = $storage;
    }

    public function get(Request $request, string $channel, string $path)
    {
        $resource = new Resource($channel, base64_decode($path));
        $rule = $request->query('rule', null);
        // $meta = $this->storage->meta($resource);
        // dd(
        //     [
        //         'size' => $meta->getSize(),
        //         'mime' => $meta->getMimeType(),
        //         'dimension' => [
        //             'width' => $meta->getImageDimension()->getWidth(),
        //             'height' => $meta->getImageDimension()->getHeight(),
        //         ]
        //     ]
        // );
    }

    public function put(Request $request, FactoryContract $cache, string $channel, string $path): Response
    {
        $signature = $request->query('signature');
        if ($cache->has($signature)) {
            throw new AccessDeniedHttpException('未授权的非法访问');
        }

        $contentHash = md5($content = $request->getContent());
        $hash = $request->header('x-plus-storage-hash');
        if ($hash !== $contentHash) {
            throw new UnprocessableEntityHttpException('Hash 校验失败');
        }

        $resource = new Resource($channel, base64_decode($path));
        if (! $this->storage->put($resource, $content)) {
            throw new HttpException('储存文件失败');
        }

        $this->storage->callback($resource);
        $expiresAt = (new Carbon)->addHours(
            setting('core', 'file:put-signature-expires-at', 1)
        );
        $cache->put($signature, 1, $expiresAt);
        $this->guard()->invalidate();

        return new Response('', Response::HTTP_NO_CONTENT);
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
