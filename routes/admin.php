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

// Add the route, SPA used mode "history"
// But, RESTful the route?
// Route::get('/{route?}', 'HomeController@index')
// ->where('route', '.*');
