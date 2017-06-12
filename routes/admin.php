<?php

Route::get('/', 'HomeController@index')
    ->name('admin');
Route::post('/login', 'HomeController@login');
Route::any('/logout', 'HomeController@logout');

Route::middleware('auth:web')
->middleware('admin')
->group(function () {

    // 后台导航
    Route::get('/manages', 'HomeController@showManages');

    // 钱包
    Route::prefix('wallet')->group(function () {
        // 充值选项
        Route::get('/labels', 'WalletLabelController@labels');
        Route::post('/labels', 'WalletLabelController@storeLabel');
        Route::delete('/labels/{label}', 'WalletLabelController@deleteLabel');

        // 转换比例
        Route::get('/ratio', 'WalletRatioController@show');
        Route::patch('/ratio', 'WalletRatioController@update');

        // 规则描述
        Route::get('/rule', 'WalletRuleController@show');
        Route::patch('/rule', 'WalletRuleController@update');

        // 提现规则
        Route::get('/cash', 'WalletCashSettingController@show');
        Route::patch('/cash', 'WalletCashSettingController@update');

        // 提现记录
        Route::get('/cashes', 'WalletCashController@show');
        Route::patch('/cashes/{cash}/passed', 'WalletCashController@passed');
        Route::patch('/cashes/{cash}/refuse', 'WalletCashController@refuse');

        //  Ping++
        Route::get('/pingpp', 'WalletPingPlusPlusController@show');
        Route::patch('/pingpp', 'WalletPingPlusPlusController@update');

        // 支付选项
        Route::get('/recharge/types', 'WalletRechargeTypeController@show');
        Route::patch('/recharge/types', 'WalletRechargeTypeController@update');

        // 凭据列表
        Route::get('/charges', 'WalletChargeController@show');
    });

    // SMS 相关
    Route::prefix('sms')->group(function () {
        Route::get('/', 'SmsController@show');
        Route::get('/driver', 'SmsController@showDriver');
        Route::patch('/driver', 'SmsController@updateDriver');
        Route::get('/driver/{dirver}', 'SmsController@showOption');
        Route::patch('/driver/alidayu', 'SmsController@updateAlidayuOption');
    });

    /* ------------------------------ */
    Route::get('/site/baseinfo', 'SiteController@get');
    Route::patch('/site/baseinfo', 'SiteController@updateSiteInfo');

    // 后台表单
    Route::get('/forms', 'SiteController@showForms');

    // area
    Route::get('/site/areas', 'SiteController@areas');
    Route::post('/site/areas', 'SiteController@doAddArea');
    Route::delete('/site/areas/{id}', 'SiteController@deleteArea');
    Route::patch('/site/areas/{area}', 'SiteController@patchArea');

    // users
    Route::get('/users', 'UserController@users');
    Route::post('/users', 'UserController@store')
        ->middleware('role-permissions:admin:user:add,你没有创建用户权限');
    Route::delete('/users/{user}', 'UserController@deleteUser');
    Route::get('/users/{user}', 'UserController@showUser');
    Route::patch('/users/{user}', 'UserController@update')
        ->middleware('role-permissions:admin:user:update,你没有修改用户信息的权限');
    Route::get('/user/setting', 'UserController@showSetting');
    Route::patch('/user/setting', 'UserController@storeSetting');

    // roles
    Route::get('/roles', 'RoleController@roles');
    Route::post('/roles', 'RoleController@createRole');
    Route::patch('/roles/{role}', 'RoleController@updateRole');
    Route::delete('/roles/{role}', 'RoleController@delete');
    Route::get('/roles/{role}', 'RoleController@showRole');

    // 权限节点
    Route::get('/perms', 'RoleController@perms');
    Route::post('/perms', 'RoleController@createPerm');
    Route::patch('/perms/{perm}', 'RoleController@updatePerm');
    Route::delete('/perms/{perm}', 'RoleController@deletePerm');

    // 储存设置
    Route::get('/storages/engines', 'StorageController@showEngines');
    Route::get('/storages/engines/{engine}', 'StorageController@showEngineOption');
    Route::patch('/storages/engines/{engine}', 'StorageController@updateEngineOption');

    // 系统通知
    Route::any('/system/notice', 'SystemController@pushSystemNotice');
});

// Add the route, SPA used mode "history"
// But, RESTful the route?
// Route::get('/{route?}', 'HomeController@index')
// ->where('route', '.*');
