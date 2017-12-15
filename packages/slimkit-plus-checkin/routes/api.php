<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusCheckIn\API\Controllers as API;
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
    'prefix' => 'api/v2',
    'middleware' => \SlimKit\PlusCheckIn\API\Middleware\CheckInSwitch::class,
], function (RouteRegisterContract $api) {

    // User check-in ranks.
    // @Route /api/v2/checkin-ranks
    $api->group(['prefix' => 'checkin-ranks'], function (RouteRegisterContract $api) {

        // Get all users check-in ranks.
        // @GET /api/v2/checkin-ranks
        $api->get('/', API\RanksController::class.'@index');
    });

    /*
    |-----------------------------------------------------------------------
    | Define a route that requires user authentication.
    |-----------------------------------------------------------------------
    |
    | The routes defined here are routes that require the user to
    | authenticate to access.
    |
    */

    // @Route /api/v2
    $api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {

        // User
        // @Route /api/v2/user
        $api->group(['prefix' => 'user'], function (RouteRegisterContract $api) {

            // User check-in.
            // @Route /api/v2/user/checkin
            $api->group(['prefix' => 'checkin'], function (RouteRegisterContract $api) {

                // Get the authenticated user check-in.
                // @GET /api/v2/user/checkin
                $api->get('/', API\CheckInController::class.'@show');

                // Punch the clock.
                // @PUT /api/v2/user/checkin
                $api->put('/', API\CheckInController::class.'@store');
            });
        });
    });
});
