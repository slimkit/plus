<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome');
Route::get('/auth/login', 'Auth\\LoginController@showLoginForm')->name('login');
Route::post('/auth/login', 'Auth\\LoginController@login');
Route::any('auth/logout', 'Auth\\LoginController@logout')->name('logout');

Route::prefix('admin')
    ->namespace('Admin')
    ->group(base_path('routes/admin.php'));
