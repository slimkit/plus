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

/*
|--------------------------------------------------------------------------
| The app routes.
|--------------------------------------------------------------------------
|
| Define the root definitions for all routes here.
|
*/

// APIs routes.
Route::group(
    [
        'middleware' => ['api'],
    ],
    __DIR__.'/routes/api.php'
);

// Web routes.
// Route::group(
//     [
//         'middleware' => ['web'],
//     ],
//     __DIR__.'/routes/web.php'
// );

// Admin routes.
Route::group(
    [
        'middleware' => ['web', 'auth', 'admin'],
    ],
    __DIR__.'/routes/admin.php'
);
