# 拓展包命令行开发

命令行开发是有些包很需要的东西，很多包会配合命令行来完成一些初始化操作。例如 [medz/plus-storage-qiniu](https://github.com/medz/plus-storage-qiniu) 就是通过下面的「处理器」来完成拓展数据的注入的。

- [处理器](#处理器)
- [命令](#命令)

## 处理器

可以把处理理解成一个事件，通过特定指令触发这个处理，可以处理很多微笑需求，通过简单的开发就可以完成一个指令动作的开发。

#### 创建处理

创建处理很简单，只需要在你的服务提供者中以 `<name>Handle` 的命名创建方法即可，默认回向改方法的注入 `$command` 以方便调用其他指令或者处理以及输出:

```php
public function helloHandle($command)
{
    $command->info('Hello ThinkSNS+');
}
```

就开发出了一个可以输出 `Hello ThinkSNS+` 的处理。

> 处理名称必须按照 **驼峰式** 命名并以 `Handle` 结尾，例如 `php artisan package:run vendor/package hello-test` 则创建处理的名称应该是 `helloTestHandle`。

#### 默认处理

ThinkSNS+ 已经为你默认创建了默认处理，默认处理可以让你在执行包的处理的时候不需要发送处理的名称。默认处理的方法为 `defaultHandle`，默认创建的默认处理是指向 `list` 的别名。你可以创建该方法来完成你的默认处理。

##### 处理列表

处理默认处理外，还为你创建好了 `list` 处理，该处理会列出你所创建的所有处理器列表。

```shell
php artisan package:run vendor/package list
```

#### 运行处理

创建了处理，我们需要来调用处理器执行处理过程：

```shell
php artisan package:run vendor/package hello
```

你也可以不指定处理器名称来调用默认处理器：

```shell
php artisan package:run vendor/package
```

> 省略处理器执行上述命令，会触发  `defaultHandle` 所定义的处理。

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
