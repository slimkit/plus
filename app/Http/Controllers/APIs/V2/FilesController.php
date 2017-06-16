<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\File as FileModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\PayPublish as PayPublishModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class FilesController extends Controller
{
    /**
     * Get file.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\FileWith $fileWith
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, FileWithModel $fileWith)
    {
        $fileWith->load(['file', 'pay']);

        if ($fileWith->pay instanceof PayPublishModel) {
            $this->resolveUserPaid($request->user('api'), $fileWith->pay);   
        }

        dd($fileWith->pay);
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
        } else

        // 检查用户是否购买过，购买过则跳过.
        if ($user->payPublishes()->newPivotStatementForId($pay->id)->exists() === true) {
            return;
        }

        // 检查失败后抛出禁止访问异常.
        abort(403, '没有购买当前文件');
    }

    public function store()
    {
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
        $file = $file->where('hash', $hash)->firstOr(['id'], function () {
            abort(404);
        });

        // 复用空类型数据～减少资源浪费.
        $fileWith = $fileWith->where('file_id', $file->id)
            ->where('user_id', $request->user()->id)
            ->where('channel', null)
            ->where('raw', null)
            ->firstOr(['id'], function () use ($file, $request, $fileWith) {
                $fileWith->file_id = $file->id;
                $fileWith->channel = null;
                $fileWith->raw = null;
                $request->user()->files()->save($fileWith);

                return $fileWith;
            });

        return $response->json([
            'message' => ['success'],
            'id' => $fileWith->id,
        ])->setStatusCode(200);
    }
}
