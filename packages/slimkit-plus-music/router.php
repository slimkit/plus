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

use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;

Route::middleware('web')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\Controllers')
    ->group(component_base_path('/routes/web.php'));

Route::middleware('web')
    ->prefix('/music/admin')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\AdminControllers')
    ->group(component_base_path('/routes/admin.php'));

Route::prefix('api/v2')
    ->middleware('api')
    ->namespace('Zhiyi\\Plus\\Packages\\Music\\API\\Controllers')
    ->group(component_base_path('/routes/api.php'));
