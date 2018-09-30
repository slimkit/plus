---
title: 创建前台页面
---

我们已经设计好了数据表，创建的 Blog 已经安装到了 Plus 程序上，这一章，我们来编写前台所需要的 UI 和逻辑部分。注意，这里不是去拓展 PC 的 UI，而是使用 Bootstrap 3 编写 Blog 的页面，因为我这里拟定你是使用开源版的 Plus 程序。

从这里开始，需要用到的 Laravel 知识👉[Blade 模板引擎](https://laravel-china.org/docs/laravel/5.7/blade/2265)

## 布局设计

在编写页面前，我们先来设计一下页面大体布局，我们先设想一下我们需要哪些页面：

- 博客广场：用于浏览全部人的 Blogs，按照最新发布排序。页面顶部始终提醒用户创建自己的 Blog。
- 博客主页：和「博客广场」类似，但是需要展示 Blog 信息以及这个 Blog 下的博客列表。
- 投稿页面：共用页面，博主操纵是发布文章，其他用户是投稿文章
- 文章阅读：文章阅读页面，底部还有评论列表。
- 审核页面：博主审核他人文章投稿。

## Layout 编写

首先，我们的前台 UI 应该有一个总图布局，所以我们在应用的 `resources/views` 目录下新建下面的文件并写入内容：

`layout.blade.php`
```html
@extends('layouts.bootstrap')
@section('head')
    @parent
    <style>
        .blog-container {
            margin-top: 50px;
        }
    </style>
@endsection
@section('body')
    @include('plus-blog::header')
    <main class="container blog-container">
        @yield('container')
    </main>
    @include('plus-blog::footer')
    @parent
@endsection
```

`header.blade.php`
```html
<header class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Blog</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">博客广场</a></li>
            <li class=""><a href="#">文章投稿</a></li>
            <li class=""><a href="#">我的博客</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#">登入</a></li>
        </ul>
    </div>
</header>
```

`footer.blade.php`
```html
@section('head')
    @parent
    <style>
        .blog-footer {
            width: 100%;
            background-color: #2a2730;
            color: #99979c;
            margin-top: 100px;
            padding: 50px 0;
        }
    </style>
@endsection

<footer class="blog-footer text-center">
    <!-- 这里写入你自己的页脚文字 -->
    The Blog package MIT Licensed | Copyright © 2018-Present <a href="https://github.com/medz" target="_blank">Seven Du</a> All rights reserved.
</footer>
```

`home.blade.php`
```php
@extends('plus-blog::layout')
@section('title', '博客广场')
```

然后我们打开 `routes/web.php` 文件，将其默认生成的路由前缀进行修改，默认生成的是 `plus-blog` 我们修改为 `blogs`：

```php
<?php

use Illuminate\Support\Facades\Route;
use SlimKit\Plus\Packages\Blog\Web\Controllers as Web;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

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

Route::group(['prefix' => 'blogs'], function (RouteRegisterContract $route) {

    // Home Router.
    $route->get('/', Web\HomeController::class.'@index');
});
```

接着我们打开包中 `src/Web/Controllers/HomeController.php` 文件，修改为下面的内容：

```php
<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Web\Controllers;

class HomeController
{
    public function index()
    {
        return view('plus-blog::home');
    }
}
```

然后我们访问 `/blogs` 你会看到下面的效果：

<img :src="$withBase('/assets/img/guide/dev/create-pages-layout-view.png')" />

## 顶栏用户图标

我们上面创建玩基础模板了，我们来处理下顶部用户状态吧，当用户没有登录的时候我们显示「登录按钮」，登录成功后我们显示用户头像和名字。

现在我们打开包的 `resources/views/header.blade.php` 文件，修改为下面的内容：

```html
<header class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Blog</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">博客广场</a></li>
            <li class=""><a href="#">文章投稿</a></li>
            <li class=""><a href="#">我的博客</a></li>
        </ul>
        @include('plus-blog::headers.user')
    </div>
</header>
```

然后我们创建一个 `resources/views/headers/user.blade.php` 文件内容如下：

```html
<ul class="nav navbar-nav navbar-right">
    @if (Auth::guest())
    <li><a href="{{ route('login') }}">登入</a></li>
    @else
    <li class="dropdown">
        <a
            href="#"
            class="dropdown-toggle"
            data-toggle="dropdown" 
            role="button" 
            aria-haspopup="true" 
            aria-expanded="false"
        >
            @if (Auth::user()->avatar instanceof \Zhiyi\Plus\FileStorage\FileMetaInterface)
                @php
                    $avatarUrl = Auth::user()->avatar->url();
                    switch (Auth::user()->avatar->getVendorName()) {
                        case 'local':
                            $avatarUrl .= '?rule=h_50,w_50';
                            break;
                        case 'aliyun-oss':
                            $avatarUrl .= '?rule=image/resize,h_50,w_50';
                            break;
                    }
                @endphp
                <img
                    src="{{ $avatarUrl }}"
                    alt="{{ Auth::user()->name }}的头像"
                    style="
                        width: 20px;
                        height: 20px;
                    "
                >
            @else
                {{ Auth::user()->name }}
            @endif
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('logout') }}">退出登录</a></li>
        </ul>
    </li>
    @endif
</ul>
```

然后保存，我们就完成了用户的登录与退出（因为 Plus 自带登录页面，所以无需再写登录页面）
