<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| The app routes.
|--------------------------------------------------------------------------
|
| Define the root definitions for all routes here.
|
*/

// APIs.
Route::middleware('api')->group(__DIR__.'/routes/api.php');

// Admin http.
Route::middleware(['web', 'auth', 'admin'])->group(__DIR__.'/routes/admin.php');
