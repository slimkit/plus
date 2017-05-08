<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::any('/example', function (Request $request) {
    var_dump(Auth::guard('api')->user());
    // var_dump($request->user());
})
// ->middleware('auth:api')
;

// API version 1.
Route::prefix('v1')
    ->namespace('APIs\\V1')
    ->group(base_path('routes/api_v1.php'));

// RESTful API version 2.
Route::prefix('v2')
    ->namespace('APIs\\V2')
    ->group(base_path('routes/api_v2.php'));

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
