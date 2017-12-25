<?php

use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;

Route::middleware('web')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\Controllers')
    ->group(component_base_path('/routes/web.php'));

Route::middleware('web')
    ->prefix('/music/admin')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\AdminControllers')
    ->group(component_base_path('/routes/admin.php'));

Route::prefix('api/v1')
    ->middleware('api')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\Controllers')
    ->group(component_base_path('/routes/api.php'));

Route::prefix('api/v2')
    ->middleware('api')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\Controllers\\V2')
    ->group(component_base_path('/routes/api_v2.php'));