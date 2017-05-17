<?php

use Zhiyi\Plus\Http\Middleware\V2 as Middleware;

// 应用启动配置
Route::get('/bootstrappers', 'BootstrappersController@show');

// 用户登录
Route::any('/login', 'LoginController@store');

// 获取手机验证码
Route::post('/auth/phone/code', 'AuthController@sendPhoneCode')
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号格式是否正确
    ->middleware(Middleware\VerifySendPhoneCodeType::class) // 验证发送验证码类型等集合
;
// 用户登录
Route::post('/auth', 'AuthController@login')
    ->middleware(Middleware\CheckDeviceCodeExisted::class) // 验证设备号是否存在
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
    ->middleware(Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
;

// 重置token接口
Route::patch('/auth', 'AuthController@resetToken');

// 用户注册
Route::post('/auth/register', 'AuthController@register')
    ->middleware(Middleware\CheckDeviceCodeExisted::class) // 验证设备号是否存在
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
    ->middleware(Middleware\VerifyUserNameRole::class) // 验证用户名规则是否正确
    ->middleware(Middleware\CheckUserByNameNotExisted::class) // 验证用户名是否被占用
    ->middleware(Middleware\CheckUserByPhoneNotExisted::class) // 验证手机号码是否被占用
    ->middleware(Middleware\VerifyPhoneCode::class) // 验证验证码释放正确
;

// 找回密码
Route::patch('/auth/password', 'AuthController@forgotPassword')
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号格式
    ->middleware(Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
    ->middleware(Middleware\VerifyPhoneCode::class) // 验证手机号码验证码是否正确
;

// 地区接口
Route::get('/areas', 'AreaController@showAreas');

// 获取单个用户资料
Route::get('/users/{user}', 'UserController@getSingleUserInfo');

// 批量获取用户资料
Route::get('/users', 'UserController@getMultiUserInfo');

// 用户相关组
Route::prefix('users')
->middleware('auth:api')
->group(function () {
    // 修改用户资料
    Route::patch('/', 'UserController@profile')
        ->middleware(Middleware\ChangeUserAvatar::class)
        ->middleware(Middleware\ChangeUserCover::class)
        ->middleware(Middleware\ChangeUsername::class);
    // 修改用户密码
    Route::patch('/password', 'UserController@resetPassword') // 设置控制器
        ->middleware(Middleware\VerifyPassword::class); // 验证用户密码是否正确
    // 关注操作相关
    Route::post('/{user}/follow', 'FollowController@doFollow')
        ->middleware(Middleware\CheckUserExsistedByUserId::class)
        ->middleware(Middleware\CheckIsFollow::class);
    Route::delete('/{user}/follow', 'FollowController@doUnFollow')
        ->middleware(Middleware\CheckUserExsistedByUserId::class)
        ->middleware(Middleware\CheckIsFollowing::class);

    // 查看指定用户关注状态
    // Route::get('/{user}/followstatus', 'FollowController@getFollowStatus')
    //     ->where(['user' => '[0-9]+']);;

    // 批量查看用户关注状态
    // Route::get('/followstatus', function () {
    //     return 1;
    // });
});

// 获取用户关注
// Route::get('/follows/{user}/follows/{max_id?}', 'FollowController@follows');

// 获取用户粉丝
// Route::get('/follows/{user}/followeds/{max_id?}', 'FollowController@followeds');