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

namespace Slimkit\PlusAppversion\Admin\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Support\Facades\Storage;
use Zhiyi\Plus\Models\File as FileModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Slimkit\PlusAppversion\Models\ClientVersion;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Slimkit\PlusAppversion\API\Requests\ApkUpload;
use Slimkit\PlusAppversion\Requests\StoreClientVersion;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class HomeController extends Controller
{
    public function storage(ApkUpload $request, ResponseContract $response, Carbon $dateTime, FileModel $fileModel, FileWithModel $fileWith)
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

    public function home(Request $request)
    {
        $user = $request->user();
        $token = JWTAuth::fromUser($user);

        return view('plus-appversion::admin', [
            'token' => $token,
        ]);
    }

    public function currentVersion(ClientVersion $clientVersionModel)
    {
        $version['android'] = $clientVersionModel->where('type', 'android')->orderBy('id', 'desc')->first() ?? null;
        $version['ios'] = $clientVersionModel->where('type', 'ios')->orderBy('id', 'desc')->first() ?? null;

        return response()->json($version)->setStatusCode(200);
    }

    /**
     * get the list of client versions.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  ClientVersion $versionModel
     * @return mixed
     */
    public function index(Request $request, ClientVersion $clientVersionModel)
    {
        $type = $request->query('type');
        $limit = $request->query('limit', 20);

        $versions = $clientVersionModel
        ->when($type, function ($query) use ($type) {
            return $query->where('type', $type);
        })
        ->orderBy('id', 'desc')
        ->paginate($limit);

        return response()->json($versions)->setStatusCode(200);
    }

    /**
     * create client version.
     *
     * @author bs<414606094@qq.com>
     * @param  StoreClientVersion $request
     * @param  ClientVersion      $clientVersionModel
     * @return mixed
     */
    public function store(StoreClientVersion $request, ClientVersion $clientVersionModel, FileWithModel $fileWith)
    {
        $clientVersionModel->type = $request->input('type');
        $clientVersionModel->version = $request->input('version');
        $clientVersionModel->description = $request->input('description');
        $clientVersionModel->link = $request->input('link');
        $clientVersionModel->version_code = $request->input('version_code');
        $clientVersionModel->is_forced = $request->input('is_forced');
        $storage = $request->input('storage');

        $clientVersionModel->save();

        if ($storage) {
            $file = $fileWith->find($storage);
            $file->channel = 'client:file:apk';
            $file->raw = $clientVersionModel->id;
            $file->save();
        }

        return response()->json($clientVersionModel, 201);
    }

    /**
     * delete a client version.
     *
     * @author bs<414606094@qq.com>
     * @param  ClientVersion $clientVersion
     * @return mixed
     */
    public function delete(ClientVersion $clientVersion)
    {
        $clientVersion->delete();

        return response()->json([], 204);
    }

    /**
     * 扩展开启/关闭状态
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function status()
    {
        $status = config('plus-appversion');

        return response()->json($status)->setStatusCode(200);
    }

    /**
     * 更新扩展开关状态
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request, Configuration $configuration)
    {
        $open = (bool) $request->input('open', false);
        $configuration->set('plus-appversion.open', $open);

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }
}
