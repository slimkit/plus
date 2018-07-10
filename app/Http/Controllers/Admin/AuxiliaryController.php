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

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Support\Facades\Artisan;
use Zhiyi\Plus\Http\Controllers\Controller;

class AuxiliaryController extends Controller
{
    /**
     * 清除缓存.
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function cleanCache()
    {
        Artisan::call('cache:clear');

        return response()->json([], 200);
    }
}
