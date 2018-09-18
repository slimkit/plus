---
title: 安装 MySQL
---

本章将带领你在 **CentOS** `7.4` 系统中安装 **MySQL** 5.7 数据库。参考资料：

- [源码安装](https://dev.mysql.com/doc/refman/5.7/en/source-installation.html)
- [使用 Systemd](https://dev.mysql.com/doc/refman/5.7/en/using-systemd.html)
- [编译选项](https://dev.mysql.com/doc/refman/5.7/en/source-configuration-options.html)
- [`my.cnf` 配置文件说明](https://dev.mysql.com/doc/refman/5.7/en/binary-installation.html)

## 下载源码

首先我们打开「[MySQL 5.7 下载页面](https://dev.mysql.com/downloads/mysql/5.7.html#downloads)」然后在英文界面有三个选择框，分别是 `Select Version`、`Select Operating System` 和 `Select OS Version`，那么 `Select Version` 以及默认为我们选择好了，是 `5.7.*` 这里的 `*` 是恩和版本，如果不是 `5.7` 开头，请点击选择 `5.7` 开头的版本。`Select Operating System` 这里我们点击后选择 `Linux - Generic` 即可，`Select OS Version` 系统版本我们下啦选择 `Linux - Generic (glibc 2.12)(x86, 64-bit)` 即可。如图：

<img :src="$withBase('/assets/img/guide/installation/mysql-download-page.png')" />

请保证除了 `Select Version` 以外，其他选择必须与上图选择一致，然后看在你的页面中找到上图中**青蓝色**框标记的 `Download` 按钮，点击这个按钮，打开页面如下：

<img :src="$withBase('/assets/img/guide/installation/mysql-download-page-get-archival.png')" />

找到页面底部的 `No thanks, just start my download.` 文字，右键复制链接地址我们得到 `https://dev.mysql.com/get/Downloads/MySQL-5.7/mysql-5.7.23-linux-glibc2.12-x86_64.tar.gz` 这样的文件。

然后我们登入 CentOS 服务器，输入 `cd ~` 命令进入家目录，然后下载该文件：

```bash
curl -L -o mysql-5.7.23-linux-glibc2.12-x86_64.tar.gz https://dev.mysql.com/get/Downloads/MySQL-5.7/mysql-5.7.23-linux-glibc2.12-x86_64.tar.gz
```

> 注意，我这里是 `5.7.23` 版本，当你阅读教程的时候可能不是 23 版本了，记得在命令中替换为你的版本号！
> 同时，如果你的系统不是 64 bit 请选择 32 bit 版本。

## 解压源码

执行完成上面的步骤后，我们就下载完了 MySQL 5.7 源码，接下里我们对源码归档进行解压处理。首先运行：

```bash
tar -zxvf mysql-5.7.23-linux-glibc2.12-x86_64.tar.gz
```

执行完成后运行 `ls` 命令你会看到一个 `mysql-5.7.23` 的目录，那么，我们以及获取到源代码了！

