<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use App\Models\ImConversation;
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
        $data = $ImUser->where('user_id', $user->id)->first();

        // 本地不存在账号信息
        if (!$data) {
            $ImService = new ImService();
            $res = $ImService->usersPost(['uid' => $user->id, 'name' => $user->name]);
            // 处理返回
            if ($res['code'] == 201) {
                // 注册成功,保存本地用户
                $data = [
                    'user_id'     => $user->id,
                    'im_password' => $res['data']['token'],
                ];
                $ImUser->create($data);
            } else {
                $data = [];
            }
        }
        if ($data) {
            return $this->returnMessage(0, $data, 200);
        } else {
            return $this->returnMessage(3002, [], 422);
        }
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
        $type = intval($request->input('type'));
        $ImService = new ImService();
        // 聊天对话类型
        if (!$request->exists('type') || !$ImService->checkConversationType($type)) {
            // 会话类型不支持
            return $this->returnMessage(3003, $info, 400);
        }
        $user = $request->attributes->get('user');
        // 如果是单聊 检测是否已经存在
        $uids = is_array($request->input('uids')) ? $request->input('uids') : array_filter(explode(',', $request->input('uids')));
        $uids[] = $user->id;
        sort($uids);
        $info = ImConversation::where(['user_id' => $user->id, 'uids' => implode(',', $uids)])->first();
        if ($info) {
            $info = $info->toArray();
            $info['uids'] = explode(',', $info['uids']);

            return $this->returnMessage(0, $info, 200);
        }
        // 组装数据
        $conversations = [
            'type' => intval($type),
            'name' => (string) $request->input('name'),
            'pwd'  => (string) $request->input('pwd'),
            'uids' => $uids,
            'uid'  => $user->id,
        ];

        // 检测uids参数是否合法
        $is_void = $ImService->checkUids($conversations['type'], $conversations['uids']);
        if (!$is_void) {
            // 返回会话参数错误
            return $this->returnMessage(3004, [], 422);
        }
        $res = $ImService->conversationsPost($conversations);
        if ($res['code'] != '201') {
            return $this->returnMessage(3005, [], 422);
        } else {
            // 保存会话
            $addConversation = [
                'user_id'     => $user->id,
                'cid'         => $res['data']['cid'],
                'name'        => $res['data']['name'],
                'pwd'         => $res['data']['pwd'],
                'is_disabled' => 0,
                'type'        => $res['data']['type'],
                'uids'        => $uids,
            ];
            $info = ImConversation::create($addConversation);
            $info = $info->toArray();

            return $this->returnMessage(0, $info, 200);
        }
    }

    /**
     * 获取会话信息.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-20T16:22:58+080
     *
     * @version  1.0
     *
     * @param int $cid 会话ID
     *
     * @return
     */
    public function getConversation(int $cid)
    {
        $info = ImConversation::where('cid', $cid)->first();
        if ($info) {
            $info = $info->toArray();
            $info['uids'] = explode(',', $info['uids']);

            return $this->returnMessage(0, $info, 200);
        }

        return $this->returnMessage(3006, [], 404);
    }

    /**
     * 获取会话列表.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-22T09:23:42+080
     *
     * @version  1.0
     *
     * @return
     */
    public function getConversationList(Request $request)
    {
        $user = $request->attributes->get('user');
        $list = ImConversation::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        if ($list) {
            return $this->returnMessage(0, $list->toArray(), 200);
        }

        return $this->returnMessage(0, [], 200);
    }

    /**
     * 刷新聊天授权.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-22T17:35:47+080
     *
     * @version  1.0
     *
     * @return
     */
    public function refresh($value = '')
    {
        // code...
    }

    /**
     * 返回信息.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-20T15:49:10+080
     *
     * @version  1.0
     *
     * @param int   $code      code状态码 0表示成功
     * @param int   $http_code http状态码
     * @param array $data      返回数据
     *
     * @return
     */
    private function returnMessage(int $code, array $data, $http_code = 200)
    {
        if ($code !== 0) {
            return app(MessageResponseBody::class, [
                'code'   => $code,
                'status' => false,
            ])->setStatusCode($http_code);
        } else {
            return app(MessageResponseBody::class, [
                'code'   => 0,
                'status' => true,
                'data'   => $data,
            ])->setStatusCode($http_code);
        }
    }
}
