---
title: 安装 Nginx
---

本章将带领你在 **CentOS** `7.4` 系统中安装 **Nginx**。参考资料：

- [Building nginx from Sources](https://nginx.org/en/docs/configure.html)

## 下载

我们打开 Nginx 的「[下载页面](https://nginx.org/en/download.html)」我们找到 `Stable version` 列表中的版本，因为这是稳定版，推荐使用的版本，目前我这里最新的稳定版本是 `1.14.0` 所以我以这个版本为例进行安装。

如下图：

<img :src="$withBase('/assets/img/guide/installation/nginx-download-page.png')" />

我们右键 `nginx-1.14.0` 这个链接，选择复制链接地址，得到：

```
https://nginx.org/download/nginx-1.14.0.tar.gz
```

这样的地址，然后我们登入 CentOS 服务器，进入家目录： 

```bash
cd ~
```

进入加目录后我们执行：

```bash
curl -L -o nginx-1.14.0.tar.gz https://nginx.org/download/nginx-1.14.0.tar.gz
```

下载完成。我们这个时候可以输入 `ls` 命令查看文件列表进行确认。

接下来，我们将源码归档进行解压获得源码：

```
tar -zxvf nginx-1.14.0.tar.gz
```

我们就得到了 `~/nginx-1.14.0` 这个目录。

## 编译前配置

安装前，我们需要提前为 Nginx 创建一个运行用户和用户组（这里定义为 `nginx:nginx`，默认不登录不创建家目录）：

```bash
groupadd nginx && useradd -s /sbin/nologin -g nginx -M nginx
```

然后在我们为其安装编译所需的工具和依赖：

```bash
yum install -y gc gcc gcc-c++ \
pcre pcre-devel \
zlib zlib-devel \
openssl openssl-devel
```

安装完成依赖后，我们使用 cd 命令进入源码目录：

```bash
cd ~/nginx-1.14.0
```

## 编译

在上面的步骤中，我们以及完成了所需的安装和配置，并进入了源码目录。接下来，我们生成 `Makefile` 所需的文件：

```bash
./configure \
--user=nginx \
--group=nginx \
--prefix=/usr/local/nginx \
--sbin-path=/usr/local/nginx/nginx \
--conf-path=/usr/local/nginx/nginx.conf \
--pid-path=/usr/local/nginx/nginx.pid \
--http-log-path=/var/log/nginx/access.log \
--with-http_ssl_module \
--with-http_v2_module \
--with-http_stub_status_module \
--with-http_gzip_static_module \
--with-pcre
```

执行完成后，我们运行 `make` 命令，运行完成后我们再运行 `make install` 命令即可。

## 方便设置

现在，我们 Nginx 的启动命令文件在 `/usr/local/nginx/nginx` 这个位置，每次进入这里操作不是很方便。所以，我们可以做一个软链，然后在全局都可以找到了。

```bash
ln -s /usr/local/nginx/nginx /usr/local/sbin/nginx
```

## 命令

- `nginx` 启动 Nginx
- `nginx -t` 检查 Nginx 配置文件
- `nginx -s stop` 停止 Nginx
- `nginx -s quit` 正常退出 Nginxx
