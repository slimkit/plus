<?php

use Illuminate\Support\Facades\Route;
use Slimkit\PlusAppversion\API\Controllers as API;
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

Route::group(['prefix' => 'api/v2'], function (RouteRegisterContract $api) {

    $api->group(['prefix' => '/plus-appversion'], function (RouteRegisterContract $api) {

        // get the list of client versions.
        // GET /api/v2/plus-appversion
        $api->get('/', API\ClientVersionController::class.'@index');
    });
});
