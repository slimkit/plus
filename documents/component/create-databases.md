# 数据表如何创建

在那部分拓展应用中，需要用到自己的数据表和数据库储存等。怎么在拓展应用中创建数据表呢？
当然，这一切都是基于 Laravel 的。

## 安装方法

在上一节说的安装类中，继承了 `Zhiyi\Component\Installer\PlusInstallPlugin\InstallerInterface` 接口，
接口中定义了安装，升级，卸载接口如下：

- public function install(Closure $next);
- public function update(Closure $next);
- public function uninstall(Closure $next);

$next 是回调 路由注入删除，静态复制删除。由开发者觉得何时调用 $next, 开发者也可以拒绝 $next 的操作，只要不执行即可。

代码如下：
```php
<?php

namespace Vendor\Name;

use Closure;
use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;

// laravel 需要用到的
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Installer extends AbstractInstaller
{
    // ... 上一章节代码收起

    // 安装方法
    public function install(Closure $next)
    {
        if (!Schema::hasTable('vendor_name')) {
            Schema::create('vendor_name', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                // ... TODO more.
            });
        }
    }

    // TODO update，uninstall 也是同理开发。
}

```
