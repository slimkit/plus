<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Http\Requests\API2\UserCertificationPost;
use Zhiyi\Plus\Models\UserCertification as UserCertificationModel;

class UserCertificationController extends Controller
{
    /**
     * my certification.
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function show(Request $request)
    {
        $user = $request->user('api')->id ?? 0;

        ! $user && abort(401, '请先登录');

        $certification = UserCertificationModel::where('user_id', $user)
            ->first()
            ->toArray();
        if (! $certification) {
            abort(404, '没有提交认证');
        }
        $data = $certification['data'];
        $certification
        unset($certification['data']);
        $certification = array_merge($certification, $data);

        return response()->json($certification)->setStatusCode(200);
    }

    /**
     * store certification from user.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(UserCertificationPost $request)
    {
        $user = $request->user('api')->id ?? 0;
        ! $user && abort(401, '请先登录');

        $certification = $request->input('certification');

        if (! $certification) {
            abort(400, '无效的认证类型');
        }

        $data = $request->only(['name', 'identify', 'contact_name', 'desc', 'tips', 'company_name', 'contact', 'file']);

        $userCertification = new UserCertificationModel;
        $userCertification->user_id = $user;
        $userCertification->status = 0;
        $userCertification->data = $data;
        $userCertification->certification = $certification;
        $userCertification->uid = 0;
        try {
            $userCertification->getConnection()->transaction(function() use ($userCertification, $data) {
                $userCertification->save();
                // 更新附件file_with
                FileWith::where('id', $data['file'])
                    ->update([
                        'channel' => 'certification:file',
                        'raw' => $userCertification->id
                    ]);
            });
        } catch (\Exception $e) {
            throw $e;
            abort(400, '系统错误,或者已经提交过审核');
        }

        return response()->json(['message'=>'提交成功,请等待审核'])->setStatusCode(201);
    }

    /**
     * 修改认证资料.
     * @param  UserCertificationPost $request [description]
     * @return [type]                         [description]
     */
    public function update(UserCertificationPost $request, UserCertificationModel $certification)
    {
        $user = $request->user('api')->id ?? 0;

        if($user !== $certification->user_id) {
            abort(403, '无权修改');
        }

        $type = $request->input('certification') || $certification->certification;

        $data = $request->only(['name', 'identify', 'contact_name', 'desc', 'tips', 'company_name', 'contact', 'file']);
        foreach ($data as $key => $value) {
            if(!$value) unset($data[$key]);
        }
        $data = array_merge($certification->data, $data);

        $origin_file = $certification->data['file'];
        $data['file'] = $data['file'] ?: $certification->data['file'];

        $certification->status = 0;
        $certification->data = $data;
        $certification->certification = $type;
        try {
            $certification->getConnection()->transaction(function() use ($certification, $data, $origin_file) {
                if($data['file'] && $data['file'] != $origin_file) {
                   // 更新附件file_with
                    FileWith::where('id', $data['file'])
                        ->update([
                            'channel' => 'certification:file',
                            'raw' => $certification->id
                        ]); 
                }
                $certification->save();
            });
        } catch (\Exception $e) {
            // throw $e;
            abort(500, '系统错误');
        }

        return response()->json(['message'=>'修改成功,请等待审核'])->setStatusCode(201);
    }
}
