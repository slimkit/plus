<?php

// use Zhiyi\Plus\Http\Middleware;

// admin router.

Route::get('/', 'HomeController@index');
Route::post('/login', 'HomeController@login');
Route::any('/logout', 'HomeController@logout');
