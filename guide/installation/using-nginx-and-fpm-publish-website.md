---
title: 使用 Nginx + FPM 发布站点
---

在前面的章节中，我们在 **CentOS** `7.4` 的系统上安装了如下软件：

- **PHP** 7.2
- **MySQL** 5.7
- **Nginx** 1.15

并且，我们以 PHP-CLI 安装并使用 PHP 内嵌 Server 运行了预览站点，本章利用前面所学的知识，进行一个完整站点的发布。

## PHP-FPM 启动

在发布前，我们需要运行一个 FastCGI 程序，这个程序就是我们在安装 PHP 的时候安装的 FPM 了，这个专门用于以 CGI 进行 PHP 程序的处理。现在我们运行下面的命令查看 FPM 状态：

```bash
systemctl status php-fpm
```

教程中无法保证你的服务器上的 FPM 是否已经被启动，我们可以运行：

```bash
systemctl kill php-fpm
```

把 FPM 相关的进程都杀死，接下来，我们来启动 FPM 程序把：

```bash
systemctl start php-fpm
```

在之前安装的 FPM 中，我们使用的默认端口，所以现在运行 `netstat -anp | grep 9000` 命令，看是否输出如下内容：

```
tcp        0      0 127.0.0.1:9000          0.0.0.0:*               LISTEN      19854/php-fpm: mast
```

如果输出大概类似上面的内容，则说明我们已经成功运行了，如果你还是不确定，我们可以运行 `systemctl status php-fpm` 命令，你会看到类似下面的内容：

```
php-fpm.service - The PHP FastCGI Process Manager
   Loaded: loaded (/usr/lib/systemd/system/php-fpm.service; disabled; vendor preset: disabled)
   Active: active (running) since Wed 2018-09-26 11:26:30 CST; 5s ago
 Main PID: 19854 (php-fpm)
   Status: "Ready to handle connections"
   CGroup: /system.slice/php-fpm.service
           ├─19854 php-fpm: master process (/usr/local/php/etc/php-fpm.conf)
           ├─19855 php-fpm: pool www
           └─19856 php-fpm: pool www

Sep 26 11:26:30 iZbp1dknyxm2bmfb4tlxjcZ systemd[1]: Starting The PHP FastCGI Process Manager...
Sep 26 11:26:30 iZbp1dknyxm2bmfb4tlxjcZ systemd[1]: Started The PHP FastCGI Process Manager.
```

## 增加 Nginx Server

首先，我们应该清除 Ngin 默认配置中的 server，因为我们之前将 Nginx 安装在 `/usr/local/nginx` 目录中，所以我们使用 Vim 打开 `/usr/local/nginx/nginx.conf` 文件，你会看到如下内容：

```nginx
#user  nobody;
worker_processes  1;

#error_log  logs/error.log;
#error_log  logs/error.log  notice;
#error_log  logs/error.log  info;

#pid        logs/nginx.pid;


events {
    worker_connections  1024;
}


http {
    include       mime.types;
    default_type  application/octet-stream;

    #log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
    #                  '$status $body_bytes_sent "$http_referer" '
    #                  '"$http_user_agent" "$http_x_forwarded_for"';

    #access_log  logs/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    #keepalive_timeout  0;
    keepalive_timeout  65;

    #gzip  on;

    server {
        listen       80;
        server_name  localhost;

        #charset koi8-r;

        #access_log  logs/host.access.log  main;

        location / {
            root   html;
            index  index.html index.htm;
        }

        #error_page  404              /404.html;

        # redirect server error pages to the static page /50x.html
        #
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }

        # proxy the PHP scripts to Apache listening on 127.0.0.1:80
        #
        #location ~ \.php$ {
        #    proxy_pass   http://127.0.0.1;
        #}

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        #location ~ \.php$ {
        #    root           html;
        #    fastcgi_pass   127.0.0.1:9000;
        #    fastcgi_index  index.php;
        #    fastcgi_param  SCRIPT_FILENAME  /scripts$fastcgi_script_name;
        #    include        fastcgi_params;
        #}

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        #location ~ /\.ht {
        #    deny  all;
        #}
    }


    # another virtual host using mix of IP-, name-, and port-based configuration
    #
    #server {
    #    listen       8000;
    #    listen       somename:8080;
    #    server_name  somename  alias  another.alias;

    #    location / {
    #        root   html;
    #        index  index.html index.htm;
    #    }
    #}


    # HTTPS server
    #
    #server {
    #    listen       443 ssl;
    #    server_name  localhost;

    #    ssl_certificate      cert.pem;
    #    ssl_certificate_key  cert.key;

    #    ssl_session_cache    shared:SSL:1m;
    #    ssl_session_timeout  5m;

    #    ssl_ciphers  HIGH:!aNULL:!MD5;
    #    ssl_prefer_server_ciphers  on;

    #    location / {
    #        root   html;
    #        index  index.html index.htm;
    #    }
    #}

}
```

我们把配置中的 server 项全部添加 `#` 在前面进行注释。然后在 http 这个块中增加一条规则如下：

```nginx
# ...
http {
    # ...
    include vhost/*.conf;
    # ...
}
```

> 上面的其他配置我用 `# ...` 代替，增加的是 `include vhost/*.conf` 这一条！

接下来，我们运行下面的命令创建目录，并将所有权赋予给 nginx 用户：

```bash
mkdir /usr/local/nginx/vhost && \
chown nginx:nginx /usr/local/nginx/vhost
```

接下来，我们使用 `touch /usr/local/nginx/vhost/plus.conf` 命令创建一个名为 `plus.conf` 的文件在 `vhost` 文件夹下，并写入下面的内容：

```nginx
server {
    listen 80;
    server_name localhost; # 暂时使用 localhost，之后替换为域名。
    root /usr/local/src/plus/public; # 这里设置我们 Plus 程序目录下的 public 目录绝对位置
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_buffers 16 16k; # 这里以后需要酌情调整
        fastcgi_buffer_size 32k;# 这里以后需要酌情调整
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

写入完成后，我们输入 `nginx -t` 命令来检查一下配置是否正确，如正确，会输出下面的内容：

```
nginx: the configuration file /usr/local/nginx/nginx.conf syntax is ok
nginx: configuration file /usr/local/nginx/nginx.conf test is successful
```

如果你 Nginx 已经运行请来了，请运行 `nginx -s stop` 进行关闭！

## 站点权限

我们在运行站点前，需要对一些 Plus 软件目录的权限做一些修改，当然，这不是必须的，本步骤也可能不是很全面，在运行过程中可以逐步完成！

下面所说的目录，都是基于 `/usr/local/src/plus` 为根，为了避免一些错误，请你现在运行 `cd /usr/local/src/plus` 命令，进入该目录。

1. 赋予权限目录所属用户
    ```bash
    chown -R php-fpm:php-fpm /usr/local/src/plus
    ```
2. 设置特定目录权限
    ```bash
    chmod -R 0777 /usr/local/src/plus/storage
    ```

## 运行站点

权限设置完成后我们来发布站点吧！运行下面的命令：

```bash
nginx
```

然后现在用你电脑的浏览器打开你服务器的 IP，你会看到下面的界面：

<img :src="$withBase('/assets/img/guide/installation/view-plus-site-default.png')" />

现在，你可以点击右上角的登录按钮，然后你的账号密码都是 `root` 点击登录按钮试试看吧！

## 细节问题处理

1. 我们打开 Plus 程序的 `storage/configure/plus.yml` 文件，将里面的 `app.url` 这一项，修改为你服务器的 IP，记住一定要携带 **http://** 协议哟！
2. 推荐点击首页的 `ADMINISTRATION` 按钮或者网址后面输入 `/admin` 进入后台，修改用户密码，然后进入「用户中心」- 「角色管理」然后设置「创始人、普通用户」点击「管理按钮」页面拉到底部勾选「签到管理、发布分享」两个权限，否则你前台 API 是会提示没有权限发布动态的哟。
3. 点击左边侧栏的「存储管理」将里面的内容进行设置，否则无法更新用户头像之类的哟！
4. 点击 CDN 管理，按照页面的指示进行配置，默认是本地公开，你在 Plus 程序目录运行 `php artisan storage:link` 命令！否则上传的动态图片之类的会 404 的。
5. 如果你想前台使用 root 帐号登录，请修改 root 帐号密码，否则是无法登陆的，并且去掉 root 帐号的「禁用用户组」
