<?php

// use Zhiyi\Plus\Http\Middleware;

// admin router.

Route::get('/', 'HomeController@index')
->name('admin');

Route::post('/login', 'HomeController@login');
Route::any('/logout', 'HomeController@logout');

// Add the route, SPA used mode "history"
// But, RESTful the route?
// Route::get('/{route?}', 'HomeController@index')
// ->where('route', '.*');
