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
     * 请求的类型别名定义.
     *
     * @var array
     */
    protected $response_type = [
        'post' => ['post', 'add', 'init'],
        'put' => ['put', 'update', 'save'],
        'delete' => ['delete', 'del'],
        'get' => ['get', 'select'],
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
     * 参数数组.
     *
     * @var array
     */
    protected $params = [];

    /**
     * 当前请求操作的模块类型.
     *
     * @var string
     */
    protected $request_mod = '';

    /**
     * 当前操作的请求方式.
     *
     * @var string
     */
    protected $requset_method = '';

    /**
     * 重写__call方法.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-16T09:40:04+080
     *
     * @version  1.0
     *
     * @param string $method 请求方法
     * @param array  $params 参数信息
     *
     * @return 执行结果
     */
    public function __call($method, $params)
    {
        $type_alias = '';
        $method = strtolower($method);
        if (($request_mod = substr($method, 0, 5)) == 'users') {
            $type_alias = self::parseName(substr($method, 5))[0];
            $this->request_mod = $request_mod;
        } else {
            return parent::__call($method, $params);
        }
        // 调用本类中的方法,获取请求方法以及请求地址
        $type_alias = strtolower($type_alias);
        $this->params = $params[0];
        $this->requset_method = $this->getRequestType($type_alias);

        // 当前方法是否存在
        $fun = $this->request_mod.'Do'.ucfirst($this->requset_method);
        if (method_exists($this, $fun)) {
            return $this->$fun();
        }
        // 调用请求IM服务器
        $this->requset();
    }

    /**
     * 获取请求类型.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-16T09:59:42+080
     *
     * @version  1.0
     *
     * @param string $type_alias 方法别名
     *
     * @return string [description]
     */
    private function getRequestType(string $type_alias) : string
    {
        $type = '';
        if (!$type_alias) {
            return $type;
        }
        foreach ($this->response_type as $key => $value) {
            if (in_array($type_alias, $value)) {
                $type = $key;
                break;
            }
        }

        return $type;
    }

    /**
     * 获取IM服务器请求的地址
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-16T14:29:44+080
     *
     * @version  1.0
     *
     * @return string IM服务器请求地址
     */
    private function getRequestUrl() : string
    {
        $url = $this->service_urls['apis'][$this->request_mod] ?? '';
        if (!$url) {
            return '';
        } else {
            // 待替换字符串匹配
            preg_match_all('/\{(\w+)\}/', $url, $matches);
            $replace = $matches[1];
            $replace = array_intersect($replace, array_keys($this->params));
            // 执行替换并清理不需要的参数
            foreach ($replace as $v) {
                $url = str_replace('{'.$v.'}', $this->params[$v], $url);
                unset($this->params[$v]);
            }

            return $url;
        }
    }

    /**
     * 解析操作方法.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-13T13:56:13+080
     *
     * @version  1.0
     *
     * @param string $name 原操作名称
     *
     * @return array 解析结果
     */
    public static function parseName($name) : array
    {
        return strpos($name, '/') ? explode('/', $name, 2) : [$name];
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
    public function usersDoPost()
    {
        $user_id = $this->params['uid'] ?? 0;
        if (!$user_id || !is_numeric($user_id)) {
            $this->error = '参数非法';

            return false;
        }
        // 检测是否已经存在信息
        if ($info = $this->where('user_id', $user_id)->first()) {
            return $info;
        }
        $res_data = $this->request();
        if ($res->getStatusCode() == 201 || $res_data['code'] == 201) {
            //添加成功,保存记录
            $imUser = [
                'user_id' => $user_id,
                'username' => $form_params['name'],
                'im_password' => $res_data['data']['token'],
                'is_disabled' => 0,
            ];

            return $this->create($imUser);
        } else {
            // 添加失败,返回服务器错误信息
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
        // 创建私聊对话
        $res = $client->request('post', $this->service_urls['apis']['conversation'], [
            'form_params' => [
                'type' => $type,
                'name' => isset($ext_data['name']) ? $ext_data['name'] : '',
                'pwd' => isset($ext_data['pwd']) ? $ext_data['pwd'] : '',
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

    public function request()
    {
        // 创建请求根地址类
        $client = new Client(['base_uri' => $this->service_urls['base_url']]);
        dump($this->requset_method);
        dump($this->getRequestUrl());
        dump($this->params);
        dump(array_values($this->service_auth));
        dump($this->service_debug);
        exit;
        $res = $client->request($this->requset_method, $this->getRequestUrl(), [
            'form_params' => $this->params,
            'auth' => array_values($this->service_auth),
            'http_errors' => $this->service_debug,
        ]);
        // 判断执行结果
        $body = $res->getBody();

        return json_decode($body->getContents(), true);
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
