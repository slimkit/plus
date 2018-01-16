<?php

use Illuminate\Support\Facades\Route;
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

Route::group(['prefix' => 'api/test-group-worker'], function (RouteRegisterContract $api) {

    // Test route.
    // @ANY /api/test-group-worker
    $api->any('/', API\HomeController::class.'@index');
});
