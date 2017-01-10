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
        $client = new Client(['base_uri' => $this->service_urls['base_url']]);
        // $res = $client->request('post', $this->service_urls['apis']['users'], [
        //     'form_params' => [
        //         'uid' => 1002,
        //         'name' => '测试账号1002',
        //     ],
        //     'auth' => [
        //         'admin', '123456',
        //     ],
        // ]);
        // $body = $res->getBody();
        // $data = $body->getContents();

        $res = $client->request('post', $this->service_urls['apis']['conversation'], [
            'form_params' => [
                'type' => 0,
                'name' => '测试私有的对话',
                'pwd'  => '',
                'uids' => [1001, 1002],
            ],
            'auth' => [
                'admin', '123456',
            ],
        ]);
        $body = $res->getBody();
        $data = $body->getContents();
        echo $data;
        //file_put_contents('conversation1.txt', json_encode($data));
        exit;
        $res = $client->request('post', $this->service_urls['apis']['conversation'], [
            'form_params' => [

            ],
            'auth' => [
                'admin', '123456',
            ],
        ]);
        echo $res->getStatusCode();
        $body = $res->getBody();
        echo $body->getContents();
        exit;
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
}
