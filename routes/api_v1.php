<?php

// 获取手机验证码
Route::post('/auth/phone/send-code', 'AuthController@sendPhoneCode')
    ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号格式是否正确
    ->middleware(App\Http\Middleware\VerifySendPhoneCodeType::class) // 验证发送验证码类型等集合
;

// 用户登录
Route::post('/auth', 'AuthController@login')
    ->middleware(App\Http\Middleware\CheckDeviceCodeExisted::class) // 验证设备号是否存在
    ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
    ->middleware(App\Http\Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
    ->middleware(App\Http\Middleware\VerifyPassword::class) // 验证密码是否正确
;

// 用户注册
Route::post('/auth/register', 'AuthController@register')
    ->middleware(App\Http\Middleware\CheckDeviceCodeExisted::class) // 验证设备号是否存在
    ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
    ->middleware(App\Http\Middleware\VerifyUserNameRole::class) // 验证用户名规则是否正确
    ->middleware(App\Http\Middleware\CheckUserByNameNotExisted::class) // 验证用户名是否被占用
    ->middleware(App\Http\Middleware\CheckUserByPhoneNotExisted::class) // 验证手机号码是否被占用
    ->middleware(App\Http\Middleware\VerifyPhoneCode::class) // 验证验证码释放正确
;

// 找回密码
Route::patch('/auth/forgot', 'AuthController@forgotPassword')
    ->middleware(App\Http\Middleware\VerifyPhoneNumber::class) // 验证手机号格式
    ->middleware(App\Http\Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
    ->middleware(App\Http\Middleware\VerifyPhoneCode::class) // 验证手机号码验证码是否正确
;

// // 用户相关组
// Route::group([
//     'middleware' => [
//         App\Http\Middleware\AuthUserToken::class,
//     ],
//     'prefix' => '/user',
// ], function ($routes) {

//     // 修改用户密码
//     Route::post('/reset-password', 'UserController@resetPassword') // 设置控制器
// ;
// });
