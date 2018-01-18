<?php

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
});
