<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\Cache;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class CheckIn
{
    public function compose(View $view)
    {
        $config = Cache::get('config');

        if ($config['bootstrappers']['checkin']['switch']) {
            $data = api('GET', '/api/v2/user/checkin');
            $data['checked_in'] = isset($data['checked_in']) && $data['checked_in'] ? 1 : 0;
            $view->with('data', $data);
        }
    }
}
