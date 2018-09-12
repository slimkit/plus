---
title: 编译安装 PHP
---

本章将会带领你在 **CentOS** `7.4` 系统上编译安装 **PHP** `7.2` 环境，以及缺少的拓展安装。 

## 下载源码

我们打开[PHP 官网下载页](http://www.php.net/downloads.php)找到最新的 PHP 7.2 版本，以 7.2.9 为例：

<img :src="$withBase('/assets/img/guide/installation/php-download-page.png')" />

我们点击**绿色**框部分的的地址，最后我们通过选择地区后得到最终地址为：`http://cn2.php.net/distributions/php-7.2.9.tar.xz` 

我们得到文件下载地址后，登入 Linux，我们现在在服务器下载 PHP 源码，下载我们使用 **curl** 命令下载，检查你的服务器是否有这个命令输入 `curl --help` 如果有很大一串内容输出，则表示有该命令，如果输出内容为：

```log
-bash: curl: command not found
```

那么，我们使用 CentOS 自带的 `yum` 命令进行安装：

```bash
yum install -y curl
```

等待命令执行完成即可，执行完成后，我们下载 PHP 源码：

```bash
curl -o php-7.2.9.tar.xz http://cn2.php.net/distributions/php-7.2.9.tar.xz
```

::: warning
如果你无法找到准确的文件下载地址，那么你点击**绿色**框的链接，会进入地区选择页面，例如你选择 `China` 地区，你会看到一个 `cn2.php.net` 的链接，鼠标放上去，「右键」点击「复制链接地址」你粘贴后会得到 `http://cn2.php.net/get/php-7.2.9.tar.xz/from/this/mirror` 这样的地址，如图：

<img :src="$withBase('/assets/img/guide/installation/php-download-copy-link.png')" />

复制得到地址后，我们运行下面的命令进行下载：

```bash
curl -L -o php-7.2.9.tar.xz http://cn2.php.net/get/php-7.2.9.tar.xz/from/this/mirror
```
:::

## 解压源码

解压源码之前，请先下载解压工具：

```bash
yum install -y tar xz
```

然后我们先将 XZ 归档解压为 tar 归档：

```bash
xz -d php-7.2.9.tar.xz
```

执行完成后，我们输入 `ls` 命令，你会看到现在 `php-7.2.9.tar.xz` 文件已经消失，而多出一个 `php-7.2.9.tar` 归档。此时我们来解压这个 tar 归档：

```bash
tar -xvf php-7.2.9.tar
```

解压完成后，运行 `ls` 命令会看到，多出一个 `php-7.2.9` 的没有了，好了我们现在就得到了源码。

> 如果你解压步骤失败，可能是下载的文件不是 `.tar.xz` 后缀归档，也有可能是下载过程中数据丢包，不用担心。你重新下运行 `rm -rf php-7.2.9*` 命令，将你之前下载的删除，然后重新下载即可。

## 编译 PHP
