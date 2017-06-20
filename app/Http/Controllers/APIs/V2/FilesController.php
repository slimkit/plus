<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Zhiyi\Plus\Models\File as FileModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Cdn\UrlManager as CdnUrlManager;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\PayPublish as PayPublishModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Plus\Http\Requests\API2\StoreUploadFile as StoreUploadFileRequest;

class FilesController extends Controller
{
    /**
     * Get file.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Cdn\UrlManager $manager
     * @param \Zhiyi\Plus\Models\FileWith $fileWith
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseContract $response, CdnUrlManager $cdn, FileWithModel $fileWith)
    {
        $fileWith->load(['file', 'pay']);

        if ($fileWith->pay instanceof PayPublishModel) {
            $this->resolveUserPaid($request->user('api'), $fileWith->pay);
        }

        $extra = array_filter([
            'width' => $request->query('w'),
            'height' => $request->query('h'),
            'quality' => $request->query('q'),
        ]);
        $url = $cdn->make($fileWith->file, $extra);

        return $request->query('json') !== null
            ? $response->json(['url' => $url])->setStatusCode(200)
            : $response->redirectTo($url, 302);
    }

    /**
     * 解决用户是否购买过处理.
     *
     * @param \Zhiyi\Plus\Models\User|null $user
     * @param \Zhiyi\Plus\Models\PayPublish $pay
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveUserPaid($user, PayPublishModel $pay)
    {
        // 如果用户位空，则抛出认证错误.
        if ($user === null) {
            abort(401);
        } elseif // 检查用户是否购买过，购买过则跳过.
        ($user->payPublishes()->newPivotStatementForId($pay->id)->exists() === true) {
            return;
        }

        // 检查失败后抛出禁止访问异常.
        abort(403, '没有购买当前文件');
    }

    /**
     * 储存上传文件.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\StoreUploadFile $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Carbon\Carbon $dateTime
     * @param \Zhiyi\Plus\Models\File $fileModel
     * @param \Zhiyi\Plus\Models\FileWith $fileWith
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreUploadFileRequest $request, ResponseContract $response, Carbon $dateTime, FileModel $fileModel, FileWithModel $fileWith)
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

        $fileWith = $this->resolveFileWith($fileWith, $request->user(), $fileModel->id);

        return $response->json([
            'message' => '上传成功',
            'id' => $fileWith->id,
        ])->setStatusCode(201);
    }

    /**
     * Get or create a uploaded file with id.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\File $file
     * @param \Zhiyi\Plus\Models\FileWith $fileWith
     * @param string $hash
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function uploaded(Request $request, ResponseContract $response, FileModel $file, FileWithModel $fileWith, string $hash)
    {
        $file = $file->where('hash', strtolower($hash))->firstOr(['id'], function () {
            abort(404);
        });

        // 复用空类型数据～减少资源浪费.
        $fileWith = $this->resolveFileWith($fileWith, $request->user(), $file->id);

        return $response->json([
            'message' => ['success'],
            'id' => $fileWith->id,
        ])->setStatusCode(200);
    }

    /**
     * 验证病返回文件数据库模型实例.
     *
     * @param \Zhiyi\Plus\Models\File $fileModel
     * @param \Illuminate\Http\UploadedFile $file
     * @param callable $call
     * @return \Zhiyi\Plus\Models\File
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function validateFileInDatabase(FileModel $fileModel, UploadedFile $file, callable $call): FileModel
    {
        $hash = md5_file($file);

        return $fileModel->where('hash', $hash)->firstOr(['id'], function () use ($file, $call, $hash): FileModel {
            return call_user_func_array($call, [$file, $hash]);
        });
    }

    /**
     * 解决数据模型非实例.
     *
     * @param \Zhiyi\Plus\Models\FileWith $fileWith
     * @param \Zhiyi\Plus\Models\User $user
     * @param int $fileId
     * @return \Zhiyi\Plus\Models\FileWith
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveFileWith(FileWithModel $fileWith, UserModel $user, int $fileId): FileWithModel
    {
        return $fileWith->where('file_id', $fileId)
            ->where('user_id', $user->id)
            ->where('channel', null)
            ->where('raw', null)
            ->firstOr(function () use ($user, $fileWith, $fileId) {
                $fileWith->file_id = $fileId;
                $fileWith->channel = null;
                $fileWith->raw = null;
                $user->files()->save($fileWith);

                return $fileWith;
            });
    }
}
