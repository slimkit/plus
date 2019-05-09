<img align="right" width="100px" src="https://github.com/slimkit/plus/raw/master/public/plus.png" alt="Plus (ThinkSNS+) Logo">

# Plus (ThinkSNS+)

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/8320deaa80b8489f95fcedaae6df079d)](https://www.codacy.com/app/slimkit/plus?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=slimkit/plus&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/8320deaa80b8489f95fcedaae6df079d)](https://www.codacy.com/app/slimkit/plus?utm_source=github.com&utm_medium=referral&utm_content=slimkit/plus&utm_campaign=Badge_Coverage)
[![StyleCI](https://github.styleci.io/repos/76627423/shield?branch=master)](https://github.styleci.io/repos/76627423)
[![Build Status](https://travis-ci.org/slimkit/plus.svg?branch=master)](https://travis-ci.org/slimkit/plus)
[![QQ Group](https://img.shields.io/badge/QQ%20Group-651240785-red.svg?longCache=true&style=flat-square)](//shang.qq.com/wpa/qunwpa?idkey=01b61bdf8a7efc2a40ab4caab2d14793f340e5fe5d09aa0c2c17f3115a579678)

[Plus (ThinkSNS+)](http://www.thinksns.com) 是使用 [Laravel](https://laravel.com/) 框架开发；一个功能强大、易于开发和动态拓展的社交系统。Plus 是遵循 **PSR 规范** 代码统一，并功能块松耦合。你安装完成 Plus 并不意味着已经成功安装了所有功能，因为 Plus 使用 **模块化** 的
原则，所以你安装完成后拥有了所有可以被请求的 REST 接口和后台管理面板，你可能还需要安装如下模块：

- 一个对搜索引擎支持良好的大屏 Web 界面 👉 [ThinkSNS Plus PC](https://github.com/zhiyicx/plus-component-pcos)
- 极大程度还原原生 APP 体验的 SPA (Single Page Application) 应用 👉 [ThinkSNS Plus SPA](https://github.com/zhiyicx/plus-component-h5)
- 一个更加强大的大屏 Web 界面应用 (但它是收费的) 👉 [Plus PC](https://github.com/zhiyicx/plus-component-pc)

> 如果你关注 ThinkSNS 所属公司更多开源产品，请看[ThinkSNS 官网](http://www.thinksns.com)

## 特点

Plus 是基于 Laravel 所开发，它拥有下面的几个主要特点：

1. 跟随 Laravel 一同升级，但是我们放弃 LTS 版本，长期的框架不变，虽然会趋于稳定，但是 Plus 是一款长期规划维护的开源项目，随时升级框架以便我们可以尽情的使用新的技术和特性
2. 前后端分离，Plus 安装完成只拥有功能快的 REST 接口部分，可以利用接口开发任何形态的客户端
3. 后台管理面板采用 Vue.js 开发
4. 使用 PHP 7 严格模式，以数据类型来限制开发人员的不规范开发
5. 完全符合 PSR 规范，代码风格选择的是比 PSR-2 更加严格的规范
6. 完善的文档，是的！在开源社区中， 一个开源项目的文档很重要。

## 文档

你可以在我们的文档网站上看到所有文档 👉 [https://slimkit.github.io](https://slimkit.github.io)

它被分为以下几个部分：

- [快速开始 · 安装](https://slimkit.github.io/docs/server-getting-started-installation.html)
- [指南](https://slimkit.github.io/docs/server-guides-package.html)
- [REST API v2](https://slimkit.github.io/docs/api-v2-overview.html)

当然，有一些并不在网站上，而是在 Plus 代码仓库中：

- [贡献指南 & 贡献者感谢名单](https://github.com/slimkit/plus/blob/master/.github/CONTRIBUTING.md)
- [行为守则](https://github.com/slimkit/plus/blob/master/.github/CODE_OF_CONDUCT.md)

## 安装

安装 Plus 是一件非常简单的事情，但是你要先做到以下几点必须：

- PHP 版本必须大于 `7.1.3`
- 你已下载并安装过 `Composer`
- 你拥有一个 `MySQL` 或者 `PostgreSQL` 等数据库

下载程序：

```shell
composer create-project slimkit/plus
```

基本配置：

下载完成后进入程序的 `storage/configure/` 目录，你会看到一个 `plus.yml.example` 文件，复制一份命名为 `plus.yml` 然后打开这份 Yaml 配置文件，进行你数据库等信息的配置。

生成数据表以及默认填充数据：

```shell
php artisan migrate --seed
```

软链公开磁盘并发布静态资产：

```shell
php artisan storage:link
php artisan vendor:publish --all
```

运行 Plus 程序：

```shell
php artisan serve
```

现在你可以访问 `http://127.0.0.1:8000` 查看你安装的 Plus 程序了，但是上述只是一份简单的安装，更加详细或者可以运行在
正式环境的安装指南请参阅 👉 [安装指南](https://slimkit.github.io/docs/server-getting-started-installation.html)

## 交流 & 支持

你可以申请加入官方 QQ 群进行交流，群号 `143325287`。

## 贡献

这个仓库的贡献者并不代表只是 Plus 的贡献者，我们也会把 Laravel 的贡献者加入感谢名单，因为他们为 Laravel 所做出的贡献，也使得 Plus 项目更加强大。

感谢所有为 Plus 贡献的人！
<a href="https://github.com/slimkit/plus/graphs/contributors"><img src="https://opencollective.com/plus/contributors.svg?width=890" /></a>

### [行为守则](https://github.com/slimkit/plus/blob/master/.github/CODE_OF_CONDUCT.md)

我们按照开源项目社区的建议，为 Plus 提供了我们期望参与者遵守的行为准则，请 [阅读准则](https://github.com/slimkit/plus/blob/master/.github/CODE_OF_CONDUCT.md) 全文，以便了解哪些行为是我们不会容忍的。

### [贡献指南](https://github.com/slimkit/plus/blob/master/.github/CONTRIBUTING.md)

阅读我们的 [贡献指南](https://github.com/slimkit/plus/blob/master/.github/CONTRIBUTING.md)，了解我们的开发过程，
如题提出错误修正或者建议，我们在贡献指南中包含了所有的贡献者名单。

## 赞助

首先，我们有一个特约赞助商 👉 [成都 · 智艺创想](http://www.zhiyicx.com)，该赞助商为 Plus 团队提供了一个开发的基础，提供工作场所以及提供商务处理事宜，并且开发了如下商业项目：

- ThinkSNS Plus Android 客户端
- ThinkSNS Plus iOS 客户端
- 一个强大且全面的大屏 Web 界面应用
- 问答模块
- 圈子社群模块

如果你对这些商业模块感兴趣，可以联系 QQ `3298713109` 了解更多细节。

### 赞助商

感谢所有赞助商！(如果你也支持这个项目，[√成为赞助商](https://opencollective.com/plus#sponsor))

<a href="https://opencollective.com/plus/sponsor/0/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/1/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/2/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/3/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/4/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/5/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/6/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/7/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/8/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/plus/sponsor/9/website" target="_blank"><img src="https://opencollective.com/plus/sponsor/9/avatar.svg"></a>

## 优秀项目推荐

- [PHP CORS](https://github.com/medz/cors) 专为 PHP 开发的“跨域资源共享”中间件，快速解决 PHP 设置跨域问题
- [Notadd](https://github.com/notadd/notadd) 基于 Nest.js 的微服务开发架构，异步高性能应用、AOP（面向切面编程）

## License

Plus 代码采用企业定制许可证发布，请参阅完整的[许可证文本](https://github.com/slimkit/plus/blob/master/LICENSE)

Copyright © 2018 Chengdu [ZhiYiChuangXiang](http://zhiyicx.com) Technology Co., Ltd. All rights reserved.
