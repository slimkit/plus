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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\Certification;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Http\Requests\API2\UserCertification;

class CertificationController extends Controller
{
    /**
     * certification list.
     *
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $limit = (int) $request->get('limit');
        $offset = (int) $request->get('offset');
        $status = $request->get('status');
        $keyword = $request->get('keyword');
        $name = $request->get('certification_name');

        $query = Certification::when(! is_null($keyword), function ($query) use ($keyword) {
            $query->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'like', sprintf('%%%s%%', $keyword));
            });
        })
        ->when($name, function ($query) use ($name) {
            $query->where('certification_name', $name);
        })
        ->when(! is_null($status), function ($query) use ($status) {
            $query->where('status', $status);
        });

        $total = $query->count('id');
        $items = $query->orderBy('updated_at', 'desc')
        ->limit($limit)
        ->offset($offset)
        ->get();

        $data['items'] = $items;
        $data['counts'] = $this->certificationCount();

        return response()->json($data, 200, ['x-certifications-total' => $total]);
    }

    /**
     * 认证统计.
     *
     * @return array
     */
    protected function certificationCount()
    {
        $counts = \DB::select('
            SELECT 
                COUNT(status) AS `全部认证用户：`,
                COUNT(CASE WHEN status=0 THEN 1 ELSE NULL END ) AS `待审核用户：`,
                COUNT(CASE WHEN status=1 THEN 1 ELSE NULL END ) AS `已认证用户：`,
                COUNT(CASE WHEN status=2 THEN 1 ELSE NULL END ) AS `驳回用户：` 
            FROM `certifications`'
        );

        return $counts;
    }

    /**
     * certifiction pass.
     *
     * @param certification $certification
     * @return \Illuminate\Http\JsonResponse
     */
    public function passCertification(Request $request, Certification $certification)
    {
        $desc = $request->input('desc');

        if (! $desc) {
            return response()->json(['message' => ['请填写通过描述']], 422);
        }

        $data = $certification->data;
        $data['desc'] = $desc;

        $certification->data = $data;
        $certification->status = 1;
        $certification->examiner = Auth::user()->id;
        $certification->save();

        $certification->user->sendNotifyMessage('user-certification:pass', '你申请的身份认证已被通过', [
            'certification' => $certification,
        ]);

        return response()->json(['message' => ['通过认证成功']], 201);
    }

    /**
     * certifiction reject.
     *
     * @param Request $request
     * @param Certification $certification
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectCertification(Request $request, Certification $certification)
    {
        $content = $request->input('reject_content');

        if ($content === null || ! $content) {
            return response()->json(['message' => ['请填写驳回理由']], 422);
        }

        $data = $certification->data;
        $data['reject_content'] = $content;
        $certification->data = $data;
        $certification->status = 2;
        $certification->examiner = Auth::user()->id;

        if ($certification->save()) {
            $certification->user->sendNotifyMessage('user-certification:reject', sprintf('你申请的身份认证已被驳回，驳回理由为%s', $content), [
                'certification' => $certification,
            ]);

            return response()->json(['message' => ['驳回成功']], 201);
        } else {
            return response()->json(['message' => ['驳回失败，请稍后再试']], 500);
        }
    }

    /**
     * get certification detail.
     *
     * @param Certification $certification
     * @return $this
     */
    public function show(Certification $certification)
    {
        return response()->json($certification)->setStatusCode(200);
    }

    /**
     * update user certification.
     *
     * @param UserCertification $request
     * @param Certification $certification
     * @param FileWithModel $fileWithModel
     * @return mixed
     */
    public function update(
        Request $request,
        Certification $certification,
        FileWithModel $fileWithModel
    ) {
        $this->validate($request, $this->rules($request), $this->messages($request));

        $request->all();
        $type = $request->input('type');
        $certification = $certification;

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

        $certification->data = array_merge($certification->data, array_filter($updateData));
        $certification->status = 1;
        $certification->certification_name = $type;

        return $certification->getConnection()->transaction(function () use ($files, $type, $certification) {
            $files->each(function ($file) use ($certification) {
                $file->channel = 'certification:file';
                $file->raw = $certification->user_id;
                $file->save();
            });
            $certification->save();

            return response()->json(['message' => ['修改成功']], 201);
        });
    }

    public function rules(Request $request): array
    {
        if (strtolower($request->getMethod()) === 'patch') {
            return $this->updateRules($request);
        }

        $baseRules = [
            'user_id' => ['bail', 'required', 'numeric'],
            'type' => ['bail', 'required', 'string', 'in:user,org'],
            'name' => ['bail', 'required', 'string'],
            'phone' => ['bail', 'required', 'string', 'cn_phone'],
            'number' =>['bail', 'required', 'string'],
            'desc' => ['bail', 'required', 'string'],
            'files' => 'bail|required|array',
            'files.*' => 'bail|required_with:files|integer|exists:file_withs,id,channel,NULL,raw,NULL',
        ];

        if ($request->input('type') === 'org') {
            return array_merge($baseRules, [
                'org_name' => ['bail', 'required', 'string'],
                'org_address' => ['bail', 'required', 'string'],
            ]);
        }

        return $baseRules;
    }

    public function updateRules(Request $request): array
    {
        $baseRules = [
            'type' => ['bail', 'required', 'nullable', 'string', 'in:user,org'],
            'name' => ['bail', 'required', 'nullable', 'string'],
            'phone' => ['bail', 'required', 'nullable', 'string', 'cn_phone'],
            'number' =>['bail', 'required', 'nullable', 'string'],
            'desc' => ['bail', 'required', 'nullable', 'string'],
            'files' => 'bail|required|nullable|array',
            'files.*' => 'bail|required_with:files|integer|exists:file_withs,id',
        ];

        if ($request->input('type') === 'org') {
            return array_merge($baseRules, [
                'org_name' => ['bail', 'required', 'nullable', 'string'],
                'org_address' => ['bail', 'required', 'nullable', 'string'],
            ]);
        }

        return $baseRules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => '姓名未提供',
            'name.min' => '姓名太短',
            'name.max' => '姓名太长',
            'user_id.required' => '用户ID未提供',
            'user_id.numeric' => '用户ID类型错误',
            'certification.required' => '认证类型未提供',
            'certification.exists' => '认证类型不存在',
            'id.required' => '证件号未提供',
            'contact.required' => '联系方式未提供',
            'desc.required' => '认证描述未提供',
            'desc.max' => '认证描述长度最大250',
            'files.required' => '证件照片未提供',
            'files.exists' => '文件不存在或已被使用',
        ];

        return $messages;
    }

    /**
     * add user certification.
     *
     * @param UserCertification $request
     * @param Certification $certification
     * @param FileWithModel $fileWithModel
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store(Request $request,
                          Certification $certification,
                          FileWithModel $fileWithModel)
    {
        $this->validate($request, $this->rules($request), $this->messages($request));

        $userId = (int) $request->input('user_id');
        if (! $userId) {
            return response()->json(['message' => ['用户ID不存在']], 422);
        }

        $count = Certification::where('user_id', $userId)->count();
        if ($count) {
            return response()->json(['message' => ['该用户已提交过认证']], 422);
        }

        $type = $request->input('type');
        $data = $request->only(['name', 'phone', 'number', 'desc']);
        $files = $this->findNotWithFileModels($request, $fileWithModel);

        $data['files'] = $files->pluck('id');
        if ($type === 'org') {
            $data = array_merge($data, $request->only(['org_name', 'org_address']));
        }

        $certification->certification_name = $type;
        $certification->data = $data;
        $certification->status = 1;

        return $certification->getConnection()->transaction(function () use ($userId, $files, $certification) {
            $files->each(function ($file) use ($userId) {
                $file->channel = 'certification:file';
                $file->raw = $userId;
                $file->save();
            });

            $user = User::find($userId);
            $user->certification()->save($certification);

            return response()->json([
                'message' => ['添加认证成功'],
                'certification_id' => $user->certification->id,
            ])->setStatusCode(201);
        });
    }

    /**
     * Search for non certification users.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function findNoCertificationUsers(Request $request)
    {
        $keyword = $request->get('keyword');

        $condition = sprintf('%%%s%%', $keyword);
        $users = $keyword ? User::where('name', 'like', $condition)
        ->get() : [];

        return response()->json($users)->setStatusCode(200);
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
