<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Navigation;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User;
use function Zhiyi\Plus\setting;

class BaseController extends Controller
{
    protected $PlusData;

    public function __construct()
    {
        // 初始化
        $this->middleware(function ($request, $next) {
            // 用户认证
            $user = $request->user();
            if ($user) {
                $user->load('newWallet', 'extra', 'tags', 'currency');
                $user->makeVisible(['phone', 'email']);
            }

            $token = $this->PlusData['token'] = $request->session()->get('token');
            $this->PlusData['TS'] = null;
            if ($user) {
                $jwt = app(\Tymon\JWTAuth\JWT::class);
                if (! $token || ! $jwt->setToken($token)->check()) {
                    $token = $this->PlusData['token'] = $jwt->fromUser($user);
                    $request->session()->put('token', $token);
                    $request->session()->save();
                }

                $this->PlusData['TS'] = clone $user;
                // $this->PlusData['TS']['avatar'] = $this->PlusData['TS']['avatar'] ?: asset('assets/pc/images/avatar.png');
            }

            // 站点配置
            $config = Cache::remember('pc-config', 60, function () {
                $config = [];

                // 启动信息接口
                $config['bootstrappers'] = api('GET', '/api/v2/bootstrappers/', []);

                // 删除不需要配置项
                unset($config['bootstrappers']['registerSettings']['content']);

                // 基本配置
                $repository = app(\Illuminate\Contracts\Config\Repository::class);
                $config['common'] = $repository->get('pc');
                $config['files'] = $repository->get('files');
                $config['app'] = $repository->get('app');

                // 顶部导航
                $config['nav'] = Navigation::byPid(0)->byPos(0)->byStatus(1)->orderBy('order_sort')->get();

                // 底部导航
                $config['nav_bottom'] = Navigation::byPid(0)->byPos(1)->byStatus(1)->get();

                // 环信
                $easemob = setting('user', 'vendor:easemob', []) + [
                    'open' => false,
                    'appKey' => '',
                    'clientId' => '',
                    'clientSecret' => '',
                    'registerType' => 0,
                ];

                $config['easemob_key'] = $easemob['appKey'] ?? '';

                // 小助手
                if (isset($config['bootstrappers']['im:helper'])) {
                    foreach ($config['bootstrappers']['im:helper'] as $key => &$value) {
                        // 去除自己
                        if ($value['uid'] == $this->PlusData['TS']['id']) {
                            unset($config['bootstrappers']['im:helper'][$key]);
                            continue;
                        }
                        $value['name'] = User::where('id', $value['uid'])->value('name');
                    }
                }

                // 上传配置
                $config['files'] = $config['files'] ?: ['upload_max_size' => '102400'];

                return $config;
            });

            $this->PlusData['config'] = $config;
            // 公共地址
            $this->PlusData['routes']['api'] = asset('/api/v2');
            $this->PlusData['routes']['storage'] = asset('/api/v2/files').'/';

            // 环信相关用户
            $this->PlusData['easemob_users'] = isset($_COOKIE['easemob_uids']) ? User::whereIn('id', explode(',', $_COOKIE['easemob_uids']))->get() : [];

            return $next($request);
        });
    }

    /**
     * 操作提示.
     * @param Request $request
     * @return mixed
     * @author ZsyD
     */
    public function notice(Request $request)
    {
        $data['status'] = $request->query('status', 1);
        $data['message'] = $request->query('message');
        $data['content'] = $request->query('content');
        $data['url'] = $request->query('url');
        $data['time'] = $request->query('time', 3);

        return view('pcview::templates.notice', $data, $this->PlusData);
    }

    /**
     * 查看资源页面.
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function reportView(Request $request)
    {
        $reportable_id = $request->query('reportable_id');
        $reportable_type = $request->query('reportable_type');
        if (! $reportable_id || ! $reportable_type) {
            return abort(404);
        }

        switch ($reportable_type) {
            case 'users':
                return response()->redirectTo(route('pc:mine', ['user' => $reportable_id]), 302);
                break;
            case 'comments': // 评论部分暂时跳转到所属资源的详情页
                $comment = CommentModel::query()->find($reportable_id);
                if (! $comment) {
                    return abort(404);
                }

                return response()->redirectTo(route('pc:reportview', ['reportable_id' => $comment->commentable_id, 'reportable_type' => $comment->commentable_type]), 302);
                break;
            case 'feeds':
                return response()->redirectTo(route('pc:feedread', ['feed' => $reportable_id]), 302);
                break;
            case 'news':
                return response()->redirectTo(route('pc:newsread', ['news_id' => $reportable_id]), 302);
                break;
            case 'groups':
                return response()->redirectTo(route('pc:groupread', ['group_id' => $reportable_id]), 302);
                break;
            default:
                return abort(404);
                break;
        }
    }
}
