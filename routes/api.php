<?php

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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Http\Controllers\APIs\V2 as API2;
use Illuminate\Contracts\Routing\Registrar as RouteContract;

Route::any('/example', function (Request $request) {
    $user = $request->user();
    dd($user->hasFollwing(3));
})
->middleware('auth:api');

// API version 1.
Route::prefix('v1')
    ->namespace('Zhiyi\\Plus\\Http\\Controllers\\APIs\\V1')
    ->group(base_path('routes/api_v1.php'));

/*
|--------------------------------------------------------------------------
| RESTful API version 2.
|--------------------------------------------------------------------------
|
| Define the version of the interface that conforms to most of the
| REST ful specification.
|
*/

Route::group(['prefix' => 'v2'], function (RouteContract $api) {

    /*
    |-----------------------------------------------------------------------
    | No user authentication required.
    |-----------------------------------------------------------------------
    |
    | Here are some public routes, public routes do not require user
    | authentication, and if it is an optional authentication route to
    | obtain the current authentication user, use `$request-> user ('api')`.
    |
    */

    /*
    | 应用启动配置.
    */

    $api->get('/bootstrappers', API2\BootstrappersController::class.'@show');

    // Create user authentication token
    $api->post('/tokens', API2\TokenController::class.'@store');

    // Refresh token
    $api->patch('/tokens/{token}', API2\TokenController::class.'@refresh');

    // Search location.
    $api->get('/locations/search', API2\LocationController::class.'@search');

    /*
    |-----------------------------------------------------------------------
    | 用户验证验证码
    |-----------------------------------------------------------------------
    |
    | 定义与用户操作相关的验证码操作
    |
    */

    $api->group(['prefix' => 'verifycodes'], function (RouteContract $api) {

        /*
        | 注册验证码
        */

        $api->post('/register', API2\VerifyCodeController::class.'@storeByRegister');

        /*
        | 已存在用户验证码
        */

        $api->post('/', API2\VerifyCodeController::class.'@store');
    });

    /*
    | 获取文件.
    */

    tap($api->get('/files/{fileWith}', API2\FilesController::class.'@show'), function ($route) {
        $route->setAction(array_merge($route->getAction(), [
            'middleware' => 'bindings',
        ]));
    });

    /*
    |-----------------------------------------------------------------------
    | 与公开用户相关
    |-----------------------------------------------------------------------
    |
    | 定于公开用户的相关信息路由
    |
    */

    $api->group(['prefix' => 'users'], function (RouteContract $api) {

        /*
        | 创建用户
        */

        $api->post('/', API2\UserController::class.'@store');

        /*
        | 批量获取用户
        */

        $api->get('/', API2\UserController::class.'@index');

        /*
        | 获取单个用户资源
         */

        $api->get('/{user}', API2\UserController::class.'@show');

        /*
        | 用户头像
         */

        $api->get('/{user}/avatar', API2\UserAvatarController::class.'@show');

        // 获取用户关注者
        $api->get('/{user}/followers', API2\UserFollowController::class.'@followers');

        // 获取用户关注的用户
        $api->get('/{user}/followings', API2\UserFollowController::class.'@followings');
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

    $api->group(['middleware' => 'auth:api'], function (RouteContract $api) {

        /*
        |--------------------------------------------------------------------
        | Define the current authentication user to operate the route.
        |--------------------------------------------------------------------
        |
        | Define the routes associated with the current authenticated user,
        | such as getting your current user, updating user data, and so on.
        |
        */

        $api->group(['prefix' => 'user'], function (RouteContract $api) {

            /*
            | 获取当前用户
            */

            $api->get('/', API2\CurrentUserController::class.'@show');

            // Update the authenticated user
            $api->patch('/', API2\CurrentUserController::class.'@update');

            // Update phone or email of the authenticated user.
            $api->put('/', API2\CurrentUserController::class.'@updatePhoneOrMail');

            /*
            | 用户收到的评论
            */

            $api->get('/comments', API2\UserCommentController::class.'@index');

            /*
            | 用户收到的赞
             */

            $api->get('/likes', API2\UserLikeController::class.'@index');

            /*
            | 用户通知相关
             */

            $api->group(['prefix' => 'notifications'], function (RouteContract $api) {

                /*
                | 用户通知列表
                 */

                $api->get('/', API2\UserNotificationController::class.'@index');

                /*
                | 通知详情
                 */

                $api->get('/{notification}', API2\UserNotificationController::class.'@show');

                /*
                | 阅读通知，可以使用资源模型阅读单条，也可以使用资源组形式，阅读标注多条.
                 */

                $api->patch('/{notification?}', API2\UserNotificationController::class.'@markAsRead');
            });

            /*
            | 更新当前用户头像
             */

            $api->post('/avatar', API2\UserAvatarController::class.'@update');

            // Update background image of the authenticated user.
            $api->post('/bg', API2\CurrentUserController::class.'@uploadBgImage');

            /*
            | 用户关注
             */

            $api->group(['prefix' => 'followings'], function (RouteContract $api) {

                // 我关注的人列表
                $api->get('/', API2\CurrentUserController::class.'@followings');

                // 关注一个用户
                $api->put('/{target}', API2\CurrentUserController::class.'@attachFollowingUser');

                // 取消关注一个用户
                $api->delete('/{target}', API2\CurrentUserController::class.'@detachFollowingUser');
            });

            $api->group(['prefix' => 'followers'], function (RouteContract $api) {

                // 获取关注我的用户
                $api->get('/', API2\CurrentUserController::class.'@followers');
            });
        });

        /*
        |--------------------------------------------------------------------
        | Wallet routing.
        |--------------------------------------------------------------------
        |
        | Defines routes related to wallet operations.
        |
        */

        $api->group(['prefix' => 'wallet'], function (RouteContract $api) {

            /*
            | 获取钱包配置信息
             */

            $api->get('/', API2\WalletConfigController::class.'@show');

            /*
            | 获取提现记录
             */
            $api->get('/cashes', API2\WalletCashController::class.'@show');

            /*
            | 发起提现申请
             */

            $api->post('/cashes', API2\WalletCashController::class.'@store');

            /*
            | 充值钱包余额
             */

            $api->post('/recharge', API2\WalletRechargeController::class.'@store');

            /*
            | 获取凭据列表
             */

            $api->get('/charges', API2\WalletChargeController::class.'@list');

            /*
            | 获取单条凭据
             */

            $api->get('/charges/{charge}', API2\WalletChargeController::class.'@show');
        });

        /*
        | 检查一个文件的 md5, 如果存在着创建一个 file with id.
         */

        $api->get('/files/uploaded/{hash}', API2\FilesController::class.'@uploaded');

        /*
        | 上传一个文件
         */

        $api->post('/files', API2\FilesController::class.'@store');

        /*
        | 显示一个付费节点
         */

        $api->get('/purchases/{node}', API2\PurchaseController::class.'@show');

        /*
        | 为一个付费节点支付
         */

        $api->post('/purchases/{node}', API2\PurchaseController::class.'@pay');
    });
});
