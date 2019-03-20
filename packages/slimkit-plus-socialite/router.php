<?php

declare(strict_types=1);

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
