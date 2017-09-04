# ThinkSNS+ 安装部署

- [祖传部署](#祖传部署)
- [Laradock](#laradock)

> 首先，我们推荐使用 [Laradock](#Laradock) 进行部署程序，将持续运行在 Docker 容器之中。

## 祖传部署

这里会讲如何在服务器中或者你的电脑中使用集成环境又或者手动编译环境来对 ThinkSNS+ 进行安装。

#### 环境要求

##### PHP & 拓展

- PHP 必须大于或等于 7.0
- 必须安装扩展 dom
- 必须安装扩展 fileinfo
- 必须安装扩展 gd
- 必须安装扩展 json
- 必须安装扩展 mbstring
- 必须安装扩展 openssl
- 必须安装 PDO
- 使用 MySQL 数据库则必须安装 PHP 扩展 pdo_mysql
- 使用 PostgreSQL 数据库则必须安装 PHP 扩展 pdo_pgsql
- 使用 SQLite 数据库则必须安装 PHP 拓展 pdo_sqlite
- 使用 SQL Server 数据库则必须安装 PHP 拓展 pdo_dblib

###### MySql

使用 MySQL 建议使用 `>=5.7` 版本，必须 `>=5.6` 版本，如果你的是 5.6 版本，则自行解决索引过长导致的 SQL 执行错误问题。

> 解决方法，修改 `my.cnf` 中的索引长度（尺寸）

###### MariaDB

使用 MariaDB 必须 `>=10.1` 版本，因为只有该版本是建立在 MySQL 5.6 & 5.7 之上的，得以支持 Emoji。

> 使用 MariaDB 按照 MySQL 进行配置即可。

###### PostgreSQL

PostgreSQL 数据库天然支持 Emoji，无需任何版本要求，但是我们还是建议使用最新版本的 PostgreSQL 稳定版本的以支持更完善的空间计算。

###### SQLite

首先，这个数据库不建议使用，因为这种轻量级的数据库适合在 App 中来解决数据本地化需求，服务器应用场景很小。

> 虽然 ThinkSNS+ 不允许使用 SQLite，但是您仍然可以在系统中使用该数据库，但是例如 Emoji 储存等问题自行解决。

###### Microsoft SQL Server

就像不推荐 SQLite 一样，我们同样不推荐 Microsoft SQL Server 除非你确定你的系统不适用 Emoji 那么你可以无顾虑的使用 Microsoft SQL Server 了，因为 Microsoft SQL Server 同样支持 utf8 字符集，却无法支持四位长度的 Emoji 字符。

> ThinkSNS+ 不建议使用 SQL Server，但是你仍然可以在系统中使用，出现的 emoji 存储问题自行解决。

##### 需要的函数

`exec`, `system`, `scandir`, `shell_exec`, `proc_open`, `proc_get_status`

> 这些是在 Console 环境下使用的，尽量确保你的系统没有禁止。

#### 前端网络组件（Nginx/Apache/Caddy）

本地测试环境推荐使用 Caddy, 这是一个使用 Golang 实现的 http 软件，只需要一个文件即可运行，省去了类似 nginx 这样复杂的安装和配置。

###### Nginx

```conf
location / {
    try_files $uri $uri/ /index.php$is_args$args;
}

location ~ \.php$ {
    try_files $uri /index.php =404;
    ...
}
``` 

> 上面的 `...` 代表常规的 Fast CGI 配置

Example:

```conf
server {
    listen 443 ssl http2;
    server_name plus.io;
    ssl on;
    ssl_certificate /var/www/plus.crt;
    ssl_certificate_key /var/www/plus.key.unsecure;
    root /var/www/public;
    index index.php index.html index.htm;

    location / {
         try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

其中 `plus.io` 替换为你的 **网址**，`/var/www/public` 替换为你下载的 ThinkSNS+ 的 `public` 目录，`SSL` 证书也是同理哟。

> 启用 http2 需要 Nginx 1.13.0 以上哟，因为 1.12.0 中支持是有问题的。


###### Apache

在 ThinkSNS+ 中，已经在根目录（`/plulic`）中已经提供了 `.htaccess` 文件，其中已经为您配置好了优雅的地址配置。如果在你的 Apache 中不生效或者由其他位置提供配置，请设置：

```
Options +FollowSymLinks
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

###### Caddy

Caddy 是一个小巧精悍的 http 软件，在开发环境，测试环境等下也是我们推荐使用的软件。因为它无需特殊的安装，无需特殊的配置，您只需下载一个 Caddy 运行文件，写一份你的站点配置即可运行。

> Caddy is the HTTP/2 web server with automatic HTTPS.

```conf
fastcgi / localhost:9000 php { 
    index index.php
}

# To handle .html extensions with laravel change ext to
# ext / .html

rewrite { 
    r .*
    ext /
    to /index.php?{query}
}

```

#### 下载安装

>
> **安装前注意**
> 安装前倾确保您已安装了 `git`、`php-cli`、`composer`，否则无法进行安装。
>

##### 克隆源代码

```
git clone https://github.com/zhiyicx/thinksns-plus
```

克隆完毕后会在你执行命令的所在目录下建立一个 `thinksns-plus` 目录，里面存放了一份最新的 ThinkSNS+ 源码，并依赖于 Git 进行了版本控制，您可以在合适的时候在该目录下执行 `git pull` 来下载最新程序。

> 克隆完成后 `cd thinksns-plus` 然后讲 `.env.example` 复制为 `.env` 文件，并修改其中的配置

- APP_URL 网址
- DB_CONNECTION 数据库驱动
- DB_HOST 数据库连接主机
- DB_PORT 数据库连接端口
- DB_DATABASE 数据库名称，确保是已存在并且无任何内容的数据库。
- DB_USERNAME 连接数据库用户名
- DB_PASSWORD 连接数据库用户密码

将你的 **网址** 绑定到 `/thinksns-plus/public` 目录下

>
> `/thinksns-plus/public` 目录必须为 http 组件设置的根目录
> 这么做可以让你的站点更安全。
>


###### 安装依赖

> 因为中国大陆地区的网络环境原因，推荐使用 [Packagist / Composer 中国全量镜像](https://pkg.phpcomposer.com/) 来保证 Composer 的正常下载。

1. 下载并安装依赖
    ```shell
    composer install
    ```
2. 设置应用秘钥
    ```shell
    php artisan key:generate
    ```
3. 发布资源
    ```shell
    php artisan vendor:publish
    ```
4. 迁移数据表
    ```shell
    php artisan migrate
    ```
5. 填充数据
    ```shell
    php artisan db:seed
    ```

至此您已久安装完成了，现在输入 `{APP_URL}/admin` 进入后台看看吧

> 进入后台的账号密码都是 `root`

## Laradock

Laradock 是一套完整的在 Docker 下的 PHP 环境，支持繁多的数据库引擎和 http 软件以及多版本的 PHP 支持。

### 安装 Docker

Docker 是你在你的电脑或者服务器上唯一需要安装的软件，如何安装情阅读 [Docker 安装官方文档](https://docs.docker.com/engine/installation/) 中找到您电脑或者服务器的安装方式进行安装。

> 我们推荐安装的版本是 `Docker CE` 版本，该版本在服务器上支持性更好。

### 安装 Laradock

首先我们看推荐阅读 [Laradock 官方安装文档](http://laradock.io/getting-started/) 来了解您需要何种安装，而本文只会讲解以 git submodule 的方式对 ThinkSNS+ 的环境部署。

### 下载 ThinkSNS+

```shell
git clone https://github.com/zhiyicx/thinksns-plus
```

```shell
cd thinksns-plus
```

### 添加 Laradock

```shell
git submodule add https://github.com/Laradock/laradock && cd laradock
```

### 配置 Laradock

```
cp env.example .env && vi .env
```

然后根据你的实际环境修改变量

### 运行

到这一步，您基本已经部署完成了。

```shell
docker-compose up -d nginx mysql
```

进入工作区：
```shell
docker0compose exec workspace bash
```

初始化 ThinkSNS+

```shell
composer install
php artisan key:generate
php artisan vendor:publish
php artisan migrate
php artisan db:seed
```

好了，现在已经利用 Laradock 部署完成，但是这是最简单的部署，已久推荐去看 Laradock 官方文档，你可以最大化的友好部署。
