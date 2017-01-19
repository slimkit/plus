<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use App\Models\ImUser;
use App\Models\User;
use Illuminate\Http\Request;
use Ts\IM\Service as ImService;

class ImController extends Controller
{
    /**
     * 获取聊天服务账号信息.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-18T16:08:41+080
     *
     * @version  1.0
     *
     * @param Request $request 请求类
     *
     * @return mixed 返回结果
     */
    public function getImAccount(Request $request)
	{
		// 当前登陆的用户
        $user = $request->attributes->get('user');

		// 获取本地的IM用户
        $ImUser = new ImUser();
		$data = $ImUser->where('user_id',$user->id)->first();

		//本地不存在账号信息
		if(!$data){
			$ImService = new ImService();
			$data = $ImService->usersPost(['uid' => $user->id, 'name' => $user->name]);
		}

        $data = $ImUser->usersPost(['uid' => $user->id, 'name' => $user->name]);

        return app(MessageResponseBody::class, [
            'code'   => 0,
            'status' => true,
            'data'   => $data,
        ])->setStatusCode(200);
    }

    /**
     * 创建会话.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-18T16:19:33+080
     *
     * @version  1.0
     *
     * @param Request $request 请求类
     *
     * @return mixed 返回结果
     */
    public function createConversations(Request $request)
    {
        //聊天对话类型
        $type = intval($request->input('type'));
        $Im = new ImUser();
        if (!$Im->checkConversationType($type)) {
            return app(MessageResponseBody::class, [
                'code'   => 3001,
                'status' => false,
            ])->setStatusCode(422);
        }
        $user = $request->attributes->get('user');
        $conversations = [
            'type' => intval($type),
            'name' => (string) $request->input('name'),
            'pwd'  => (string) $request->input('name'),
            'uids' => $request->input('uids'),
            'uid'  => $user->id,
        ];

        $res = $Im->conversationsPost($conversations);
        if (!$res) {
            return app(MessageResponseBody::class, [
                'code'   => 3002,
                'status' => false,
            ])->setStatusCode(422);
        }
    }
}
