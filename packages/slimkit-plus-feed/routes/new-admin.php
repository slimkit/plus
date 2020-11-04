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

use Illuminate\Contracts\Routing\Registrar as RouteContract;
use Illuminate\Support\Facades\Route;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Admin\Controllers\Topic;

Route::group(['middleware' => ['auth:web', 'admin']], function (RouteContract $route) {
    $route->get('topics', Topic::class.'@adminListTopics');
    $route->post('topics', Topic::class.'@create');
    $route->put('topics/{topic}', Topic::class.'@update');
    $route->delete('topics/{topic}', Topic::class.'@destroy');
    $route->put('topics/{topic}/hot-toggle', Topic::class.'@hotToggle');
    $route->get('topic-review-switch-toggle', Topic::class.'@getReviewSwitch');
    $route->put('topic-review-switch-toggle', Topic::class.'@reviewSwitchToggle');
    $route->put('topics/{topic}/review', Topic::class.'@toggleReview');
});
