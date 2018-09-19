---
title: 安装 MySQL
---

本章将带领你在 **CentOS** `7.4` 系统中安装 **MySQL** 5.7 数据库。参考资料：

- [Linux 二进制安装 MySQL](https://dev.mysql.com/doc/refman/5.7/en/binary-installation.html)

## 下载

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

## 解压

执行完成上面的步骤后，我们就下载完了 MySQL 5.7 源码，接下里我们对源码归档进行解压处理。首先运行：

```bash
tar -zxvf mysql-5.7.23-linux-glibc2.12-x86_64.tar.gz
```

执行完成后运行 `ls` 命令你会看到一个 `mysql-5.7.23-linux-glibc2.12-x86_64` 的目录，那么，我们以及获取到源代码了！

## 初始化

在运行初始化之前，我们需要安装一个链接库 `libaio` 这个库是 MySQL 二进制安装必须的库：

```bash
yum install -y libaio
```

安装完成后我们还需要为 MySQL 提供用户和用户组来运行（我这里设置为 `mysql:mysql`）：

```bash
groupadd mysql && useradd -r -g mysql -s /bin/false mysql
```

接下来，我们将程序放到一个比较统一的位置，推荐放置在 `/usr/local` 目录下：

```bash
mv -f ~/mysql-5.7.23-linux-glibc2.12-x86_64 /usr/local/mysql
```

然后创建 MySQL 需要的文件目录并设置权限和用户：

```bash
mkdir /usr/local/mysql/mysql-files && \
chown mysql:mysql /usr/local/mysql/mysql-files && \
chmod 750 /usr/local/mysql/mysql-files
```

现在，我们来初始化数据库吧：

```bash
/usr/local/mysql/bin/mysqld --initialize --user=mysql && \
/usr/local/mysql/bin/mysql_ssl_rsa_setup
```

执行完成后，从页面输出中找到 `[Note] A temporary password is generated for root@localhost: icXadLT)-0mx` 这样一条信心，例如这条 Example 信息中 `icXadLT)-0mx` 这一段就是默认的 root 帐号密码。

## 一些方便设置

当然，MySQL 在 `bin` 目录下提供了很多好用的命令，我们每次都全路径指定很繁琐，所以加入到环境变量是一个很好的注意。

使用 `vim /etc/profile` 打开文件，在结尾写入：

```bash
export PATH=$PATH:/usr/local/mysql/bin/
```

然后执行 `source /etc/profile` 即可。

## 启动 MySQL

我们可以使用 `bin/mysqld_safe --user=mysql &` 来启动，但是我们先不要这样子。我们有更好的和安全的非法：

```bash
mv -f /usr/local/mysql/support-files/mysql.server /etc/init.d/mysql.server
```

执行完成后，我们执行：

```bash
/etc/init.d/mysql.server
```

会输出下面的结果：

```
Usage: mysql.server  {start|stop|restart|reload|force-reload|status}  [ MySQL server options ]
```

我们通过这种服务命令来启动数据库是最好的现在执行来启动吧：

```bash
/etc/init.d/mysql.server start
```

## 登入数据库

启动完成后，我们试一下是否正常启动了呢：

```
mysql -u root -h localhost -P 3360 -p
```

然后会出现一个密码输入，现在输入之前初始化的时候得到的 root 账号密码，即可成功进入数据库啦！
