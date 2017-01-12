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
    protected $fillable = ['user_id', 'username', 'im_password', 'is_disabled'];

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
    public $service_debug = false;

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
    public $service_urls = [
        'base_url' => 'http://192.168.2.222:9900',
        'apis' => [
            'users' => '/users',
            'conversation' => '/conversations',
            'member' => '/conversations/member',
            'limited' => '/conversations/{cid}/limited-members',
            'message' => '/conversations/{cid}/messages',
        ],
    ];

    /**
     * 定义IM服务器的授权用户登陆信息.
     *
     * @var array
     */
    public $service_auth = [
        'user' => 'admin',
        'password' => '123456',
    ];

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
        if (!$user_id || !is_numeric($user_id)) {
            $this->error = '参数非法';

            return false;
        }
        //检测是否已经存在信息
        if ($info = $this->where('user_id', $user_id)->first()) {
            return $info;
        }
        //创建请求根地址类
        $client = new Client(['base_uri' => $this->service_urls['base_url']]);
        $form_params = [
            'uid' => $user_id,
            'name' => '测试账号'.date('YmdHis', time()), //需要获取用户昵称
        ];
        $res = $client->request('post', $this->service_urls['apis']['users'], [
            'form_params' => $form_params,
            'auth' => array_values($this->service_auth),
            'http_errors' => $this->service_debug,
        ]);
        //判断执行结果
        $body = $res->getBody();
        $res_data = json_decode($body->getContents(), true);
        if ($res->getStatusCode() == 201) {
            //添加成功,保存记录
            $imUser = [
                'user_id' => $user_id,
                'username' => $form_params['name'],
                'im_password' => $res_data['data']['token'],
                'is_disabled' => 0,
            ];

            return $this->create($imUser);
        } else {
            //添加失败,返回服务器错误信息
            $this->error = $res_data['msg'];

            return false;
        }
    }

    /**
     * 创建会话.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-11T17:59:37+080
     *
     * @version  1.0
     *
     * @param int   $type     会话类型 0:私聊 1:群聊 2:聊天室
     * @param array $uids     默认加入的用户uid数组
     * @param array $ext_data 扩展字段 包括以下字段
     *                        name:会话名称,pwd会话密码
     *
     * @return array
     */
    public function conversation(int $type, array $uids, array $ext_data)
    {
        if (!$this->checkConversationType()) {
            $this->error = '会话类型不合法';

            return false;
        }
        //创建私聊对话
        $res = $client->request('post', $this->service_urls['apis']['conversation'], [
            'form_params' => [
                'type' => $type,
                'name' => $ext_data ?? '',
                'pwd' => '',
                'uids' => [1001, 1002],
            ],
            'auth' => array_values($this->service_auth),
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
