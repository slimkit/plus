# 拓展包命令行开发

命令行开发是有些包很需要的东西，很多包会配合命令行来完成一些初始化操作。例如 [medz/plus-storage-qiniu](https://github.com/medz/plus-storage-qiniu) 就是通过下面的「处理器」来完成拓展数据的注入的。

- [处理器](#处理器)
- [命令](#命令)

## 处理器

可以把处理理解成一个事件，通过特定指令触发这个处理，可以处理很多微笑需求，通过简单的开发就可以完成一个指令动作的开发。

#### 创建处理器

你只需要创建一个类，这个类继承 `Zhiyi\Plus\Support\PackageHandler`:

```php

use Zhiyi\Plus\Support\PakcageHandler;

class ExamplePackageHandler extends PakcageHandler
{
    // todo.
}
```

然后在你的服务提供者的 `boot` 方法中:

```php
use Zhiyi\Plus\Support\PakcageHandler;

...
public function boot()
{
    PackageHandler::loadHandleFrom('example', ExamolePackageHandler::class);
}
...

```

即可发布

#### 实现处理器

在你创建的「处理器」中只要方法按照 `<name>Handle` 的格式进行写即可：
```php
use Zhiyi\Plus\Support\PakcageHandler;

class ExamplePackageHandler extends PakcageHandler
{
    public function aHandler($command) {
        // TODO
    }    
}
```

> 一个处理器中可以实现多个处理方法

#### 调用处理器方法

```shell
php artisan package:handle <name> [<handler>]
```

> name, handler 都是可选参数，两个都确实，则会打印出全部命令
> 如果只缺失  handler 则打印出该处理器下所有的处理方法。

## 命令

虽然处理器能完成大部分的工作，但是对于丰富 ThinkSNS+ 的命令，我们需要用到命令发布来直接创建命令。命令的开发会比处理器复杂很多。

### 发布命令

发布命令可以调用服务提供者的 `commands` 方法来完成：

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

#### 开发一个新命令

因为 ThinkSNS+ 是基于 Laravel 所开发，所以开发命令并没有任何区别，只要按照 Laravel 的命令开发方式开发即可：

- [Laravel 官方命令开发文档](https://laravel.com/docs/5.4/artisan#defining-input-expectations)
- [Laravel 中文命令开发文档](http://d.laravel-china.org/docs/5.4/artisan#命令结构)
