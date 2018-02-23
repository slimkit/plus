<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| The app routes.
|--------------------------------------------------------------------------
|
| Define the root definitions for all routes here.
|
*/

// APIs routes.
Route::group(['middleware' => ['api']], __DIR__.'/routes/api.php');

// Web routes.
Route::group(['middleware' => ['web']], __DIR__.'/routes/web.php');

// Admin routes.
Route::group(['middleware' => ['web', 'auth', 'admin']], __DIR__.'/routes/admin.php');
