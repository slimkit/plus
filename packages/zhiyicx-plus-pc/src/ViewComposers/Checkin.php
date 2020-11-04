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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Cache;
use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class Checkin
{
    public function compose(View $view)
    {
        $config = Cache::get('pc-config');

        if ($config['bootstrappers']['checkin']['switch']) {
            $data = api('GET', '/api/v2/user/checkin');
            $data['checked_in'] = isset($data['checked_in']) && $data['checked_in'] ? 1 : 0;
            $view->with('data', $data);
        }
    }
}
