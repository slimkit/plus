# 如果更好的开发一个拓展包

按照「拓展包开发指南」已经可以完整的开发出这个 ThinkSNS+ 拓展了，如果你的拓展包存在需要用户配置的信息，也就是「发布配置」；那么直接发布并非最优选择。

因为你发布配置后，用户需要进入 `config/` 目录对你的配置文件进行配置，大多数情况下，用户也并不知道你的配置文件是哪一个，或者你需要长篇大论的在你拓展包的主页进行讲解。

为了解决这个需求，ThinkSNS+ 中开发了一种更友好的方式，你可以先看下 `\Zhiyi\Plus\Support\Configuration` 这个类。这个类的作用是深度合并系统配置，你可以让程序动态的来配置你的配置文件内容。

## 如何开发自定义配置

首先，你需要「发布配置」，这不是必须的，但是我们建议一定要发布，而且你可以使用「合并配置」来让你的配置存在默认值。

然后你应该注册一个后台管理页面,首先在你的服务提供者的 `boot` 方法中调用 `loadRoutesFrom` 方法发布路由：

```php
public function boot()
{
    $this->loadRoutesFrom(
        __DIR__.'/path/to/routes.php'
    );
}
```

路由文件（`__DIR__.'/path/to/routes.php'`）内容如下：

```php
<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/example')
->middleware('web')
->group(function () {
    Route::get('/', 'HomeController@show')->name('example:admin');
});

```

HomeController.php

```php
use Zhiyi\Plus\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('example::admin');
    }
}
```

> `example::admin` 是你发布的视图，你可以采用你喜欢的任何方式开发。（推荐采用 SPA）

创建一个路由和一个控制器以及一个视图后，你只需要将你的路由发布到服务提供者的 `loadManageFrom` 方法即可，这个方法接受三个参数，第一个参数是你的拓展菜单名称，第二个参数是 URL 或者路由名称，第三个是数组拓展参数。

直接传递地址:

```php
public function register()
{
    $this->loadManageFrom('演示', 'https://example.com');
}
```

你可以传递你注册的路由：

```php
public function register()
{
    $this->loadManageFrom('演示', 'example:admin', [
        'route' => true // 必须，该只为真的时候表示传递的是一个路由而非地址
        'icon' => '', // 可选，菜单icon地址
        'parameters' => [] //可选，如果你的理由有参数需要传递，可以用此设置，
        'absolute' => true // 可选
    ]);
}
```

这样你就发布了一个后台管理。

然后在你的控制器的 方法中依赖 `\Zhiyi\Plus\Support\Configuration` 来管理你的配置:

```php
public function store(\Zhiyi\Plus\Support\Configuration $config)
{
    // 拟定发布的配置为 example
    // 你可以直接调用 set 方法更新单个值
    $config->set('example.key1', 'value1');

    // 你也可以设置一个数组来更新多个值
    $config->set([
        'example.key1' => 'value1',
        'example.key2' => 'value2',
    ]);
}
```

> 注意，数组的维度级别使用半角 `.` 点进行分割，例如：
> ```php
> $a = [
>     'key' => [
>         'k' => [
>             'k2' => 1,
>         ]
>     ]
> ];
> ```
> 想更新 `k2` 的值，只需要 `key.k.k2` 作为 `set` 方法的健即可。

通过诸如的 `Configuration` 实例，你可以让用户在后台通过表单提交配置，ThinkSNS+ 会根据你在执行用户提交保存的 `set` 函数配置深度合并你发布的配置的信息。

