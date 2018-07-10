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

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\Certification as CertificationModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Zhiyi\Plus\Http\Requests\API2\UserCertification as UserCertificationRequest;

class UserCertificationController extends Controller
{
    /**
     * Get a user certification.
     *
     * @param \Illuminate\Http\Request $request [description]
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response [description]
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        if (! $user->certification) {
            return $response->json($user->certification, 200);
        }
        $info = $user->certification->data;
        $fileInfo = [];
        foreach ($info['files'] as $key => $file) {
            $file = FileWithModel::where('id', $file)->with('file')->first();
            $fileInfo[$key] = new \stdClass();
            $fileInfo[$key]->file = $file->id;
            $fileInfo[$key]->size = $file->size;
            $fileInfo[$key]->mime = $file->file->mime;
        }
        $fileInfo = collect(array_values($fileInfo));
        $user->certification->files = $fileInfo;

        return $response->json($user->certification, 200);
    }

    /**
     * Send certification.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\UserCertification $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\Certification $certification
     * @param \Zhiyi\Plus\Models\FileWith $fileWithModel
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(
        UserCertificationRequest $request,
        ResponseFactoryContract $response,
        CertificationModel $certification,
        FileWithModel $fileWithModel
    ) {
        $user = $request->user();
        $type = $request->input('type');
        $data = $request->only(['name', 'phone', 'number', 'desc']);
        $files = $this->findNotWithFileModels($request, $fileWithModel);

        $data['files'] = $files->pluck('id');
        if ($type === 'org') {
            $data = array_merge($data, $request->only(['org_name', 'org_address']));
        }

        $certification->certification_name = $type;
        $certification->data = $data;
        $certification->status = 0;

        return $certification->getConnection()->transaction(function () use ($user, $files, $certification, $response) {
            $files->each(function ($file) use ($user) {
                $file->channel = 'certification:file';
                $file->raw = $user->id;
                $file->save();
            });
            $user->certification()->save($certification);

            return $response->json(['message' => '提交成功，等待审核'])->setStatusCode(201);
        });
    }

    /**
     * Update certification.
     *
     * @param \Zhiyi\Plus\Http\Requests\API2\UserCertification $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\FileWith $fileWithModel
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(
        UserCertificationRequest $request,
        ResponseFactoryContract $response,
        FileWithModel $fileWithModel
    ) {
        $user = $request->user();
        $type = $request->input('type');
        $certification = $user->certification;

        if ($certification->status === 1) {
            return $response->json(['message' => '已审核通过，无法修改'], 422);
        }

        $updateData = $request->only(['name', 'phone', 'number', 'desc']);
        if ($type === 'org') {
            $updateData = array_merge($updateData, $request->only(['org_name', 'org_address']));
        }

        $files = $this->findNotWithFileModels($request, $fileWithModel);
        $fileIds = array_values(
            array_filter((array) $request->input('files', []))
        );

        if (! empty($fileIds)) {
            $updateData['files'] = $fileIds;
        }

        $certification->certification_name = $type ?: $certification->certification_name;
        $certification->data = array_merge($certification->data, array_filter($updateData));
        $certification->status = 0;

        return $user->getConnection()->transaction(function () use ($user, $files, $certification, $response) {
            $files->each(function ($file) use ($user) {
                $file->channel = 'certification:file';
                $file->raw = $user->id;
                $file->save();
            });
            $certification->save();

            return $response->json(['message' => '提交成功，等待审核'], 201);
        });
    }

    /**
     * File not with file models.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\FileWith $fileWithModel
     * @return \Illuminate\Support\Collection
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function findNotWithFileModels(Request $request, FileWithModel $fileWithModel): Collection
    {
        $files = new Collection(
            array_filter((array) $request->input('files', []))
        );

        if ($files->isEmpty()) {
            return $files;
        }

        return $fileWithModel->where('channel', null)
            ->where('raw', null)
            ->whereIn('id', $files)
            ->get();
    }
}
