<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\CommonConfig as CommonConfigModel;

class GroupProtocolController
{
    /**
     * 获取圈子协议.
     *
     * @param CommonConfigModel $configModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function get(CommonConfigModel $configModel)
    {
        $protocol = $configModel->byNamespace('groups')->byName('group:protocol')->value('value');

        return response()->json([
            'protocol' => $protocol ?? '',
        ], 200);
    }

    /**
     * 更新圈子协议.
     *
     * @param Request $request
     * @param CommonConfigModel $configModel
     * @author BS <414606094@qq.com>
     */
    public function set(Request $request, CommonConfigModel $configModel)
    {
        $protocol = $request->input('protocol');
        $configModel->updateOrCreate([
            'namespace' => 'groups',
            'name' => 'group:protocol',
        ], ['value' => $protocol]);

        return response()->json(['message' => ['保存成功']], 201);
    }
}
