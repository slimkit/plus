<?php

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
     * certification list
     *
     * @param Request $request
     * @return $this
     * @author: huhao <915664508@qq.com>
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', 20);
        $certificationName = $request->get('certification_name');
        $certificationStatus = $request->get('status');
        $keyword = $request->get('keyword');
        $items = Certification::orderBy('id', 'desc')
        ->when(! is_null($keyword), function ($query) use ($keyword) {
            $where = sprintf('%%%s%%', $keyword);
            $query->whereHas('user', function ($query) use ($keyword, $where) {
                $query->where('name', 'like', $where);
            })
            ->orWhere('data->number', 'like', $where)
            ->orWhere('data->phone', 'like', $where)
            ->orWhere('data->name', 'like', $where)
            ->orWhere('data->org_address', 'like', $where)
            ->orWhere('data->org_name', 'like', $where);
        })
        ->when($certificationName, function ($query) use ($certificationName) {
            $query->where('certification_name', $certificationName);
        })
        ->when(! is_null($certificationStatus), function ($query) use ($certificationStatus) {
            $query->where('status', $certificationStatus);
        })
       ->paginate($perPage);

        return response()->json($items)->setStatusCode(200);
    }

    /**
     * certifiction pass
     * @param certification $certification
     * @return \Illuminate\Http\JsonResponse
     * @author: huhao <915664508@qq.com>
     */
    public function passCertification(Certification $certification)
    {
        $certification->status = 1;
        $certification->examiner = Auth::user()->id;
        $certification->save();

        return response()->json(['message' => ['修改成功']], 201);
    }

    /**
     * certifiction reject
     * @param Request $request
     * @param Certification $certification
     * @return \Illuminate\Http\JsonResponse
     * @author: huhao <915664508@qq.com>
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
            return response()->json(['message' => ['驳回成功']], 201);
        } else {
            return response()->json(['message' => ['驳回失败，请稍后再试']], 500);
        }
    }

    /**
     * get certification detail
     *
     * @param Certification $certification
     * @return $this
     * @author: huhao <915664508@qq.com>
     */
    public function show(Certification $certification)
    {
        return response()->json($certification)->setStatusCode(200);
    }

    /**
     * update user certification
     * @param UserCertification $request
     * @param Certification $certification
     * @param FileWithModel $fileWithModel
     * @return mixed
     * @author: huhao <915664508@qq.com>
     */
    public function update(
        UserCertification $request,
        Certification $certification,
        FileWithModel $fileWithModel
    ) {
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

        $certification->certification_name = $type ?: $certification->certification_name;
        $certification->data = array_merge($certification->data, array_filter($updateData));
        $certification->status = 1;

        return $certification->getConnection()->transaction(function () use ($files, $certification) {
            $files->each(function ($file) use ($certification) {
                $file->channel = 'certification:file';
                $file->raw = $certification->user_id;
                $file->save();
            });
            $certification->save();

            return response()->json(['message' => ['修改成功']], 201);
        });
    }

    /**
     * add user certification
     * @param UserCertification $request
     * @param Certification $certification
     * @param FileWithModel $fileWithModel
     * @return \Illuminate\Http\JsonResponse|mixed
     * @author: huhao <915664508@qq.com>
     */
    public function store(UserCertification $request,
                          Certification $certification,
                          FileWithModel $fileWithModel)
    {
        $userId = (int) $request->input('user_id');
        if (! $userId) {
            return response()->json(['message' => ['用户ID不存在']], 422);
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

            return response()->json(['message' => ['添加认证成功']])->setStatusCode(201);
        });
    }

    /**
     * Search for non certification users
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     * @author: huhao <915664508@qq.com>
     */
    public function findNoCertificationUsers(Request $request)
    {
        $keyword = $request->get('keyword');

        if (! $keyword) {
            return response()->json(['message' => ['请输入搜索关键字']], 422);
        }

        $condition = sprintf('%%%s%%', $keyword);
        $users = User::where('name', 'like', $condition)
        ->whereDoesntHave('certification')
        ->get();

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
