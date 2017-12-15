<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin', 'HomeController@index')
  ->middleware('auth:web')
  ->name('news:admin');
