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
    'prefix' => 'api/test-group-worker',
    'as' => 'api:test-group-worker',
    'middleware' => ['auth:api', Middleware\HasRole::class],
], function (RouteRegisterContract $api) {

    // GitHub Accesses
    $api->apiResource('github/accesses', API\AccessesController::class);
});
