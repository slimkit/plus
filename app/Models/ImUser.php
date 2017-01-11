<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImUser extends Model
{
    use SoftDeletes;

    /**
     * 定义表名.
     *
     * @var string
     */
    protected $table = 'im_users';

    /**
     * 定义允许更新的字段.
     *
     * @var array
     */
    protected $fillable = ['username', 'im_password', 'is_disabled'];

    /**
     * 定义隐藏的字段.
     *
     * @var array
     */
    protected $hidden = ['is_disabled', 'deleted_at', 'username'];

    /**
     * 将字段调整为日期属性.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 是否开启对IM聊天服务器请求的调试功能.
     *
     * @var bool
     */
    protected $service_debug = false;

    /**
     * 保存错误信息.
     *
     * @var string
     */
    protected $error = '';

    /**
     * 定义im服务器的相关请求链接.
     *
     * @var array
     */
    protected $service_urls = [
        'base_url' => 'http://192.168.2.222:9900',
        'apis'     => [
            'users'        => '/users',
            'conversation' => '/conversations',
            'member'       => '/conversations/member',
            'limited'      => '/conversations/{cid}/limited-members',
            'message'      => '/conversations/{cid}/messages',
        ],
    ];

    /**
     * 定义IM服务器的授权用户登陆信息.
     *
     * @var array
     */
    protected $service_auth = [
        'user'     => 'admin',
        'password' => '123456',
    ];

    /**
     * 对密码进行加密.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-09T09:53:25+080
     *
     * @version  1.0
     *
     * @param string $password 初始密码
     */
    public function setImPasswordAttribute($password = '')
    {
        $this->attributes['im_password'] = bcrypt($password);
    }

    /**
     * 初始化IM用户.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-09T09:52:07+080
     *
     * @version  1.0
     *
     * @param int $user_id 本地用户id
     *
     * @return array IM用户信息
     */
    public function initImUser(int $user_id)
    {
        /* 以下为测试数据 **/
        //创建请求根地址类
        $client = new Client(['base_uri' => $this->service_urls['base_url']]);
        $res = $client->request('post', $this->service_urls['apis']['users'], [
             'form_params' => [
                 'uid'  => $user_id,
                 'name' => '测试账号1002', //需要获取用户昵称
            ],
            'auth'        => $this->service_auth,
            'http_errors' => $this->service_debug,
        ]);
        $body = $res->getBody();
        $data = $body->getContents();
        echo $data;
        exit;
    }

    /**
     * 创建会话.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-11T17:59:37+080
     *
     * @version  1.0
     *
     * @param int   $type 会话类型 0:私聊 1:群聊 2:聊天室
     * @param array $uids 默认加入的用户uid数组
     *
     * @return array
     */
    public function conversation(int $type, array $uids)
    {
        if (!$this->checkConversationType()) {
            $this->error = '会话类型不合法';

            return false;
        }
        //创建私聊对话
        $res = $client->request('post', $this->service_urls['apis']['conversation'], [
            'form_params' => [
                'type' => 0,
                'name' => '测试私有的对话',
                'pwd'  => '',
                'uids' => [1001, 1002],
            ],
            'auth'        => $this->service_auth,
            'http_errors' => false,
        ]);
        $body = $res->getBody();
        $data = $body->getContents();
        echo $data;
        exit;
    }

    /**
     * 检测会话类型.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-11T18:01:26+080
     *
     * @version  1.0
     *
     * @param int $type 会话类型 0:私聊 1:群聊 2:单聊
     *
     * @return bool 是否合法
     */
    private function checkConversationType(int $type) : boolean
    {
        return in_array($type, [0, 1, 2]) ? true : false;
    }

    /**
     * 处理IM聊天服务器返回的数据.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-11T17:38:16+080
     *
     * @version  1.0
     *
     * @return array 返回数据信息
     */
    private function haddleImServerResponse($res) : array
    {
        return [];
    }

    /**
     * 根据用户ID获取IM用户信息.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-09T13:46:40+080
     *
     * @version  1.0
     *
     * @param int $user_id 用户id
     *
     * @return array IM用户信息数组
     */
    public function getImUserByUserId(int $user_id)
    {
    }

    /**
     * 获取错误信息.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-11T18:08:14+080
     *
     * @version  1.0
     *
     * @return string 错误信息描述
     */
    public function getError() : string
    {
        return $this->error;
    }
}
