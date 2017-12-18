<?php

namespace Zhiyi\Plus\EaseMobIm;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class EaseMobController
{
    // Client ID
    protected $client_id;

    // Client Secret
    protected $client_secret;

    // OrgName
    protected $org_name;

    // AppName
    protected $app_name;

    // 环信请求地址
    protected $url;

    // 环信注册类型  0-开放注册，1-授权注册
    protected $register_type;

    public function __construct()
    {
        $this->client_id = 'YXA6tCQToBKgEee3Rv3_L_Q4PQ';
        $this->client_secret = 'YXA6I9abUlXokAjHlKP7fAVK0mKSI_8';
        // 应用标识
        $app_key = explode('#', '1100170327115877#test');

        $this->org_name = $app_key[0];
        $this->app_name = $app_key[1];
        $this->register_type = 0;
        if (! empty($this->org_name) && ! empty($this->app_name)) {
            $this->url = 'https://a1.easemob.com/'.$this->org_name.'/'.$this->app_name.'/';
        }
    }

    public function getToken()
    {
        $options = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ];
        $body = json_encode($options);
        $url = $this->url.'token';

        $tokenResult = $this->postCurl($url, $body, $header = []);

        return 'Authorization:Bearer '.$tokenResult['access_token'];
    }

    /**
     * 开放注册模式.
     *
     * @param Request $request
     * @param ResponseContract $response
     * @return string
     * @author ZsyD<1251992018@qq.com>
     */
    public function openRegister(Request $request, ResponseContract $response)
    {
        $user = $request->user();
        $options['username'] = $user->id;
        $options['password'] = $user->getImPwdHash();
        $url = $this->url.'users';
        $body = json_encode($options);
        $result = $this->postCurl($url, $body, $head = 0);

        return $this->getData($result, $response);
    }

    /**
     * 授权注册.
     *
     * @param Request $request
     * @param ResponseContract $response
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function createUser(Request $request, ResponseContract $response)
    {
        if ($this->register_type == 0) {
            return $this->openRegister($request, $response);
        }
        $user = $request->user();
        $url = $this->url.'users';
        $options = [
            'username' => $user->uid,
            'password' => $user->getImPwdHash(),
        ];
        $body = json_encode($options);
        $header = [$this->getToken()];
        $result = $this->postCurl($url, $body, $header);

        return $this->getData($result, $response);
    }

    /**
     * 批量注册用户.
     *
     * @param Request $request
     * @param ResponseContract $response|NULL
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function createUsers(Request $request, ResponseContract $response)
    {
        $user_ids = $request->input('user_ids');
        $user_ids = is_array($user_ids) ? $user_ids : explode(',', $user_ids);
        $options = [];
        $users = User::when($user_ids, function ($query) use ($user_ids) {
            return $query->whereIn('id', $user_ids);
        })
            ->select('id', 'password')
            ->get();

        foreach ($users as $user) {
            $options[] = [
                'username' => $user->id,
                'password' => $user->getImPwdHash(),
            ];
        }
        $url = $this->url.'users';
        $body = json_encode($options);
        $header = [$this->getToken()];
        $result = $this->postCurl($url, $body, $header);

        return $this->getData($result, $response);
    }

    /**
     * 重置环信密码.
     *
     * @param Request $request
     * @param ResponseContract $response
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    public function resetPassword(Request $request, ResponseContract $response)
    {
        $user = $request->user();
        $url = $this->url.'users/'.$user->id.'/password';

        $options = [
            'newpassword' => $user->getImPwdHash(),
        ];
        $body = json_encode($options);
        $header = [$this->getToken()];
        $result = $this->postCurl($url, $body, $header, 'PUT');

        return $this->getData($result, $response);
    }

    public function postCurl($url, $body, $header, $type = 'POST')
    {
        //1.创建一个curl资源
        $ch = curl_init();
        //2.设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url); //设置url
        //1)设置请求头
        //array_push($header, 'Accept:application/json');
        //array_push($header,'Content-Type:application/json');
        //array_push($header, 'http:multipart/form-data');
        //设置为false,只会获得响应的正文(true的话会连响应头一并获取到)
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
        //设置发起连接前的等待时间，如果设置为0，则无限等待。
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //2)设备请求体
        if (count($body) > 0) {
            //$b=json_encode($body,true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body); //全部数据使用HTTP协议中的"POST"操作来发送。
        }
        //设置请求头
        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        //上传文件相关设置
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算

        //3)设置提交方式
        switch ($type) {
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case 'PUT'://使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请									                     求。这对于执行"DELETE" 或者其他更隐蔽的HTT
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        //4)在HTTP请求中包含一个"User-Agent: "头的字符串。-----必设

        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)'); // 模拟用户使用的浏览器
        //5)

        //3.抓取URL并把它传递给浏览器
        $res = curl_exec($ch);

        $result = json_decode($res, true);
        //4.关闭curl资源，并且释放系统资源
        curl_close($ch);
        if (empty($result)) {
            return $res;
        } else {
            return $result;
        }
    }

    public function getData($result, ResponseContract $response)
    {
        if ($result['error']) {
            return $response->json(['message' => [$result['error_description']]], 500);
        }

        return $response->json([
            'data' => $result['data'],
        ])->setStatusCode(201);
    }
}
