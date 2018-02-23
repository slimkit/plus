<p align="center"><img src="https://github.com/slimkit/thinksns-plus/raw/master/public/plus.png"></p>

Plus (ThinkSNS Plus) 是一个使用 Laravel 开发，并且功能繁多且健壮的社交程序。Plus 是遵循 **PSR 规范** 代码统一，并功能块松耦合。你安装完成 Plus 并不意味着已经成功安装了所有功能，因为 Plus 使用 **模块化** 的
原则，所以你安装完成后拥有了所有可以被请求的 REST 接口和后台管理面板，你可能还需要安装如下模块：

- 一个对搜索引擎支持良好的大屏 Web 界面 👉 [ThinkSNS Plus PC](https://github.com/zhiyicx/plus-component-pcos)
- 极大程度还原原生 APP 体验的 SPA (Single Page Application) 应用 👉 [ThinkSNS Plus SPA](https://github.com/zhiyicx/plus-component-h5)
- 一个更加强大的大屏 Web 界面应用 (但它是收费的) 👉 [Plus PC](https://github.com/zhiyicx/plus-component-pc)

> 如果你关注 ThinkSNS 所属公司更多开源产品，请看[ThinkSNS 官网](http://www.thinksns.com)

## Badges

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/8320deaa80b8489f95fcedaae6df079d)](https://www.codacy.com/app/zhiyi/thinksns-plus?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=slimkit/thinksns-plus&amp;utm_campaign=Badge_Grade)
[![Style CI](https://styleci.io/repos/76627423/shield?branch=master)](https://styleci.io/repos/76627423)
[![Composer publish version](https://img.shields.io/packagist/v/zhiyicx/thinksns-plus.svg?style=flat-square)](https://packagist.org/packages/zhiyicx/thinksns-plus)
[![Composer publish preview version](https://img.shields.io/packagist/vpre/zhiyicx/thinksns-plus.svg?style=flat-square)](https://packagist.org/packages/zhiyicx/thinksns-plus)

Travis CI: [![Travis CI Build Status](https://img.shields.io/travis/slimkit/thinksns-plus.svg?style=flat-square)](https://travis-ci.org/slimkit/thinksns-plus)

Circle CI: [![https://img.shields.io/circleci/project/github/slimkit/thinksns-plus.svg?style=flat-square](https://img.shields.io/travis/slimkit/thinksns-plus.svg?style=flat-square)](https://circleci.com/gh/slimkit/thinksns-plus)

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

- [贡献指南 & 贡献者感谢名单](https://github.com/slimkit/thinksns-plus/blob/master/CONTRIBUTING.md)
- [行为守则](https://github.com/slimkit/thinksns-plus/blob/master/CODE_OF_CONDUCT.md)

## 安装

安装 Plus 是一件非常简单的事情，但是你要先做到以下几点必须：

1. PHP 版本必须大于 `7.0.12`
2. 你已下载并安装过 `Composer`
3. 你拥有一个 `MySQL` 或者 `PostgreSQL` 等数据库
4. 你必须安装一个 Git 命令行工具

使用 Git 在你的电脑中执行克隆仓库命令：

```shell
git clone https://github.com/slimkit/thinksns-plus
```

进入该 Plus 目录并使用 Composer 进行依赖安装：

```shell
cd thinksns-plus
cp .env.example .env
composer install
```

现在，打开你的 `.env` 文件配置数据库等各类信息。

生成应用秘钥：

```shell
php artisan key:generate
php artisan jwt:secret --force
```

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

针对不同的用户，我们准备了两个 QQ 群，分别是：

- 技术交流群：`NjUxMjQwNzg1`，该群适合技术交流，不接受普通用户加入。（解码提示：`*toa`）
- 用户交流群：`638051536`，该群所有人都可以加入并进行交流。

## 贡献

这个仓库的贡献者并不代表只是 Plus 的贡献者，我们也会把 Laravel 的贡献者加入感谢名单，因为他们为 Laravel 所做出的贡献，也使得 Plus 项目更加强大。

感谢所有为 Plus 贡献的人！
<a href="graphs/contributors"><img src="https://opencollective.com/thinksns-plus/contributors.svg?width=890" /></a>

### [行为守则](https://github.com/slimkit/thinksns-plus/blob/master/CODE_OF_CONDUCT.md)

我们按照开源项目社区的建议，为 Plus 提供了我们期望参与者遵守的行为准则，请 [阅读准则](https://github.com/slimkit/thinksns-plus/blob/master/CODE_OF_CONDUCT.md) 全文，以便了解哪些行为是我们不会容忍的。

### [贡献指南](https://github.com/slimkit/thinksns-plus/blob/master/CONTRIBUTING.md)

阅读我们的 [贡献指南](https://github.com/slimkit/thinksns-plus/blob/master/CONTRIBUTING.md)，了解我们的开发过程，
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

感谢所有赞助商！(如果你也支持这个项目，[√成为赞助商](https://opencollective.com/thinksns-plus#sponsor))

<a href="https://opencollective.com/thinksns-plus/sponsor/0/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/1/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/2/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/3/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/4/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/5/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/6/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/7/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/8/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/thinksns-plus/sponsor/9/website" target="_blank"><img src="https://opencollective.com/thinksns-plus/sponsor/9/avatar.svg"></a>

## 优秀仓库推荐

- [Notadd](https://github.com/notadd/notadd) 基于 Laravel 的下一代开发框架。

## 开源协议

ThinkSNS Plus 代码遵循 Apache 2.0 许可证发布，请参阅完整的 [许可证文本](https://github.com/slimkit/thinksns-plus/blob/master/LICENSE)
