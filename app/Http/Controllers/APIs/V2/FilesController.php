<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\File as FileModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class FilesController extends Controller
{
    public function show(Request $request)
    {
        dd($request);
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
