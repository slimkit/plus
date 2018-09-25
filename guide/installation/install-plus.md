---
title: 安装 Plus
---

本章将带你对 Plus 进行安装，**安装模式为基础安装**，安装系统：**CentOS** `7.4`。

## 下载 Plus 程序

现在我们进入服务器，然后输入 `cd ~` 进入家目录，我们在下载程序前，需要下载代码推荐的工具：`Git`：

```bash
yum install -y git
```

执行完成后我们将代码放到合适的位置，我们推荐放在 `/usr/local/src` 下面，所以输入 `cd /usr/local/src` 进入该目录，然后执行下面的命令：

```
git clone https://github.com/slimkit/plus plus && cd plus
```

好了，我们程序下载完成了，如果你原封不动复制的上面的命令，你随处目录为 `/usr/local/src/plus` 了。

我们切换到最新的稳定版本吧，目前最新的稳定版本是 `2.0` 所以执行下面的命令：

```bash
git checkout 2.0
```

## 创建数据库

在前面章节中，我们安装好了 MySQL 5.7 现在我们使用 root 帐号进入数据库：

```bash
mysql -u root -h localhost -P 3360 -p
```

接下来，我们创建数据库：

```sql
create database plus;
```

上面 SQL 中的 `plus` 可以替换为你希望的名字，为了避免安全问题，我们为 plus 数数据库分配一个独有的帐号密码，虽然此时我们可以使用 root 帐号，测试没有关系，但是推荐进行帐号分配，我们现在创建与**数据库同名**的帐号吧：

```sql
create user plus;
```

创建完成用户后，我们为用户设置密码，这里为了方便我将密码设置为 `12345678` 你设置的时候可别这么干哟：

```sql
set password for plus=password("12345678");
```

设置完成后，我们刷新一下数据库用户权限：

```sql
flush privileges;
```

接下来，我们把 `plus` 数据库的所有权赋予给 `plus` 用户：

```sql
grant all privileges on plus.* to plus@"localhost" identified by "12345678";
```

我们再次刷新一下数据库用户权限：

```sql
flush privileges;
```

然后我们输入 `exit;` 退出 root 帐号，测试帐号密码是否成功,推出后执行：

```bash
mysql -u plus -h localhost -P 3360 -p
```

然后输入密码进入，输入 `use plus;` 能进入数据库基本上算成功了！好了，现在退出数据库吧！

## 基础配置

我们输入 `cd /usr/local/src/plus` 进入程序目录，然后执行：

```bash
cp -rf ./storage/configure/.env.example ./storage/configure/.env && \
cp -rf ./storage/configure/plus.yml.example ./storage/configure/plus.yml
```

执行完成后会在 `storage/configure/` 目录下多出 `.env` 和 `plus.yml` 文件。

## 安装依赖包

我们在安装依赖包之前，需要先下载 `composer` 软件，这是 PHP 软件项目所使用的依赖管理工具，我们现在执行：

```bash
curl -L https://getcomposer.org/composer.phar > /usr/local/bin/composer && \
chmod +x /usr/local/bin/composer && \
composer self-update
```

上面的命令根据网速决定快慢，因为文件在国外服务器所以有可能下载失败或者不完整，如果出现了错误就多试几次。

接下来，我们开始下载程序依赖吧，这个命令过程也会非常缓慢，如果下载过程中杨浦失败，请多试几次：

```bash
composer update -vvv
```

## 生成应用秘钥

上面的步骤依旧安装完成了软件依赖，我们执行下面的命令来为应用生成独一无二的秘钥：

```bash
php artisan app:key-generate
```

## 配置数据库

我们打开 `storage/configure/plus.yml` 文件，找到 `database` 关键词，然后配置 `database` 下面的子项（如果你不了解这个文件的格式，请先通过搜索引擎搜索 `YAML 语法` 进行学习后开始配置）：

1. 我们将 database.default 字段配置为 `mysql`
2. 我们配置 database.connections.mysql 下的内容：

```yaml
host: 127.0.0.1  # 数据库链接地址，我们配置为 127.0.0.1 或者 localhost 即可
port: 3306       # 连接端口，配置为 3306
database: plus   # 使用的数据库名称，按照我们创建的配置为 plus
username: plus   # 连接数据库使用的帐号，按照我们创建的配置为 plus
password: 12345678 # 数据库连接帐号密码
charset: utf8mb4 # 使用的字符集，配置为 utf8mb4
collation: utf8mb4_unicode_ci # 按照这里的配置为 utf8mb4_unicode_ci 即可
```

## 迁移数据表

我们在 Plus 程序中已经为你制作好了数据库表迁移文件，你只需要执行下面的命令即可：

```bash
php artisan migrate -vvv
```

## 数据库填充

我们执行完成数据表迁移后，数据库已经存在数据表了，但是我们需要的一些默认数据并不存在，所以执行下面的命令进行数据填充：

```bash
php artisan db:seed -vvv
```

## 静态资产发布

走到这一步，基本上就算安装完成了，但是对于后端等还有一些静态资产需要发布：

```bash
php artisan vendor:publish --all -vvv
```

## 查看安装

好了，我们已经安装完成了，安装完成后，默认创始人帐号密码均为 `root`，为了方便我们查看，运行下面的命令进行查看站点：

```bash
php artisan serve --host=0.0.0.0 --port=80
```

> 如果你不想用 80 或者 80 端口已经被占用，请更换其他

执行后，命令行会进入 watch 状态，我们打开服务器的 IP 进行查看站点。

查看完成后按键盘上的 `ctrl` + `C` 退出。
