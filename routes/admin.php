<?php

// admin router.

Route::get('/', function () {
    return view('tets');
});

Route::get('/login', 'IndexController@login');
Route::post('/login', 'IndexController@doLogin')
	->name('admin.login')
	->middleware(App\Http\Middleware\VerifyPhoneNumber::class)
	->middleware(App\Http\Middleware\CheckUserByPhoneExisted::class)
	->middleware(App\Http\Middleware\VerifyPassword::class)
	->middleware(App\Http\Middleware\VerifyPermissionNode::class)
;
Route::group([
	'middleware' => [
		App\Http\Middleware\CheckIsAdmin::class,
	]
], function() {
	Route::get('/index', 'IndexController@index')->name('admin.index');
});