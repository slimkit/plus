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
        ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
        ->middleware(App\Http\Middleware\VeridySendPhoneCodeType::class) // 验证发送验证码类型等集合
;

    // 用户注册
    Route::post('/auth/register', 'AuthController@register')
        ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
        ->middleware(App\Http\Middleware\VerifyUserNameRole::class) // 验证用户名规则是否正确
        ->middleware(App\Http\Middleware\CheckUserByNameNotExisted::class) // 验证用户名是否被占用
        ->middleware(App\Http\Middleware\CheckUserByPhoneNotExisted::class) // 验证手机号码是否被占用
        ->middleware(App\Http\Middleware\VerifyPhoneCode::class) // 验证验证码释放正确
;

    // 用户登录
    Route::post('/auth/login', 'AuthController@login')
        ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
        ->middleware(App\Http\Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
        ->middleware(App\Http\Middleware\VerifyPassword::class) // 验证密码是否正确
;

    // 找回密码
    Route::post('/auth/forgot', 'AuthController@forgotPassword')
        ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号格式
        ->middleware(App\Http\Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
        ->middleware(App\Http\Middleware\VerifyPhoneCode::class) // 验证手机号码验证码是否正确
;
});
