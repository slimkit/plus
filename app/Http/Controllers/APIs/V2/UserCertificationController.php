<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Http\Requests\API2\UserCertificationPost;
use Zhiyi\Plus\Models\UserCertification as UserCertificationModel;
use Zhiyi\Plus\Models\Certification as CertificationModel;

class UserCertificationController extends Controller
{   

    /**
     * my certification
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function show(Request $request)
    {
    	$user = $request->user('api')->id ?? 0;

    	!$user && abort(401, '请先登录');

    	$certification = UserCertificationModel::where('user_id', $user)
    		->first()
            ->toArray();
    	if(!$certification) {
    		abort(404, '没有提交认证');
    	}
        $data = $certification['data'];

        unset($certification['data']);
        $certification = array_merge($certification, $data);

    	return response()->json($certification)->setStatusCode(200);
    }

    /**
     * store certification from user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(UserCertificationPost $request)
    {
    	$user = $request->user('api')->id ?? 0;
    	!$user && abort(401, '请先登录');

        $certification = $request->input('certification');

        if(!$certification) {
            abort(400, '无效的认证类型');
        }

        $data = $request->only(['name', 'id', 'contact_name', 'desc', 'tips', 'company_name', 'contact', 'file']);

    	$userCertification = new UserCertificationModel;
    	$userCertification->user_id = $user;
    	$userCertification->status = 0;
    	$userCertification->data = $data;
        $userCertification->certification = $certification;
        $userCertification->uid = 0;
        try {
            $userCertification->save();
        } catch (\Exception $e) {
            abort(400, '系统错误,或者已经提交过审核');
        }

        return response()->json(['message'=>'提交成功,请等待审核'])->setStatusCode(201);
    }

    /**
     * 修改认证资料
     * @param  UserCertificationPost $request [description]
     * @return [type]                         [description]
     */
    public function update(UserCertificationPost $request)
    {
        $user = $request->user('api')->id ?? 0;
        !$user && abort(401, '请先登录');

        $certification = $request->input('certification');

        if(!$certification) {
            abort(400, '无效的认证类型');
        }

        $data = $request->only(['name', 'id', 'contact_name', 'desc', 'tips', 'company_name', 'contact', 'file']);

        $userCertification = UserCertificationModel::where('user_id', $user)->first();

        $userCertification->status = 0;
        $userCertification->data = $data;
        $userCertification->certification = $certification;
        try {
            $userCertification->save();
        } catch (\Exception $e) {
            throw $e;
            // abort(500, '系统错误');
        }

        return response()->json(['message'=>'修改成功,请等待审核'])->setStatusCode(201);
    }
}
