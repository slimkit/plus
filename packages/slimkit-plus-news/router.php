<?php

use Illuminate\Support\Facades\Route;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentNews\base_path as component_base_path;

Route::prefix('news')
    ->middleware('web')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentNews\\AdminControllers')
    ->group(component_base_path('/routes/web.php'));

Route::prefix('/news/admin')
   ->middleware(['web', 'auth', 'admin'])
   ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentNews\\AdminControllers')
   ->group(component_base_path('/routes/admin.php'));

/*
|----------------------------------------------------------------------
| The News compoennt REST ful API routes.
|----------------------------------------------------------------------
*/
Route::prefix('/api')
    ->middleware('api')
    ->group(component_base_path('/routes/api.php'));
