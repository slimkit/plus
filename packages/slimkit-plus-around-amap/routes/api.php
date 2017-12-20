<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusAroundAmap\API\Controllers as API;
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

    // Test route.
    // @ANY /api/around-amap
    $api->group(['prefix' => 'around-amap'], function (RouteRegisterContract $api) {
	    $api->get('/', API\HomeController::class.'@getArounds');
	    $api->get('/geo', API\HomeController::class.'@getgeo');
    	$api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {
	    	$api->post('/', API\HomeController::class.'@index');
	    	$api->patch('/', API\HomeController::class.'@update');
	    	$api->delete('/', API\HomeController::class.'@delete');
	    	$api->get('/source', API\HomeController::class.'@getMyAmapId');
	    });
	});
});
