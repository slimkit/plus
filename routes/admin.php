<?php

// use Zhiyi\Plus\Http\Middleware;

// admin router.

Route::get('/', 'HomeController@index')
    ->name('admin');

Route::post('/login', 'HomeController@login');
Route::any('/logout', 'HomeController@logout');

Route::get('/site/baseinfo', 'SiteController@get')
    ->middleware('auth:web');
Route::patch('/site/baseinfo', 'SiteController@updateSiteInfo')
    ->middleware('auth:web');

// area
Route::get('/site/areas', 'SiteController@areas');
Route::post('/site/areas', 'SiteController@doAddArea')
    ->middleware('auth:web');
Route::delete('/site/areas/{id}', 'SiteController@deleteArea')
    ->middleware('auth:web');
Route::patch('/site/areas/{area}', 'SiteController@patchArea')
    ->middleware('auth:web');

// users
Route::get('/users', 'UserController@users')
    ->middleware('auth:web');

// roles
Route::get('/roles', 'RoleController@roles')
    ->middleware('auth:web');
Route::delete('/roles/{role}', 'RoleController@delete')
    ->middleware('auth:web');

// Add the route, SPA used mode "history"
// But, RESTful the route?
// Route::get('/{route?}', 'HomeController@index')
// ->where('route', '.*');
