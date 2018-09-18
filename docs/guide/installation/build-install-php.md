---
title: 安装 PHP
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

解压完成后，运行 `ls` 命令会看到，多出一个 `php-7.2.9` 的目录了，好了我们现在就得到了源码。

> 如果你解压步骤失败，可能是下载的文件不是 `.tar.xz` 后缀归档，也有可能是下载过程中数据丢包，不用担心。你重新下运行 `rm -rf php-7.2.9*` 命令，将你之前下载的删除，然后重新下载即可。

## 编译 PHP

编译 PHP 之前我们需要安装 PHP 编译工具和依赖：

```bash
yum install -y gcc autoconf gcc-c++ \
libxml2 libxml2-devel \
openssl openssl-devel \
bzip2 bzip2-devel \
libcurl libcurl-devel \
libjpeg libjpeg-devel \
libpng libpng-devel \
freetype freetype-devel \
gmp gmp-devel \
readline readline-devel \
libxslt libxslt-devel \
libmcrypt libmcrypt-devel \
mhash mhash-devel \
systemd-devel openjpeg-devel
```
安装完编译工具和依赖后，我们需要为 FPM 分配一个运行用户和用户组（我们取名 `php-fpm:php-fpm` 并设置不予怒登录和不创建家目录）：

```bash
groupadd php-fpm && useradd -s /sbin/nologin -g php-fpm -M php-fpm
```

现在，我们使用 `cd php-7.2.9` 进入源码目录，上面都是开始编译前的必要设置，但是我们还有一步没有完成，就是生成编译配置（如果你很想知道下面的参数有什么用，请访问[这里](https://www.cnblogs.com/zsl123/p/5962944.html)查看参数详解）：

```bash
./configure \
--prefix=/usr/local/php \
--with-config-file-path=/usr/local/php/etc \
--with-zlib-dir \
--with-freetype-dir \
--enable-mbstring \
--with-libxml-dir=/usr \
--enable-xmlreader \
--enable-xmlwriter \
--enable-soap \
--enable-calendar \
--with-curl \
--with-zlib \
--with-gd \
--with-pear \
--with-pdo-sqlite \
--with-pdo-mysql \
--with-mysqli \
--with-mysql-sock \
--enable-mysqlnd \
--disable-rpath \
--enable-inline-optimization \
--with-bz2 \
--with-zlib \
--enable-sockets \
--enable-sysvsem \
--enable-sysvshm \
--enable-pcntl \
--enable-mbregex \
--enable-exif \
--enable-bcmath \
--with-mhash \
--enable-zip \
--with-pcre-regex \
--with-jpeg-dir=/usr \
--with-png-dir=/usr \
--with-openssl \
--enable-ftp \
--with-kerberos \
--with-gettext \
--with-xmlrpc \
--with-xsl \
--enable-fpm \
--with-fpm-user=php-fpm \
--with-fpm-group=php-fpm \
--with-fpm-systemd \
```

等待完成，然后我们执行编译：

```
make
```

这个过程会非常的缓慢，主要是看机器，你的服务器配置比较好就很快，一般而言差不多半小时到两小时都是正常的。执行完成后，我们推荐执行一次 `make test` 命令，如果你觉得没必要浪费时间，请直接执行：

```bash
make install
```

执行完成后，你会看到 `php-7.2.9` 目录下有 `php.ini-development` 和 `php.ini-production` 两个文件，因为我们是教程，所以选择开发环境的配置文件:

```bash
cp php.ini-development /usr/local/php/etc/php.ini
```

复制完成后，我们进入 `/usr/local/php/etc` 目录：

```bash
cd /usr/local/php/etc
```

并执行 `ls` 命令，你会看到有一个 `php-fpm.conf.default` 文件，这个是 FPM 配置文件，目前是不会被加载的，我们执行：

```bash
cp php-fpm.conf.default php-fpm.conf
```

将其复制为可使用的配置文件，接着允许 `yum install -y vim` 下载一个编辑器，下载完成后我们执行：

```bash
vim php-fpm.conf
```

会进入编辑模式，如果你不会用 Linux 下的 Vim 软件，请自信使用搜索引擎学习，编辑内容如下：

错误日志：

```
error_log = /usr/local/php/var/log/php-fpm.log
```

PID 文件配置

```
pid = /usr/local/php/var/run/php-fpm.pid
```

然后保存并退出，我们再只 `cd /usr/local/php/etc/php-fpm.d` 进入 FPM 配置目录，这个目录下有一个 `www.conf.default` 文件，我们执行 `cp www.conf.defailt www.conf` 命令将其发布为可被加载的配置文件。

接下来，我们需要对 FPM 做一些系统级的配置，我们进入之前解压的 PHP 源码目录，如果你跟随教程执行下来，应该在 `/root/php-7.2.9` 目录，如果不是，自行进入你下载后解压的所在目录。进入该目录后，我们复制服务文件：

```bash
cp ./sapi/fpm/php-fpm.service /usr/lib/systemd/system/
```

复制成功后，我们来设置开机启动 FPM：

```bash
systemctl enable php-fpm
```

当然，我们现在直接执行 `php -v` 还无法找到 PHP 命令，所以，我们编辑将 `/usr/local/php/bin/` 加入到环境变量：

```
vim /etc/profile
```

打开文件后我们在结尾写入：

```
export PATH=$PATH:/usr/local/php/bin/
```

写入后，并不会立刻生效，所以我们执行 `source /etc/profile` 执行完成后，我们运行 `php -v` 会输出 PHP 版本信息，差不多下面这样子：

```
PHP 7.2.9 (cli) (built: Sep 18 2018 12:16:25) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
```

现在，我们需要检查环境是否符合我们 Plus 程序的需要，所以请将下面的内容写入到测试脚本中，测试脚本我命名为 `plus-test.php` 内容如下：

```php
<?php

declare(strict_types=1);

echo 'exec function: ', function_exists('exec') ? '1' : '0', PHP_EOL;
echo 'system function: ', function_exists('system') ? '1' : '0', PHP_EOL;
echo 'scandir function: ', function_exists('scandir') ? '1' : '0', PHP_EOL;
echo 'symlink function: ', function_exists('symlink') ? '1' : '0', PHP_EOL;
echo 'shell_exec function: ', function_exists('shell_exec') ? '1' : '0', PHP_EOL;
echo 'proc_open function: ', function_exists('shell_exec') ? '1' : '0', PHP_EOL;
echo 'proc_get_status function: ', function_exists('proc_get_status') ? '1' : '0', PHP_EOL;
```

> 注意，上面需要的函数都是 Console 也就是 CLI 环境下使用，这里配置细节之后会详细讲解。

写入进去并保存退出后，我们执行 `php plus-test.php` 输出内容正确的应该如下：

```
exec function: 1
system function: 1
scandir function: 1
symlink function: 1
shell_exec function: 1
proc_open function: 1
proc_get_status function: 1
```

如果你按照教程进行安装，到这里输出结果都会如上。自行安装的请百度 php 解锁禁用函数。

到这里，我们编译安装 PHP 就算完成
