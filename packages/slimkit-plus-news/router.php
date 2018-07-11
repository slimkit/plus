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

use Illuminate\Support\Facades\Route;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentNews\base_path as component_base_path;

Route::prefix('news')
    ->middleware('web')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentNews\\AdminControllers')
    ->group(component_base_path('/routes/web.php'));

Route::prefix('/news/admin')
   ->middleware(['web', 'auth', 'admin'])
   ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentNews\\AdminControllers')
   ->group(component_base_path('/routes/admin.php'));

/*
|----------------------------------------------------------------------
| The News compoennt REST ful API routes.
|----------------------------------------------------------------------
*/
Route::prefix('/api')
    ->middleware('api')
    ->group(component_base_path('/routes/api.php'));
