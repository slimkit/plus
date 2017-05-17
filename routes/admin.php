<?php

use Zhiyi\Plus\Http\Middleware\V1 as Middleware;

// admin router.

Route::get('/', 'HomeController@index')
    ->name('admin');
Route::post('/login', 'HomeController@login');
Route::any('/logout', 'HomeController@logout');

Route::middleware('auth:web')->group(function () {

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
    Route::post('/users', 'UserController@createUser')
        ->middleware(Middleware\VerifyUserNameRole::class)
        ->middleware(Middleware\VerifyPhoneNumber::class)
        ->middleware(Middleware\CheckUserByNameNotExisted::class)
        ->middleware(Middleware\CheckUserByPhoneNotExisted::class);
    Route::delete('/users/{user}', 'UserController@deleteUser');
    Route::get('/users/{user}', 'UserController@showUser');
    Route::patch('/users/{user}', 'UserController@updateUser');

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
