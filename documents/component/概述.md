# 概述

将描述拓展应用可实现的功能和基础细则。

## 可实现功能

- 前端路由定义与开发
- 数据模型创建于开发
- 路由注入
- 静态资源复制

上述只是大纲，已经足够实现强大的功能块了。

## 基础架构包信息

首先，拓展包必须使用 Composer 来做包架构加载管理。但是没有特殊限制，唯一限制的就是 composer.json 中必须配置 TS+ 特有信息：
```json5
{
    "name": "vendor/name",
    "type": "plus-component",
    "require": {
        "zhiyicx/plus-install-plugin": "^1.0"
    },
    "extra": {
        "installer-class": "vendor\\name\\Installer"
    }
}
```
上述中，是最基本的 composer 配置，包中必须依赖 [zhiyicx/plus-install-plugin](https://packagist.org/packages/zhiyicx/plus-install-plugin) 这个包，然后 `extra` 字段配置 `installer-class` 为你的包中的安装类。

## 如何在 TS+ 中安装拓展应用包

首先使用 Composer 进行依赖
```shell
composer require vendor/name
```
然后再运行:
```shell
php artisan component install vendor/name
```
安装，其中 `php artisan component` 命令有三个指令，分别为 `install`, `update`, `uninstall`

开发更多细节，请参考 [medz/plus-component-example](https://github.com/medz/plus-component-example),其中对做了基础的开发演示。
