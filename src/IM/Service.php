@@ -1,353 +0,0 @@
<?php

namespace Ts\IM;

use GuzzleHttp\Client;

class Service
{
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
        'base_url' => 'http://192.168.10.222:9900',
        'apis' => [
            'users' => '/users',
            'conversations' => '/conversations',
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
     * __call方法.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-16T09:40:04+080
     *
     * @version  1.0
     *
     * @param string $method 访问方法
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
        } elseif (($request_mod = substr($method, 0, 13)) == 'conversations') {
            $type_alias = self::parseName(substr($method, 13))[0];
            $this->request_mod = $request_mod;
        } else {
            $this->error = '服务不可用';

            return false;
        }

        // 调用本类中的方法,获取请求方法
        $type_alias = strtolower($type_alias);
        $this->requset_method = $this->getRequestType($type_alias);

        // 定义类参数
        $this->params = $params[0];

        // 自定义方法是否存在,存在则执行并返回
        $fun = $this->request_mod.'Do'.ucfirst($this->requset_method);
        if (method_exists($this, $fun)) {
            return $this->$fun();
        }

        // 直接调用请求IM服务器
        $res = $this->request();

        // 是否定义后置方法,已定义则执行并返回
        $after_fun = '_after_'.$fun;
        if (method_exists($this, $after_fun)) {
            return $this->$after_fun($res);
        } else {
            $body = $res->getBody()->getContents();
            if ($body) {
                // 有返回主体
                $ret = json_decode($body, true);
            } else {
                // 没有返回主体,获取状态码
                $ret = [
                    'code' => $res->getStatusCode(),
                ];
            }

            return $ret;
        }
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

        // 发送请求
        $res = $this->request();

        // 获取返回数据
        $res_data = $res->getBody();

        //获取执行结果
        $res_data = json_decode($res_data->getContents(), true);
        if ($res->getStatusCode() == 201 || $res_data['code'] == 201) {
            return $res_data;
        } else {
            // 添加失败,返回服务器错误信息
            $this->error = $res_data['msg'];

            return false;
        }
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
    public function checkConversationType(int $type) : bool
    {
        return in_array($type, [0, 1, 2]) ? true : false;
    }

    /**
     * 请求方法.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-17T14:16:20+080
     *
     * @version  1.0
     *
     * @param bool $get_body 是否获取数据信息
     *
     * @return class,array 如果 $get_body设置为false,返回请求实例对象,否则返回请求结果的body体
     */
    public function request($get_body = false)
    {
        // 创建请求根地址类
        $client = new Client(['base_uri' => $this->service_urls['base_url']]);

        // 发送请求内容
        $request_body = [
            'auth' => array_values($this->service_auth),
            'http_errors' => $this->service_debug,
        ];

        // 获取请求的地址
        $request_url = $this->getRequestUrl();

        // 处理请求的参数
        if (in_array($this->requset_method, ['get', 'delete'])) {
            if (!empty($this->params)) {
                foreach ($this->params as $key => $value) {
                    $request_url .= '/'.$value;
                }
            }

            // 同时也发送请求的参数信息
            $request_body['query'] = $this->params;
        } else {
            // 采用表单的方式提交数据
            $request_body['form_params'] = $this->params;
        }

        // 发送请求
        $res = $client->request($this->requset_method, $request_url, $request_body);

        // 判断并返回期望得到的执行结果类型
        if ($get_body === true) {
            $body = $res->getBody()->getContents();
            if ($body) {
                $ret = json_decode($body, true);
            } else {
                $ret = [
                    'code' => $res->getStatusCode(),
                ];
            }

            return $ret;
        } else {
            return $res;
        }
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
