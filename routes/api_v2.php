<?php


// 应用启动配置
Route::get('/bootstrappers', 'BootstrappersController@show');

// 用户登录
Route::post('/login', 'LoginController@store');

// 创建注册验证码
Route::post('/verifycodes/register', 'VerifyCodeController@storeByRegister');
// 已存在用户发送验证码
Route::post('/verifycodes', 'VerifyCodeController@store');

// 当前用户资料接口
Route::prefix('/user')
->middleware('auth:api')
->group(function () {
    // 当前用户资料
    Route::get('/', 'CurrentUserController@show');
});

// 用户相关
Route::prefix('/users')
->group(function () {
    // 获取用户列表
    Route::get('/', 'UserController@show');
    // 获取单用户
    Route::get('/{user}', 'UserController@user');

    Route::post('/', 'UserController@store');
});

// 钱包相关接口
Route::prefix('wallet')
->middleware('auth:api')
->group(function () {
    // 获取钱包配置信息
    Route::get('/', 'WalletConfigController@show');

    // 提现申请
    Route::post('/cashes', 'WalletCashController@store');
    // 获取提现记录
    Route::get('/cashes', 'WalletCashController@show');

    // 充值
    Route::post('/recharge', 'WalletRechargeController@store');

    //  凭据
    Route::get('/charges/{charge}', 'WalletChargeController@show');
    // 用户凭据列表
    Route::get('/charges', 'WalletChargeController@list');
});
