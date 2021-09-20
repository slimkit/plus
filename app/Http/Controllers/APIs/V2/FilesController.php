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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Cache;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Image;
use Zhiyi\Plus\Cdn\UrlManager as CdnUrlManager;
use Zhiyi\Plus\Http\Requests\API2\StoreUploadFile as StoreUploadFileRequest;
use Zhiyi\Plus\Models\File as FileModel;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Zhiyi\Plus\Models\User as UserModel;

class FilesController extends Controller
{
    /**
     * Get file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param  CdnUrlManager  $cdn
     * @param  \Zhiyi\Plus\Models\FileWith  $fileWith
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseContract $response, CdnUrlManager $cdn, FileWithModel $fileWith)
    {
        $fileWith->load(['file', 'paidNode']);
        $user = $request->user('api');
        $extra = array_filter([
            'width' => $request->query('w'),
            'height' => $request->query('h'),
            'quality' => $request->query('q'),
            'blur' => $request->query('b'),
        ]);

        if (($fileWith->paidNode instanceof PaidNodeModel &&
            $fileWith->paidNode->paid($user->id ?? 0) === false) &&
            ($fileWith->paidNode->extra === 'read' || (! isset($extra['width']) && isset($extra['height'])))
        ) {
            $extra['blur'] = (int) config('image.blur', 96);
        }

        $url = Cache::remember(sprintf('file_url_%d_%s', $fileWith->id, implode('_', $extra)), 50, function () use ($fileWith, $cdn, $extra) {
            return $cdn->make($fileWith->file, $extra);
        });

        return $request->query('json') !== null
            ? $response->json(['url' => $url])->setStatusCode(200)
            : $response->redirectTo($url, 302);
    }

    /**
     * 解决用户是否购买过处理.
     *
     * @param  \Zhiyi\Plus\Models\User|null  $user
     * @param  PaidNodeModel  $node
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveUserPaid($user, PaidNodeModel $node): bool
    {
        return $node->paid($user->id ?? 0);
    }

    /**
     * 储存上传文件.
     *
     * @param  \Zhiyi\Plus\Http\Requests\API2\StoreUploadFile  $request
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param  \Carbon\Carbon  $dateTime
     * @param  \Zhiyi\Plus\Models\File  $fileModel
     * @param  \Zhiyi\Plus\Models\FileWith  $fileWith
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreUploadFileRequest $request, ResponseContract $response, Carbon $dateTime, FileModel $fileModel, FileWithModel $fileWith)
    {
        $clientHeight = $request->input('height', 0);
        $clientWidth = $request->input('width', 0);
        $fileModel = $this->validateFileInDatabase($fileModel, $file = $request->file('file'), function (UploadedFile $file, string $md5) use ($fileModel, $dateTime, $clientWidth, $clientHeight, $response): FileModel {
            // 图片做旋转处理
            $clientMimeType = $file->getClientMimeType();
            if (Str::startsWith($clientMimeType, 'image/') && $clientMimeType !== 'image/gif') {
                ini_set('memory_limit', '-1');
                Image::make($file->getRealPath())->orientate()->save($file->getRealPath(), 100);
            }
            [$width, $height] = ($imageInfo = @getimagesize($file->getRealPath())) === false ? [null, null] : $imageInfo;
            $path = $dateTime->format('Y/m/d/Hi');
            if (($filename = $file->store($path, config('cdn.generators.filesystem.disk'))) === false) {
                return $response->json(['message' => '上传失败'], 500);
            }
            $fileModel->filename = $filename;
            $fileModel->hash = $md5;
            $fileModel->origin_filename = $file->getClientOriginalName();
            $fileModel->mime = $file->getClientMimeType();
            $fileModel->width = $width ?? $clientWidth;
            $fileModel->height = $height ?? $clientHeight;
            $fileModel->saveOrFail();

            return $fileModel;
        });
        $fileWith = $this->resolveFileWith($fileWith, $request->user(), $fileModel);

        return $response->json([
            'message' => ['上传成功'],
            'id' => $fileWith->id,
        ])->setStatusCode(201);
    }

    /**
     * Get or create a uploaded file with id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param  \Zhiyi\Plus\Models\File  $file
     * @param  \Zhiyi\Plus\Models\FileWith  $fileWith
     * @param  string  $hash
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function uploaded(Request $request, ResponseContract $response, FileModel $file, FileWithModel $fileWith, string $hash)
    {
        $file = $file->where('hash', strtolower($hash))->firstOr(function () {
            abort(404);
        });

        // 复用空类型数据～减少资源浪费.
        $fileWith = $this->resolveFileWith($fileWith, $request->user(), $file);

        return $response->json([
            'message' => ['success'],
            'id' => $fileWith->id,
        ])->setStatusCode(200);
    }

    /**
     * Validate and return the file database model instance.
     *
     * @param  \Zhiyi\Plus\Models\File  $fileModel
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  callable  $call
     * @return \Zhiyi\Plus\Models\File
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateFileInDatabase(FileModel $fileModel, UploadedFile $file, callable $call): FileModel
    {
        $hash = md5_file($file->getRealPath());

        return $fileModel->where('hash', $hash)->firstOr(function () use ($file, $call, $hash): FileModel {
            return call_user_func_array($call, [$file, $hash]);
        });
    }

    /**
     * 解决数据模型非实例.
     *
     * @param  \Zhiyi\Plus\Models\FileWith  $fileWith
     * @param  \Zhiyi\Plus\Models\User  $user
     * @param  \Zhiyi\Plus\Models\File  $file
     * @return \Zhiyi\Plus\Models\FileWith
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveFileWith(FileWithModel $fileWith, UserModel $user, FileModel $file): FileWithModel
    {
        $fileWith->file_id = $file->id;
        $fileWith->channel = null;
        $fileWith->raw = null;
        $fileWith->size = ($size = sprintf('%sx%s', $file->width, $file->height)) === 'x' ? null : $size;
        $user->files()->save($fileWith);

        return $fileWith;
    }
}
