<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\Certification as CertificationModel;
use Zhiyi\Plus\Http\Requests\API2\UserCertification as UserCertificationRequest;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

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
    public function store(UserCertificationRequest $request,
                          ResponseFactoryContract $response,
                          CertificationModel $certification,
                          FileWithModel $fileWithModel)
    {
        $user = $request->user();
        $type = $request->input('type');
        $file = $fileWithModel->find($request->input('file'));
        $data = $request->only(['name', 'phone', 'number', 'desc']);

        $data['file'] = $file->id;
        $file->channel = 'certification:file';
        $file->raw = $user->id;

        if ($type === 'org') {
            $data = array_merge($data, $request->only(['org_name', 'org_address']));
        }

        $certification->certification_name = $type;
        $certification->data = $data;
        $certification->status = 0;

        return $certification->getConnection()->transaction(function () use ($user, $file, $certification, $response) {
            $file->save();
            $user->certification()->save($certification);

            return $response->json(['message' => ['申请成功，等待审核']])->setStatusCode(201);
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
    public function update(UserCertificationRequest $request,
                           ResponseFactoryContract $response,
                           FileWithModel $fileWithModel)
    {
        $user = $request->user();
        $type = $request->input('type');
        $file = $request->input('file');
        $certification = $user->certification;

        if ($certification->status === 1) {
            return $response->json(['message' => ['已审核通过，无法修改']], 422);
        }

        $updateData = $request->only(['name', 'phone', 'number', 'desc']);
        if ($type === 'org') {
            $updateData = array_merge($updateData, $request->only(['org_name', 'org_address']));
        }

        if ($file) {
            $updateData['file'] = $file;
        }

        $certification->certification_name = $type ?: $certification->certification_name;
        $certification->data = array_merge($certification->data, array_filter($updateData));
        $certification->status = 0;

        return $user->getConnection()->transaction(function () use ($user, $file, $certification, $fileWithModel, $response) {
            if ($file) {
                $fileWithModel->where('id', $file)->update([
                    'channel' => 'certification:file',
                    'raw' => $user->id,
                ]);
            }

            $certification->save();

            return $response->json(['message' => ['修改成功，等待审核']], 201);
        });
    }
}
