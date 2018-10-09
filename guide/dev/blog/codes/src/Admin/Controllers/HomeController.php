<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Admin\Controllers;

class HomeController
{
    public function index()
    {
        return trans('plus-blog::messages.success');
    }
}
