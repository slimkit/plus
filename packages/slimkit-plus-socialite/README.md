# ThinkSNS+ 第三方社交账号支持

[![StyleCI](https://styleci.io/repos/100242455/shield?branch=master)](https://styleci.io/repos/100242455)

> 以下称「ThinkSNS+ 第三方社交账号支持」为「本拓展」或者「拓展包」。

本拓展是用于增强 ThinkSNS+ 账户登录能力，内置多种登录驱动，依赖于抽象设计，可以快速的支持本拓展支持外的第三方社交账号。

## 接口文档

查看接口文档请 [点击这里](docs) 。

## 安装拓展

首先，你应该先安装好 ThinkSNS+ 系统，然后通过在命令行进入 ThinkSNS+ 系统的根目录，然后使用 composer 执行：

```shell
composer require slimkit/plus-socialite
```

至此，你已经把拓展包依赖到 ThinkSNS+ 系统中并下载了稳定的代码，但是安装并为完成，你还需要运行「数据库迁移」来将拓展包需要的数据表创建在数据库中：

```shell
php artisan package:handle plus-socialite migrate
```

命令执行完成之后，你就已经安装好了拓展包，并且所提供的 APIs 都是可用的。

## 许可证

拓展包遵循 Apache-2.0 许可证进行开源，更多请查阅 [许可证](LICENSE) 文本。
