<?php

// admin router.

// login
Route::get('/login', 'IndexController@login')
    ->name('admin.login')
    ->middleware(App\Http\Middleware\CheckAdminLogin::class);

// 登出方法
Route::get('/logout', 'IndexController@logout');

Route::post('/login', 'IndexController@doLogin')
    ->name('admin.doLogin')
    ->middleware(App\Http\Middleware\VerifyPhoneNumber::class)
    ->middleware(App\Http\Middleware\CheckUserByPhoneExisted::class)
    ->middleware(App\Http\Middleware\VerifyPassword::class)
    ->middleware(App\Http\Middleware\VerifyPermissionNode::class);

Route::group([
    'middleware' => [
        App\Http\Middleware\CheckIsAdmin::class,
    ],
], function () {
    Route::get('/', 'IndexController@index');
});
