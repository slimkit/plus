# 路由注入和静态资源复制.md

拓展应用中，静态资源复制和路由注入属于一切的开始，没有这个，应用写了再多，入口也是白瞎。
TS+ 的入口路由注入自定义性极强，你只需要按照 Laravel 的路由文件配置，就能实现。

## 安装类中注入路由

路由可以是独立文件，但是如何注入呢？很简单，在 `Zhiyi\Component\Installer\PlusInstallPlugin\InstallerInterface` 中定义了

- public function resource();
- public function router();

两个接口，router 方法就是路由注入方法，不需要路由的情况下，方法是一个空方法，无需返回任何东西，但是一旦需要注入路由，该方法需要返回你的路由文件的绝对路径
```php
<?php

namespace Vendor\Name;

use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;

class Installer extends AbstractInstaller
{
    // ...

    public function router()
    {
        return __DIR__.'/routes.php';
    }
}
```
及
```php
<?php

Route::any('/component-example', function () {
    return 'This is a example.';
});

```

就完成了。

## 静态资源复制

静态资源复制也很简单
```php
<?php

namespace Vendor\Name;

use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;

class Installer extends AbstractInstaller
{
    // ...

    public function resource()
    {
        return __DIR__.'/res';
    }
}
```
只需要返回目录绝对地址，安装后程序就会复制你的静态资源到public目录下，存放在 `vendor/name/` 目录中。
