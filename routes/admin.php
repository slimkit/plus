<?php

use Zhiyi\Plus\Http\Middleware;

// admin router.

Route::get('/', 'HomeController@index')
    ->name('admin');
Route::post('/login', 'HomeController@login');
Route::any('/logout', 'HomeController@logout');

Route::middleware('auth:web')->group(function () {
    Route::get('/site/baseinfo', 'SiteController@get');
    Route::patch('/site/baseinfo', 'SiteController@updateSiteInfo');

    // area
    Route::get('/site/areas', 'SiteController@areas');
    Route::post('/site/areas', 'SiteController@doAddArea');
    Route::delete('/site/areas/{id}', 'SiteController@deleteArea');
    Route::patch('/site/areas/{area}', 'SiteController@patchArea');

    // users
    Route::get('/users', 'UserController@users');
    Route::post('/users', 'UserController@createUser')
        ->middleware(Middleware\CheckUserByNameNotExisted::class)
        ->middleware(Middleware\CheckUserByPhoneNotExisted::class);

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
});

// Add the route, SPA used mode "history"
// But, RESTful the route?
// Route::get('/{route?}', 'HomeController@index')
// ->where('route', '.*');
