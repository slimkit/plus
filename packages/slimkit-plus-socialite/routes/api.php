<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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
use SlimKit\PlusSocialite\API\Controllers as API;
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

    // Socialite.
    // @Route /api/v2/socialite
    $api->group(['prefix' => 'socialite'], function (RouteRegisterContract $api) {

        // Check bind and get user auth token.
        // @POST /api/v2/socialite/:provider
        $api->post('/{provider}', API\SocialiteController::class.'@checkAuth');

        // Create user and return auth token.
        // @PATCH /api/v2/socialite/:provider
        $api->patch('/{provider}', API\SocialiteController::class.'@createUser');

        // Bind provider for account.
        // @PUT /api/v2/socialite/:provider
        $api->put('/{provider}', API\SocialiteController::class.'@bindForAccount');
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

    $api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {

        // User
        // @Route /api/v2/user
        $api->group(['prefix' => 'user'], function (RouteRegisterContract $api) {

            // Socialite.
            // @Route /api/v2/user/socialite
            $api->group(['prefix' => 'socialite'], function (RouteRegisterContract $api) {

                // Get all providers bind status.
                // @GET /api/v2/user/socialite
                $api->get('/', API\SocialiteController::class.'@providersStatus');

                // Bind provider for user.
                // @PATCH /api/v2/user/socialite/:provider
                $api->patch('/{provider}', API\SocialiteController::class.'@bindForUser');

                // Unbind provider for user.
                // @DELETE /api/v2/user/socialite/:provider
                $api->delete('/{provider}', API\SocialiteController::class.'@unbindForUser');
            });
        });
    });
});
