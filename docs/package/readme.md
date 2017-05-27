# ThinkSNS+ 拓展包开发指南

> 也有可能是指北、指东、指西。

- [简介](#简介)
    - [创建拓展包](#创建拓展包)
- [服务提供者](#服务提供者)
- [路由](#路由)
- [资源](#资源)
- [命令](#命令)
- [发布资源](#发布资源)
- [其他服务提供者](#其他服务提供者)

## 简介

拓展包是添加功能到 ThinkSNS+ 的主要方式。拓展包几乎可以达到任何自定义 ThinkSNS+ 的目的，也可以包含许多好用的功能，例如 [medz/plus-component-qiniu](https://github.com/medz/plus-component-qiniu) 可以增加云储存引擎，或像 [zhiyicx/plus-component-web](https://packagist.org/packages/zhiyicx/plus-component-web) 一样包含一整套前端视图程序。

当然，这并不是拓展包的全部，也有很多不需要依赖 ThinkSNS+ 的拓展包，这些都是通过 Composer 组织起来的，这也是 ThinkSNS+ 开发的主要方式。

而这里讲的拓展包的含义在于，这些拓展包依赖于 ThinkSNS+ 也服务于 ThinkSNS+ 。这些包可能包含不同 路由、控制器、视图或者是对 ThinkSNS+ 的定制拓展，目的都在于增强 ThinkSNS+。

> 当然，拓展包并不是全新的一套系统，因为 ThinkSNS+ 是基于 Laravel 进行开发的，你也可以采用 [Laravel拓展包开发](https://laravel.com/docs/5.4/packages) 的方式进行开发拓展，功能目的都是一致的。

### 创建拓展包

拓展包你可以手动创建，手动创建请参照 [创建一个服务提供者](#创建一个服务提供者)。

当然，我们也提供了快捷命令来快速的创建一个标准的拓展包：

```shell
php artisan package:create
```

> 创建命令是询问交互的方式，你只需要一步一步的按照要求简单的输入信息即可完成。

## 服务提供者

服务提供者是每一个拓展包的入口，这是一个非常重要的类，也是所有功能定制入口。

服务提供者负责绑定或注册一些东西到 ThinkSNS+ 的服务容器中并告知 ThinkSNS+ 要从哪里加载拓展包的资源，例如：视图、路由、配置文件、语言包甚至是其他的服务提供者。

### 创建一个服务提供者

所有拓展包的服务提供者都应该继承自 `Zhiyi\Plus\Support\ServiceProvider` 并包含以下两个方法：`boot` 和 `register`。基类 `ServiceProvider` 被放置在 ThinkSNS+ 程序中，正常情况下你无需依赖，但是我们建议你在 Composer 的 "require-dev" 中依赖 `zhiyicx\thinksns-plus` 依赖它，当时它并不会成为一个网站，而是成为你做单元测试服务的环境提供者。

```php
<?php

use Zhiyi\Plus\Support\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // TODO.
    }

    public fucntion register()
    {
        // TODO.
    }
}

```

> `register` 和 `boot` 都不是必须的，但是就算是空方法，没有实体我们也建议包含其中，如果你的服务提供者两个方法都是空的，你就应该考虑，你的拓展包是否还需要按照本文档开发。两个方法都是空的情况下，你的拓展包应该是一个标准的 Composer 包，而不是 ThinkSNS+ 拓展包。

### 发布服务提供者

发布服务提供者很重要，没有发布配置，你的拓展包只会被 Composer 当初普通的包进行依赖，这样 ThinkSNS+ 就无法得知你包的存在也不会加载你的包。

发布服务提供者的方法是在 `composer.json` 中增加配置，由 Composer 来告诉 ThinkSNS+。首先你的包中应当依赖 `zhiyicx\plus-installer` 你可以执行命令 `composer require zhiyicx\plus-installer` 来进行依赖也可以手动修改 `composer.json`。

```json
{
    ...
    "require": {
        ...
        "zhiyicx/plus-installer": "^1.0"
    }
    ...
}
```

依赖 `zhiyicx\plus-installer` 后，只是为 Composer 添加了类型安装器，所以，你需要用 `type` 字段来告诉 Composer 应该用我们选择的安装器，将 `type` 字段设置为 `plus-library` 就可以了。

```json
{
    ...
    "type": "plus-library"
    ...
}
```

好了，我们已经为 Composer 增加了类型安装器，也告诉 Composer 安装你的包改用哪个安装器了，那么我们来发布我们的服务提供者吧。我们在 `composer.json` 中增加 `extra.class` 来告诉安装器我们的服务提供者的位置。

```json
{
    ...
    "extra": {
        "class": "ExampleServiceProvider"
    }
    ...
}
```

> `extra.class` 应当是相当于你包定义的命名空间的完整类名称。

这样发布服务提供者就完成了，当你在 ThinkSNS+ 中依赖你这个包的时候，ThinkSNS+ 就会自动加载你的服务。完整的 `composer.json` 示例如下：

```json
{
    "name": "zhiyicx/plus-example-package",
    "type": "plus-library",
    "require": {
        "zhiyicx/plus-installer": "^1.0"
    },
    "authload": {
        "psr-4": {
            "Zhiyi\\PackageExample\\": "src/"
        }
    },
    "extra": {
        "class": "Zhiyi\\PackageExample\\ExampleServiceProvider"
    }
}
```

> 上面的配置只是一个完整的示例，你需要配置成你包的信息。

## 路由

要为你的包定义路由，只需要在服务提供者的 `boot` 方法中传递路由文件的路径到 `loadRoutesFrom` 方法即可。在你的路由文件中，你可以使用 `Illuminate\Support\Facades\Route` 来注册路由，就像你在 ThinkSNS+ 的路由文件中注册路由一样。

```php
public function boot()
{
    $this->loadRoutesFrom(
        __DIR__.'/path/to/routes.php'
    );
}
```

> 其实在资源中已经讲了路由，但是单独再提出来讲，则能更好的理解支持的功能。

## 资源

有时候，你需要把拓展包的各项信息注册到 ThinkSNS+ 中。

### 配置

你可能想为你的拓展包发布独立的配置文件到 ThinkSNS+ 的 config 目录中。这可以让你的拓展包轻松地进行配置，如果要发布配置文件，只需要在服务提供者的 `boot` 方法内使用 `publishes` 方法：

```php
public function boot()
{
    $this->publishes([
        __DIR__.'/path/to/config.php' => $this->app->configPath('example.php')
    ]);
}
```

当然，我们还有更好的推荐建议，你应该注册一个后台配置页面，对你的配置进行自定义的配置。在 [更好的拓展包提供](nice.md) 中会提到。

### 合并系统配置

你还可以对已经存在的应用程序配置进行合并，你只需要在 `register` 方法中使用 `mergeConfigFrom` 方法：

```php
public function register()
{
    $this->mergeConfigFrom(__DIR__.'/path/to/config.php', 'example');
}
```

> 此方法仅合并配置数组的第一级。如果您的用户部分定义了多维配置数组，则不会合并缺失的选项。

### 路由

如果您的扩展包包中包含路由，您可以使用 `loadRoutesFrom` 方法加载它们。此方法将自动确定应用程序的路由是否已缓存，如果路由已缓存，将不会加载路由文件：

```php
public function boot()
{
    $this->loadRoutesFrom(
        __DIR__.'/path/to/routes.php'
    );
}
```

### 数据库迁移

如果你的拓展包需要包含数据库迁移，你需要使用 `loadMigrationsFrom` 方法告诉 ThinkSNS+ 如何去加载它们。

`loadMigrationsFrom` 只需要你的迁移文件所在目录作为唯一参数：

```php
public function boot()
{
    $this->loadMigrationsFrom(__DIR__.'/path/to/migrations');
}
```

这样之后，ThinkSNS+ 在运行 `php artisan migrate` 命令时它们会被自动执行。效果等同于 ThinkSNS+ 的系统迁移。

### 语言包

如果你的包和 ThinkSNS+ 运用了本地化，你可以使用 `loadTranslationsFrom` 来告诉 ThinkSNS+。

```
public function boot()
{
    $this->loadTranslationsFrom(__DIR__.'/path/to/translations', 'example');
}
```

本地化包加载后，使用语言包参照 `package::file.line` 双分号为你的语言包创建，所以你可以按照以下方式来加载 `example` 中的 `messages` 文件 `welcome` 语句：

```php
echo trans('example::messages.welcome');
```

#### 发布语言包

如果你想发布你的语言包到 ThinkSNS+ 的语言包目录，你可以使用服务提供者的 `publishes` 方法：

```php
public function boot()
{
    $this->loadTranslationsFrom(__DIR__.'/path/to/translations', 'example');
    $this->publishes([
        __DIR__.'/path/to/translations' => $this->app->resourcePath('lang/vendor/example')
    ]);
}
```

现在当你执行包发布命令的时候，你的文件就会自动被复制到指定的目录下。

### 视图

如果你想在拓展包中注册视图，就需要告诉 ThinkSNS+ 视图位置。你可以使用服务提供者的 `loadViewsFrom` 方法来达到这一目的，`loadViewsFrom` 方法有两个参数：视图路径和拓展包名称。例如你的拓展包名称为 `example`，你可以按照下面的方法增加到服务提供者的 `boot` 方法内：

```php
public function boot()
{
    $this->loadViewsFrom(__DIR__.'/path/to/views', 'example');
}
```

扩展包视图参照使用了双分号 `package::view` 语法。所以，你可以通过如下方式从 `example` 扩展包中加载 'test' 视图：

```php
Route::get('test', function () {
    return view('example::test');
});
```

#### 发布视图

实际上在调用 `loadViewsFrom` 方法时，ThinkSNS+ 为你注册了别名在两个位置上。一个是程序的 `resources/views/vendor` 目录，另一个是拓展包的指定目录。

以 `example` 为例，当加载视图的时候，ThinkSNS+ 会先查找 `resources/views/vendor/example` 目录查看是否讲视图发布到程序中了，如果没有，则会加载注册的指定目录。

如果想发布视图到 `resources/views/vendor` 目录，我们依然可以使用服务提供者的 `publishes` 方法：

```php
public function boot()
{
    $this->loadViewsFrom(__DIR__.'/path/to/views', 'example');
    $this->publishes([
        __DIR__.'/path/to/views' => $this->app->resourcePath('views/vendor/example'),
    ]);
}
```

## 命令

有时候，你可能希望为 ThinkSNS+ 增加一些命令，你可以使用 `commands` 来实现，方法接受一个数组，数组中你需要列出你你需要注册的命令：

```php
public function boot()
{
    // 我们用这个方法来判断当前释放在命令行下运行的，来选择性的增加命令，当然，你也可以选择不增加这个判读。
    // 因为命令行可能会在 web 中来动态调用执行一些底层的东西，大部分命令是可以判断的。我们也建议这样做。
    if ($this->app->runningInConsole()) {
        $this->commands([
            FooCommand::class,
            BarCommand::class,
        ]);
    }
}
```

注册完命令后，你可以使用 `php artisan commandName` 来调用它。

> 命令详细开发指南请参考 [拓展包命令行开发指南](console.md)

## 发布资源

在上面定义了很多资源发布，但是定义了并不是真的发布，还需要使用复制命令来进行资源的发布，命令：

```shell
php artisan package:publish vendor/package
```

同时还有两个可选参数，`--tag[=TAG]`、`--force`，参数是可选的。加入 `--force` 参数可以强制覆盖之前已完成的发布，加入 `--tag` 参数可以指定在服务提供者种设置的群组。

### 发布群组资源

有时候需要将发布的资源进行分组，在发布的时候可以选择性的发布特定资源，这对公用 Asstes 非常有用，因为公用 Asstes （如javascriot、css、image等）在拓展包有的情况下基本是每个版本都会出现更改，而其他资源则不一定，由此可以直接发布特定资源来达到最小化操作。

我们在调用 `publishes` 方法的时候可以使用「标签」来对资源进行分组，例如我们现在把发布的数据库迁移和公用 Assets 进行发布两个群组：

```php
public function boot()
{
    $this->publishes([
        __DIR__.'/path/to/assets' => $this->app->publicPath().'/vendor/example'
    ], 'public');

    $this->publishes([
        __DIR__.'/path/to/migrations/' => $this->app->databasePath('migrations')
    ], 'migrations');
}
```

这样发布到群组后我们可以 `php artisan package:publish vendor/package --tag=public` 来单独发布公用 Assets 但是如果已经发布过，可以调用 `php artisan package:publish vendor/package --tag=public --force` 来强制覆盖已经发布的文件。

## 其他服务提供者

这不是一个常用的需求，但是其他服务提供者选项可以让你轻松整合「Laravel 拓展包」，在你的包开发需求中，你其实就是基于 Laravel 的另一种形态进行开发，在特殊需求场景下，你希望你的拓展包能依赖其他的服务提供者或者 Laravel 的服务提供者，你只需要在 `register` 方法中使用 `loadProvider` 方法即可。`loadProvider` 方法接受单个服务提供者类，也可以是用数组包含起来的多个服务提供者类：

```php
public function register()
{
    $this->loadProvider([
        Example2ServiceProvider::class,
    ]);
}
```
