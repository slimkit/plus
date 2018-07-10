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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\AdminControllers;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Zhiyi\Plus\Models\File as FileModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\MusicUploadFile as MusicUploadFileRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\SpecialUploadFile as SpecialUploadFileRequest;

class MusicStorageController extends Controller
{
    public function store(MusicUploadFileRequest $request, ResponseContract $response, Carbon $dateTime, FileModel $fileModel, FileWithModel $fileWith)
    {
        $fileModel = $this->validateFileInDatabase($fileModel, $file = $request->file('file'), function (UploadedFile $file, string $md5) use ($fileModel, $dateTime): FileModel {
            $path = $dateTime->format('Y/m/d/Hi');

            if (($filename = $file->store($path, 'public')) === false) {
                abort(500, '上传失败');
            }

            $fileModel->filename = $filename;
            $fileModel->hash = $md5;
            $fileModel->origin_filename = $file->getClientOriginalName();
            $fileModel->mime = $file->getClientMimeType();
            $fileModel->width = 0;
            $fileModel->height = 0;
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
     * Validate and return the file database model instance.
     *
     * @param \Zhiyi\Plus\Models\File $fileModel
     * @param \Illuminate\Http\UploadedFile $file
     * @param callable $call
     * @return \Zhiyi\Plus\Models\File
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
     * @param \Zhiyi\Plus\Models\FileWith $fileWith
     * @param \Zhiyi\Plus\Models\User $user
     * @param \Zhiyi\Plus\Models\File $file
     * @return \Zhiyi\Plus\Models\FileWith
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveFileWith(FileWithModel $fileWith, UserModel $user, FileModel $file): FileWithModel
    {
        return $fileWith->where('file_id', $file->id)
            ->where('user_id', $user->id)
            ->where('channel', null)
            ->where('raw', null)
            ->firstOr(function () use ($user, $fileWith, $file) {
                $fileWith->file_id = $file->id;
                $fileWith->channel = null;
                $fileWith->raw = null;
                $fileWith->size = ($size = sprintf('%sx%s', $file->width, $file->height)) === 'x' ? null : $size;
                $user->files()->save($fileWith);

                return $fileWith;
            });
    }

    /**
     * 专辑/歌手附件上传.
     * @param  SpecialUploadFileRequest $request   [description]
     * @param  ResponseContract         $response  [description]
     * @param  Carbon                   $dateTime  [description]
     * @param  FileModel                $fileModel [description]
     * @param  FileWithModel            $fileWith  [description]
     * @return [type]                              [description]
     */
    public function specialStorage(SpecialUploadFileRequest $request, ResponseContract $response, Carbon $dateTime, FileModel $fileModel, FileWithModel $fileWith)
    {
        $fileModel = $this->validateFileInDatabase($fileModel, $file = $request->file('file'), function (UploadedFile $file, string $md5) use ($fileModel, $dateTime): FileModel {
            list($width, $height) = ($imageInfo = @getimagesize($file->getRealPath())) === false ? [null, null] : $imageInfo;
            $path = $dateTime->format('Y/m/d/Hi');

            if (($filename = $file->store($path, 'public')) === false) {
                abort(500, '上传失败');
            }

            $fileModel->filename = $filename;
            $fileModel->hash = $md5;
            $fileModel->origin_filename = $file->getClientOriginalName();
            $fileModel->mime = $file->getClientMimeType();
            $fileModel->width = $width;
            $fileModel->height = $height;
            $fileModel->saveOrFail();

            return $fileModel;
        });

        $fileWith = $this->resolveFileWith($fileWith, $request->user(), $fileModel);

        return $response->json([
            'message' => ['上传成功'],
            'id' => $fileWith->id,
        ])->setStatusCode(201);
    }
}
