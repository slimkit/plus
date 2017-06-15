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

    public function uploaded(Request $request, ResponseContract $response, FileModel $file, string $hash)
    {
        $file = $file->where('hash', $hash)->firstOr(['id'], function () {
            abort(404);
        });

        $fileWith = new FileWithModel();
        $fileWith->file_id = $file->id;

        $request->user()->files()->save($fileWith);
    }
}
