<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace SlimKit\PlusSocialite\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function Zhiyi\Plus\setting;

class WechatController extends Controller
{
    /**
     * 获取授权跳转页面, 防止appid外泄.
     * @Author   Wayne
     * @DateTime 2018-03-19
     * @Email    qiaobin@zhiyicx.com
     * @return   [type]              [description]
     */
    public function getOauthUrl(Request $request)
    {
        $config = setting('user', 'vendor:wechat-mp', [
            'appid' => '',
            'secret' => '',
        ]);
        $url = $request->post('redirectUrl') ?? '';
        if ($url === '') {
            return response()->json(['message' => '微信配置错误'], 422);
        }
        $url = urlencode($url);
        $originUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$config['appid'].'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state=true#wechat_redirect';

        return response()->json(['url' => $originUrl], 200);
    }

    /**
     * 获取网页授权的access_token, 以及unionid.
     * @Author   Wayne
     * @DateTime 2018-03-19
     * @Email    qiaobin@zhiyicx.com
     * @param    Request             $request [description]
     * @param    string              $code    [description]
     * @return   [type]                       [description]
     */
    public function getAccess(string $code)
    {
        $config = setting('user', 'vendor:wechat-mp', [
            'appid' => '',
            'secret' => '',
        ]);
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$config['appid'].'&secret='.$config['secret'].'&code='.$code.'&grant_type=authorization_code';
        $res = file_get_contents($url);

        return response()->json(json_decode($res), 200);
    }

    /**
     * 获取微信用户信息.
     * @Author   Wayne
     * @DateTime 2018-03-19
     * @Email    qiaobin@zhiyicx.com
     * @param    Request             $request [description]
     * @return   [type]                       [description]
     */
    public function getUser(Request $request)
    {
        $access_token = $request->input('access_token');
        $openid = $request->input('openid');

        $user = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN`');

        return response()->json(json_decode($user), 200);
    }

    public function calculateConfig(Request $request)
    {
        $url = $request->input('url', '');
        if (! $url) {
            return response()->json(['message' => '传递的链接地址错误'], 422);
        }
        $accessToken = Cache::get('wechat-mp-accessToken', '');
        $jssdkTicket = Cache::get('wedchat-mp-jssdk-ticket', '');
        $config = setting('user', 'vendor:wechat-mp', [
            'appid' => '',
            'secret' => '',
        ]);
        if (! $accessToken || ! $jssdkTicket) {
            $originUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$config['appid'].'&secret='.$config['secret'];
            $result = json_decode(file_get_contents($originUrl), true) ?? [];
            if (! $result) {
                return response()->json(['message' => '微信配置错误'], 422);
            }

            $accessToken = $result['access_token'];
            Cache::put('wechat-mp-accessToken', $accessToken, 118);

            $jssdkTicketOrigin = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accessToken.'&type=jsapi'), true) ?? [];

            if (! $jssdkTicketOrigin) {
                return response()->json(['message' => '微信配置错误'], 422);
            }

            $jssdkTicket = $jssdkTicketOrigin['ticket'];
            Cache::put('wedchat-mp-jssdk-ticket', $jssdkTicket, 118);
            // 计算随机字符串
        }

        $str = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
        str_shuffle($str);
        $noncestr = substr(str_shuffle($str), 2, 16);
        $timestamp = time();

        $str = 'jsapi_ticket='.$jssdkTicket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
        $signature = sha1($str);

        return response()->json([
            'appid' => $config['appid'],
            'noncestr' => $noncestr,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ], 200);
    }
}
