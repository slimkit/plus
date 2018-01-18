# ThinkSNS+ 用户签到拓展

[![StyleCI](https://styleci.io/repos/99870628/shield?branch=master)](https://styleci.io/repos/99870628)

「用户签到」拓展用来增强 [ThinkSNS+](https://github.com/slimkit/thinksns-plus) 系统，使其拥有「签到」和「签到排行」功能。

并且，拓展包还提供了拓展开关，可以在不需要的时候关闭功能。

## 接口文档。

查看接口文档请 [点击这里](docs) 。

## 安装拓展

首先，你应该先安装好 [ThinkSNS+](https://github.com/slimkit/thinksns-plus) 系统，然后通过在命令行进入 [ThinkSNS+](https://github.com/slimkit/thinksns-plus) 系统的根目录，然后使用 `composer` 执行：

```shell
composer require slimkit/plus-checkin
```

至此，你已经把「用户签到」拓展包依赖到 [ThinkSNS+](https://github.com/slimkit/thinksns-plus) 系统中并下载了稳定的代码，但是安装并为完成，你还需要运行「数据库迁移」来将「用户签到」拓展包需要的数据表创建在数据库中：

```shell
php artisan package:handle checkin migrate
```

命令执行完成之后，你就已经安装好了「用户签到」拓展包，并且所提供的 APIs 都是可用的。

## 许可证

用户签到拓展包遵循 Apache-2.0 许可证进行开源，更多请查阅 [许可证](LICENSE) 文本。
