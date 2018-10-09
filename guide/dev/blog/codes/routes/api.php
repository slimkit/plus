<?php

use Illuminate\Support\Facades\Route;
use SlimKit\Plus\Packages\Blog\API\Controllers as API;
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

Route::group(['prefix' => 'api/plus-blog'], function (RouteRegisterContract $api) {

    // Test route.
    // @ANY /api/plus-blog
    $api->any('/', API\HomeController::class.'@index');
});
