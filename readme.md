<p align="center"><img src=".github/plus.png"></p>

<p align="center">
<a href="https://styleci.io/repos/76627423"><img src="https://styleci.io/repos/76627423/shield?branch=master" alt="StyleCI"></a>
<a href="https://travis-ci.org/zhiyicx/thinksns-plus"><img src="https://travis-ci.org/zhiyicx/thinksns-plus.svg?branch=master" alt="Build Status"></a>
<a href="https://circleci.com/gh/zhiyicx/thinksns-plus/tree/master"><img src="https://circleci.com/gh/zhiyicx/thinksns-plus/tree/master.svg?style=svg" alt="CircleCI"></a>
<a href="https://www.codacy.com/app/shiweidu/thinksns-plus?utm_source=github.com&utm_medium=referral&utm_content=zhiyicx/thinksns-plus&utm_campaign=badger"><img src="https://api.codacy.com/project/badge/Grade/8320deaa80b8489f95fcedaae6df079d" alt="Codacy Badge"></a>
<a href="https://www.codacy.com/app/shiweidu/thinksns-plus?utm_source=github.com&utm_medium=referral&utm_content=zhiyicx/thinksns-plus&utm_campaign=Badge_Coverage"><img src="https://api.codacy.com/project/badge/Coverage/8320deaa80b8489f95fcedaae6df079d" alt="Codacy Badge"></a>
</p>

## About TS+

TS+ 是基于 Laravel 开发的一个用户生态基础框架系统，支持动态拓展应用的接入和UI规范设计。
TS+ 在 PHP 版本上选择 **>=7.0** 版本开发，并使用了强类型语言模式，使开发更规范化。

## 如何安装

安装 TS+ 上，可能需要一些技术，但是我们会尽量做到最小化的技术操作。

### Pre

- PHP >= 7.0
- Composer
    * ext-mbstring
    * ext-openssl
- MySQL
- Nginx | Apache | IIS
- Git

### Installed

1. 进入你的 `www` 目录，先克隆仓库。
```shell
git clone https://github.com/zhiyicx/thinksns-plus
cd thinksns-plus
```
2. 使用 Composer 进行依赖处理。
```shell
composer install
```
