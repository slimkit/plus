<?php

Route::middleware('web')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentPc\\Controllers')
    ->group(__DIR__.'/routes/web.php');

Route::prefix('/pc/admin')
   ->middleware('web')
   ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentPc\\AdminControllers')
   ->group(__DIR__.'/routes/admin.php');
