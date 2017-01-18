<?php

namespace App\Http\Controllers;

use App\Models\ImUser;
use Illuminate\Http\Request;

class IMController extends Controller
{
    /**
     * 创建聊天用户测试.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-17T17:57:45+080
     *
     * @version  1.0
     *
     * @param Request $request Request
     * @param int     $user_id 用户id
     */
    public function create(Request $request, int $user_id)
    {
        $ImUser = new ImUser();
        $user = $ImUser->usersPost(['uid' => $user_id, 'name' => '测试用户'.date('YmdHis')]);
        if ($user === false) {
            echo $ImUser->getError();
        } else {
            dump($user);
            exit;
        }
    }

    /**
     * 删除聊天用户测试.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-17T17:58:39+080
     *
     * @version  1.0
     *
     * @param Request $request Request
     * @param int     $user_id 用户id
     */
    public function delete(Request $request, int $user_id)
    {
        $ImUser = new Imuser();
        $user = $ImUser->usersDelete(['uid' => $user_id]);
        if ($user === false) {
            echo $ImUser->getError();
        } else {
            dump($user);
            exit;
        }
    }
}
