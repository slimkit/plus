---
title: 创建页面
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

<<< @/guide/dev/blog/codes/resources/views/layout.blade.php

`header.blade.php`

<<< @/guide/dev/blog/codes/resources/views/header.blade.php

`footer.blade.php`

<<< @/guide/dev/blog/codes/resources/views/footer.blade.php

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

## 顶栏用户图标

我们上面创建玩基础模板了，我们来处理下顶部用户状态吧，当用户没有登录的时候我们显示「登录按钮」，登录成功后我们显示用户头像和名字。

现在我们打开包的 `resources/views/header.blade.php` 文件，修改为下面的内容：

<<< @/guide/dev/blog/codes/resources/views/header.blade.php

然后我们创建一个 `resources/views/headers/user.blade.php` 文件内容如下：


<<< @/guide/dev/blog/codes/resources/views/headers/user.blade.php

然后保存，我们就完成了用户的登录与退出（因为 Plus 自带登录页面，所以无需再写登录页面）

## 开源版设置 Blog 默认进入

我们的开发教程是在开源版本的 Plus 程序上进行的，是不是觉得每次都要在 URL 输入 `/blogs` 进入很麻烦？我们现在就来设置默认用户打开就进入 Blog 首页即可！

我们打开拓展包的 `routes/web.php` 文件：

```php
<?php

use Illuminate\Support\Facades\Route;
use SlimKit\Plus\Packages\Blog\Web\Controllers as Web;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

Route::get('/', Web\HomeController::class.'@index');
Route::group(['prefix' => 'blogs'], function (RouteRegisterContract $route) {
    // TODO
});
```

## 后台入口

我们覆盖了默认首页，我们自定义的页面没有了默认首页的提供的后台入口，所以我们在顶部用户图标下拉菜单中增加后台入口吧！

我们先打开包的 `resources/views/headers/user.blade.php` 中。在「退出登录」的 `li` 标签前面增加：

```html
@if (Auth::user()->ability('admin: login'))
    <li><a href="{{ url('/admin') }}">进入后台</a></li>
@endif
```

## 页面预览

我们这一步就开发完成了所有页面公用的 Layout 部分，我们来看看效果吧！

<img :src="$withBase('/assets/img/guide/dev/blog/create-pages-layout-view.png')" />
