<?php

use Zhiyi\Plus\Http\Middleware;

// admin router.

// login
Route::get('/login', 'IndexController@login')
    ->name('admin.login')
    ->middleware(Middleware\CheckAdminLogin::class);

// 登出方法
Route::get('/logout', 'IndexController@logout');

Route::post('/login', 'IndexController@doLogin')
    ->name('admin.doLogin')
    ->middleware(Middleware\VerifyPhoneNumber::class)
    ->middleware(Middleware\CheckUserByPhoneExisted::class)
    ->middleware(Middleware\VerifyPassword::class)
    ->middleware(Middleware\VerifyPermissionNode::class);

Route::get('/', function () {
    return view('admin');
});
