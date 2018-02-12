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

use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Middleware;
use Zhiyi\Plus\Packages\TestGroupWorker\API\Controllers as API;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    // 'as' => 'api:test-group-worker',
    'prefix' => 'api/test-group-worker',
    'middleware' => ['auth:api', Middleware\HasRole::class],
], function (RouteRegisterContract $api) {

    // Base route
    $api->any('/', API\HomeController::class)->name('api:test-group-worker');
    $api->get('/settings', API\SettingsController::class.'@index');
    // GitHub Accesses
    $api->post('/settings/github', API\GitHubAccessController::class.'@bind');
    $api->delete('/settings/github', API\GitHubAccessController::class.'@unbind');

    // Projects
    $api->apiResource('/projects', API\ProjectsController::class);
    $api->get('/projects/{project}/readme', API\ProjectsController::class.'@readme');

    // Tasks
    $api->get('/tasks', API\TasksController::class.'@all');
    $api->get('/projects/{project}/tasks', API\TasksController::class.'@index');
    $api->post('/projects/{project}/tasks', API\TasksController::class.'@store');
});
