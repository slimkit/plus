---
title: 概述
---

Plus (读音：**[plʌs]**，全称：`ThinkSNS+` **[θɪŋk es en es plʌs]**，是 ThinkSNS 系列产品一个重要版本，其软件识别名称为 `Plus` 即 `+`) 是一个基于 [Latest Laravel](https://github.com/laravel/laravel) 框架进行开发的一个功能强大、易于开发和强拓展的社交系统。与其他开源社交程序不同的是 Plus 拥有多年社交系统经验，不仅易于上手，还便于应用拓展。另一方面，程序采用 PHP 7 严格模式，从根本上尽量避免弱级错误的产生。同时因为从零开始选择较好的带有较好 ORM 的原因，Plus 允许你更具你的需求使用不同数据库。

如果你想深入学习 Plus，我们为你准备了大量教程级文档。哪怕你不会 Laravel 框架，也能让你入门框架基础，并胜任 Plus 应用开发。

如果你是有经验的 PHPer，那么你可以了解**现代流行框架**差异，Laravel 就是现代留下框架的佼佼者之一。

## PHP 环境要求

::: danger 重点
你可能还没有很好的 Liunx 知识，没关系，后面的教程会拟定你是**零基础**的前提下教学，但是下面的幻想要求限制，你需要重点记忆，这是程序能否运行的关键所在！
:::

### PHP 版本

您的 PHP 必须大于或者等于 **7.1.3** 版本，当然，我们推荐 **7.2.** 版本。

### 函数

在许多集成环境中，默认会禁用一些函数，当然，这些函数在 Plus 的 Web 服务中不是必须的，但是如果你在 **CLI** 环境下操作，这些函数将会成为必须：

- `exec`
- `system`
- `scandir`
- `symlink`
- `shell_exec`
- `proc_open`
- `proc_get_status`

### 拓展

这些拓展是 Plus 运行时必须的拓展，你必须包装你的 PHP 环境已安装这些拓展：

- `dom` 用于解析 XML 等文档使用
- `fileinfo` 用于文件上传，或者获取文件 Meta 信息使用
- `GD` 用于图片处理的库
- `json` 一般 PHP 内核自带，用于处理 JSON 文档和转换
- `mbstring` 用于兼容性的字符串处理
- `openssl` 用于应用秘钥加密等，同时也是内部请求 HTTPS 资源使用
- `PDO` 数据库操作抽象库

#### 选择性拓展

选择性拓展是更具你的需求，选择性安装的拓展，选择性拓展如下：

- `pdo_mysql` 如果你使用 MySQL 数据库，则必须安装
- `pdo_pgsql` 如果你使用 PostgreSQL 数据库，则必须安装
- `pdo_sqlite` 如果你使用 SQLite 数据库，则必须安装
- `pdo_dblib` 如果你使用 Microsoft SQL Server 数据库，则必须安装

#### 可选或替换性拓展

- `imagick` 此库是一个比 GD 库处理图片更好的一个拓展，可以用于替换 GD 库，此库为可选性，安装后不可卸载 GD 库，GD 库可以用于更加高效的图片基础处理。

## 数据库

Plus 支持四种数据库的使用，但是我仅推荐使用两个数据库。

- `MySQL` | `MariaDB`
    - `MySQL` 请使用 **>= 5.7** 版本，当然，如果能用 MySQL 8 再好不过。
    - `MariaDB` 是 MySQL 原作者后开发的一款关系型数据库，兼容 MySQL，如果你要使用，请选择大于或等于 **10.3** 版本。
- `PostgreSQL` 数据库是及其推荐的一个数据库，费用高昂但是起计算性能非常好，有条件可以直接使用。
- `SQLite` 是一个轻量级数据库，如果你只是想体验倒是不妨尝试，问题在于 SQLite 对于 Emoji 的储存有待改善（目前都不支持存储 Emoji）所以原则上 Plus 是不允许使用 SQLite 的。
- `Microsoft SQL Server` 就像不推荐 SQLite 一样，我们同样不推荐 Microsoft SQL Server 除非你确定你的系统不适用 Emoji 那么你可以无顾虑的使用 Microsoft SQL Server 了，因为 Microsoft SQL Server 同样支持 utf8 字符集，却无法支持四位长度的 Emoji 字符。

::: warning
😭答应我，在 SQLite 和 Microsoft SQL Server 没有很好默认支持 Emoji 之前，暂时用 MySQL 或者 PostgreSQL 好吗？我是认真的！不然你会遇到很多奇怪的问题！
:::

## 教程说明

从后面的教程开始，我们将从**零基础**开始教学，教学环境为 **CentOS** `7.4` 版本的 Linux。教学环境如下：

- 教学使用服务器系统: **CentOS** `7.4`
- 教学者使用系统: **macOS**
- SSH 工具: [**Termius**](https://itunes.apple.com/cn/app/id549039908/)
- 教学 [PHP](http://php.net) 版本：**7.2**
- 教学 [MySQL](https://www.mysql.com/) 版本： **5.7**
- 教学 Web 组件：[**Nginx**](http://nginx.org/)

教学内容包括：

- 登入 Linux
- 安装 PHP 7.2
- 安装 MySQL 5.7
- 安装 Plus
- Nginx 安装
- 发布站点

## 登入 Linux

这里虚拟一台主机，其 IP 地址我们假设为 `hostname`（正常情况下是一段 IP 地址），端口为 `22`，操作用户为 `root`。

在你的 SSH 工具中输入 ssh root@hostname -p 22 然后回车。然后输入你的主机密码即可。

> `hostname` 等信息登录的时候替换为你的真实信息。

我们进入主机后，会进入用户**家目录**，如果你不知道家目录在说明为主，输入 `pwd` 命令即可看到。
