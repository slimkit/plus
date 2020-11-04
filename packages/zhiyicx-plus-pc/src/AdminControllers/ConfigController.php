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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\cacheClear;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Navigation;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Support\Configuration;

class ConfigController extends Controller
{
    /**
     * 导航配置列表.
     * @author 28youth
     * @param  int|int $pos  0-顶部 1-底部
     * @return mixed
     */
    public function index(int $pos = 0)
    {
        $data = [];
        $parents = [];
        $subsets = [];
        $nav = Navigation::byPos($pos)->get();
        foreach ($nav as $item) {
            if ($item->parent_id <= 0) {
                $parents[] = $item;
            } else {
                $subsets[$item->parent_id][] = $item;
            }
        }
        foreach ($parents as $parent) {
            $data[] = $parent;
            if (array_key_exists($parent->id, $subsets)) {
                foreach ($subsets[$parent->id] as $subset) {
                    $data[] = $subset;
                }
            }
        }

        return response()->json([
            'status'  => true,
            'data' => $data,
        ])->setStatusCode(200);
    }

    /**
     * 添加编辑导航.
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function manage(Request $request)
    {
        $nid = $request->input('id', 0);
        $nav = Navigation::find($nid);
        if ($nav) {
            $nav->url = $request->url;
            $nav->name = $request->name;
            $nav->app_name = $request->app_name;
            $nav->order_sort = $request->order_sort;
            $nav->parent_id = $request->parent_id;
            $nav->position = $request->position;
            $nav->status = $request->status;
            $nav->target = $request->target;
            $nav->save();
        } else {
            $nav = new Navigation();
            $nav->url = $request->url;
            $nav->name = $request->name;
            $nav->app_name = $request->app_name;
            $nav->order_sort = $request->order_sort;
            $nav->parent_id = $request->parent_id;
            $nav->position = $request->position;
            $nav->status = $request->status;
            $nav->target = $request->target;
            $nav->save();
        }

        cacheClear();
        /*Navigation::firstOrCreate([
            'id' => $nid,
            ], [
            'name' => $request->name,
            'app_name' => $request->app_name,
            'url' => $request->url,
            'target' => $request->target,
            'status' => $request->status,
            'position' => $request->position,
            'parent_id' => $request->parent_id,
            'order_sort' => $request->order_sort,
        ]);*/
        return response()->json([
            'status'  => true,
            'message' => '操作成功',
        ])->setStatusCode(200);
    }

    /**
     * 获取一条导航记录.
     * @author 28youth
     * @param  int     $nid  记录id
     * @return mixed
     */
    public function getnav(int $nid)
    {
        $nav = Navigation::find($nid);
        if ($nav) {
            return response()->json(['data' => $nav]);
        }
    }

    /**
     * 删除导航记录.
     * @author 28youth
     * @param  int     $nid   记录id
     * @return mixed
     */
    public function delete(int $nid)
    {
        $nav = Navigation::find($nid);
        if ($nav) {
            $nav->delete();
        }

        cacheClear();

        return response()->json(['message' => '删除成功']);
    }

    /**
     * 获取pc基础配置信息.
     * @author 28youth
     * @param Repository $config
     * @param Configuration $configuration
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Repository $config, Configuration $configuration)
    {
        $configs = $config->get('pc');

        if (is_null($configs)) {
            $configs = $this->initSiteConfiguration($configuration);
        }

        return response()->json($configs, 200);
    }

    /**
     * 初始化站点设置.
     * @author 28youth
     * @param Repository $config
     * @param Configuration $configuration
     * @return mixed
     */
    private function initSiteConfiguration(Configuration $configuration)
    {
        $config = $configuration->getConfiguration();

        $config->set('pc.status', 1);
        $config->set('pc.logo', 0);
        $config->set('pc.loginbg', 0);
        $config->set('pc.site_name', 'ThinkSNS+');
        $config->set('pc.site_copyright', 'Powered by ThinkSNS ©2017 ZhishiSoft All Rights Reserved.');
        $config->set('pc.site_technical', 'ThinkSNS');
        $config->set('pc.weibo.client_id', '36901915631');
        $config->set('pc.weibo.client_secret', '278b2212b43ce359ee27e19dfd2303132');
        $config->set('pc.wechat.client_id', 'wx183dc69dabad5f293');
        $config->set('pc.wechat.client_secret', '1aee4dd5b6708e67d5e4d2ffa5d37a134');
        $config->set('pc.qq.client_id', '1014185575');
        $config->set('pc.qq.client_secret', '67d647556551c8e2c53f4ba315f87c936');

        $configuration->save($config);

        return $config['pc'];
    }

    /**
     * 更新pc站基本配置信息.
     * @author 28youth
     * @param  Request $request
     * @param Configuration $configuration
     * @return mixed
     */
    public function updateSiteInfo(Request $request, Configuration $configuration)
    {
        $config = $configuration->getConfiguration();

        $config->set('pc', $request->input('site'));

        $configuration->save($config);

        cacheClear();

        return response()->json(['message' => ['更新站点配置成功']], 201);
    }

    /**
     * 清除站点缓存.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cacheclear()
    {
        cacheClear();

        return response()->json(['message' => '清除成功'], 200);
    }
}
