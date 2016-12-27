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

Route::group([
    'namespace'  => 'Api',
    'middleware' => App\Http\Middleware\ApiMessageResponse::class,
], function ($routes) {

    // 获取手机验证码
    Route::post('/auth/get-phone-code', 'AuthController@getPhoneCode')
        ->middleware(App\Http\Middleware\VerifyPhoneNumber::class)
        ->middleware(App\Http\Middleware\VeridySendPhoneCodeType::class);

    Route::post('/auth/register', 'AuthController@register')
        ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
        ->middleware(App\Http\Middleware\VerifyUserNameRole::class) // 验证用户名规则是否正确
        ->middleware(App\Http\Middleware\CheckUserByNameNotExisted::class) // 验证用户名是否被占用
        ->middleware(App\Http\Middleware\CheckUserByPhoneNotExisted::class) // 验证手机号码是否被占用
        ->middleware(App\Http\Middleware\VerifyPhoneCode::class) // 验证验证码释放正确
;
});
