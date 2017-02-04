<?php

// admin router.

// login
Route::get('/login', 'IndexController@login')
    ->name('admin.login')
    ->middleware(Zhiyi\Plus\Http\Middleware\CheckAdminLogin::class);

// 登出方法
Route::get('/logout', 'IndexController@logout');

Route::post('/login', 'IndexController@doLogin')
    ->name('admin.doLogin')
    ->middleware(Zhiyi\Plus\Http\Middleware\VerifyPhoneNumber::class)
    ->middleware(Zhiyi\Plus\Http\Middleware\CheckUserByPhoneExisted::class)
    ->middleware(Zhiyi\Plus\Http\Middleware\VerifyPassword::class)
    ->middleware(Zhiyi\Plus\Http\Middleware\VerifyPermissionNode::class);

Route::get('/', function () {
    return view('admin');
});

// Route::group([
//     'middleware' => [
//         Zhiyi\Plus\Http\Middleware\CheckIsAdmin::class,
//     ],
// ], function () {
//     Route::get('/', 'IndexController@index');
// });
