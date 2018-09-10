<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Auth;
use Session;
use Carbon\Carbon;
use GuzzleHttp\Client;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use SlimKit\PlusSocialite\API\Requests\AccessTokenRequest;
use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Models\User;

/**
 * [formatContent 动态列表内容处理]
 * @author Foreach
 * @param  [string] $content [内容]
 * @return [string]
 */
function formatContent($content)
{
    // 链接替换
    $content = preg_replace_callback('/((?:https?|mailto|ftp):\/\/([^\x{2e80}-\x{9fff}\s<\'\"“”‘’，。}]*)?)/u', function($url){
        return '<a href="'.$url[0].'">访问链接+</a>';
    }, $content);

    // 回车替换
    $pattern = array("\r\n","\n","\r");
    $replace = '<br>';
    $content = str_replace($pattern, $replace, $content);

    // 过滤xss
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', 'br,a[href]');
    $purifier = new HTMLPurifier($config);
    $content = $purifier->purify($content);

    // at 用户替换为链接
    $content = preg_replace_callback('/\x{00ad}@((?:[^\/]+?))\x{00ad}/iu', function ($match) {
        $username = $match[1];
        $url = route('pc:mine', [
            'user' => $username,
        ]);
        return sprintf('<a href="%s">@%s</a>', $url, $username);
    }, $content);

    return $content;
}

/**
 * [api 内部请求]
 * @author Foreach
 * @param  string  $method   [请求方式]
 * @param  string  $url      [地址]
 * @param  array   $params   [参数]
 * @param  integer $instance
 * @param  integer $original
 * @return
 */
function api($method = 'POST', $url = '', $params = array(), $instance = 1, $original = 1)
{
    $request = Request::create($url, $method, $params);
    $request->headers->add(['Accept' => 'application/json', 'Authorization' => 'Bearer '. Session::get('token')]);

    // 注入JWT请求单例
    app()->resolving(\Tymon\JWTAuth\JWT::class, function ($jwt) use ($request) {
        $jwt->setRequest($request);

        return $jwt;
    });
    Auth::guard('api')->setRequest($request);

    // 解决获取认证用户
    $request->setUserResolver(function() {
        return Auth::user('api');
    });

    // 解决请求传参问题
    if ($instance) { // 获取登录用户不需要传参
        app()->instance(Request::class, $request);
    }

    $response = Route::dispatch($request);
    return $original ? $response->original : $response;
}

/**
 * [newapi formRequest请求api]
 * @author Foreach
 * @param  string  $method   [请求方式]
 * @param  string  $url      [地址]
 * @param  array   $params   [参数]
 * @return
 */
function newapi($method = 'POST', $url = '', $params = array())
{
    $client = new Client([
        'base_uri' => config('app.url')
    ]);

    $headers = ['Accept' => 'application/json', 'Authorization' => 'Bearer '. Session::get('token')];
    if ($method == 'GET') {
        $response = $client->request($method, $url, [
            'query' => $params,
            'headers' => $headers
        ]);
    } else {
        $response = $client->request($method, $url, [
            'form_params' => $params,
            'headers' => $headers
        ]);
    }
    return json_decode($response->getBody(), true);
}

/**
 * [getTime 时间转换]
 * @author Foreach
 * @param  [type]      $time   [时间]
 * @param  int|integer $type   [类型]
 * @param  int|integer $format [是否比较时间差异]
 * @return
 */
function getTime($time, int $type = 1, int $format = 1)
{
    // 本地化
    $time = Carbon::parse($time);
    Carbon::setLocale('zh');

    $timezone = isset($_COOKIE['customer_timezone']) ? $_COOKIE['customer_timezone'] : 0;
    // 一小时内显示文字
    if ((Carbon::now()->subHours(24) < $time) && $format) {
        return $time->diffForHumans();
    }
    return $type ? $time->addHours($timezone)->toDateString() : $time->addHours($timezone);
}

/**
 * [getImageUrl 获取图片地址]
 * @author Foreach
 * @param  array   $image  [图片数组]
 * @param  [type]  $width  [宽度]
 * @param  [type]  $height [高度]
 * @param  boolean $cut    [是否裁剪]
 * @param  integer $blur   [是否高斯模糊]
 * @return [string]
 */
function getImageUrl($image = array(), $width, $height, $cut = true, $blur = 0)
{
    if (!$image) { return false; }
    // 高斯模糊参数
    $b = $blur != 0 ? '&b=' . $blur : '';

    // 裁剪
    $file = $image['file'] ?? $image['id'];
    if ($cut) {
        $size = explode('x', $image['size']);
        if ($size[0] > $size[1]) {
            $width = number_format($height / $size[1] * $size[0], 2, '.', '');
        } else {
            $height = number_format($width / $size[0] * $size[1], 2, '.', '');
        }
        return asset('/api/v2/files/'.$file) . '?&w=' . $width . '&h=' . $height . $b . '&token=' . Session::get('token');
    } else {
        return asset('/api/v2/files/'.$file) . '?token=' . Session::get('token') . $b;
    }

}

/**
 * [cacheClear 清理缓存]
 * @author Zsyd
 * @return
 */
function cacheClear()
{
   return Artisan::call('cache:clear');
}

/**
 * [getAvatar 获取头像]
 * @author Foreach
 * @param  [type]  $user  [用户数组]
 * @param  integer $width [宽度]
 * @return [string]
 */
function getAvatar($user, $width = 0)
{
    if (! $user['avatar']) {
        switch ($user['sex']) {
            case 1:
                return asset('assets/pc/images/pic_default_man.png');
            case 2:
                return asset('assets/pc/images/pic_default_woman.png');
        }

        return asset('assets/pc/images/pic_default_secret.png');
    }

    $avatar = $user['avatar'];
    if ($avatar instanceof \Zhiyi\Plus\FileStorage\FileMetaInterface) {
        $avatar = $avatar->toArray();
    }
    if ($avatar['vendor'] === 'local' && $width) {
        return sprintf('%s?rule=w_%s', $avatar['url'], $width);
    } elseif ($avatar['vendor'] === 'aliyun-oss' && $width) {
        return sprintf('%s?rule=image/resize,w_%s', $avatar['url'], $width);
    }

    return $avatar['url'];
}

/**
 * [formatMarkdown 转换markdown]
 * @author Foreach
 * @param  [string] $body [内容]
 * @return [string] [html]
 */
function formatMarkdown($body)
{
    // 图片替换
    $body = preg_replace('/\@\!\[(.*?)\]\((\d+)\)/i', '![$1](' . getenv('APP_URL') . '/api/v2/files/$2)', $body);

    $content = htmlspecialchars_decode(\Parsedown::instance()->setMarkupEscaped(true)->text($body));
    // if (!strip_tags($content)) {
    //     $content = preg_replace_callback('/\[\]\((.*?)\)/i', function($url){
    //         return '<p><a href="'.$url[1].'">'.$url[1].'</a></p>';
    //     }, $body);
    // }

    return $content;
}

/**
 * @author Foreach
 * @param  [string] $body [内容]
 * @return [string] [html]
 */
function formatList($body)
{
    $body = preg_replace('/\@\!\[(.*?)\]\((\d+)\)/', '[图片]', $body);

    return  htmlspecialchars_decode(\Parsedown::instance()->setMarkupEscaped(true)->text($body));
}

/**
 * [getUserInfo 获取用户信息]
 * @author Foreach
 * @param  [type] $id [用户id]
 * @return
 */
function getUserInfo($id)
{
    return User::find($id);
}
