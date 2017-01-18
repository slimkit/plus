<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Models\ImUser;
use App\Models\User;
use App\Exceptions\MessageResponseBody;
use Illuminate\Http\Request;

class ImController extends Controller
{
    /**
     * 获取聊天服务器信息.
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
    public function getImUserInfo(Request $request)
    {
        $user = $request->attributes->get('user');
        $ImUser = new ImUser();
        $data = $ImUser->usersPost(['uid' => $user->id, 'name' => $user->name]);

        return app(MessageResponseBody::class, [
            'code' => 0,
            'status' => true,
            'data' => $data,
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
                'code' => 3001,
                'status' => false,
            ])->setStatusCode(422);
        }
        $user = $request->attributes->get('user');
        $conversations = [
            'type' => intval($type),
            'name' => (string) $request->input('name'),
            'pwd' => (string) $request->input('name'),
            'uids' => $request->input('uids'),
            'uid' => $user->id,
        ];

        $res = $Im->conversationsPost($conversations);
        if (!$res) {
            return app(MessageResponseBody::class, [
                'code' => 3002,
                'status' => false,
            ])->setStatusCode(422);
        }
    }
}
