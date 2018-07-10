<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;

class NewsConfigController extends Controller
{
    /**
     * 查看资讯配置.
     *
     * @author bs<414606094@qq.com>
     * @param  Repository $config
     * @return mixed
     */
    public function show(Repository $config)
    {
        $data = $config->get('news');

        return response()->json($data, 200);
    }

    /**
     * 更新投稿配置.
     *
     * @author bs<414606094@qq.com>
     * @param  Request       $request
     * @param  Configuration $configuration
     */
    public function setContribute(Request $request, Configuration $configuration)
    {
        $contribute = $request->input('contribute');

        $config = $configuration->getConfiguration();
        $config->set('news.contribute', $contribute);

        $configuration->save($config);

        return response()->json([], 204);
    }

    /**
     * 投稿金额配置.
     *
     * @author bs<414606094@qq.com>
     * @param  Request       $request
     * @param  Configuration $configuration
     */
    public function setPayContribute(Request $request, Configuration $configuration)
    {
        $pay_contribute = $request->input('pay_contribute');
        if ($pay_contribute > 9999999 || $pay_contribute < 1) {
            return response()->json(['message' => ['请输入合适的投稿金额']], 422);
        }
        $config = $configuration->getConfiguration();
        $config->set('news.pay_contribute', $pay_contribute);

        $configuration->save($config);

        return response()->json([], 204);
    }
}
