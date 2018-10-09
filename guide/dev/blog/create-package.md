---
title: 创建应用
---

从这里开始，我们假设你已经在你的本地安装好了 Plus 程序的 Master 分支版本程序。

## 创建拓展包

在 Plus 目录执行下面的命令执行：

```
php artisan package:create
```

<img :src="$withBase('/assets/img/guide/dev/blog/create-blog-package.gif')" />

我们将包名称定义为 `slimkit/plus-blog` 命令空间为 `SlimKit\Plus\Packages\Blog\` 创建完成后，页面会提示你所处位置，存储位置位于 Plus 程序的 `packages/` 目录下，名字叫做 `slimkit-plus-blo`。

我们进入 `packages/slimkit-plus-blog` 目录，你会看到已经为你生成好了下面的结构：

<img :src="$withBase('/assets/img/guide/dev/blog/base-created-package-dir-tree.png')" /> 

## 安装本地拓展包

我们创建完成拓展包后，我们需要安装，现在我们打开 Plus 程序的 `composer.json` 文件，然后找到 `repositories` 字段，我们在这个字段的数组中添加如下信息：

```json
{
    "type": "path",
    "url": "packages/slimkit-plus-blog",
    "options": {
        "symlink": true,
        "plus-soft": true
    }
}
```

然后执行 `php artisan app:version` 然后不用输入新的版本号，直接回车即可。执行完成后我们在 Plus 目录下执行：

```bash
composer require slimkit/plus-blog -vvv
```

等到命令完成。然后执行 `php artisan package:handle` 你会看到下面红框部分的信息：

<img :src="$withBase('/assets/img/guide/dev/blog/package-handlers-output.png')" /> 

> 开发过程，真正需要的是红色框下面的那一些命令。

## 设计数据表

收看，我们考虑到 Plus 是一个多用户的程序，我们可以允许每个用户都创建自己的 Blog，所以我们设计一张如下记录表：

`blogs`

| 字段 | 类型 | 属性 | 描述 |
|----|----|----|----|
| `id` | `int` 10 | 自增字段，`unsigned` | 博客自增字段 |
| `slug` | `VARCHAR` 50 | | 博客自定义地址 |
| `name` | `VARCHAR` 100 |  | Blog 名称 |
| `desc` | `VARCHAR` 255 | nullable, 默认 `null` | Blog 描述 |
| `logo` | `VARCHAR` 255 | nullable, 默认 `null` | Blog 头像 |
| `owner_id` | `int` 10 | `unsigned` | Blog 创建者 |
| `posts_count` | `int` 10 | `unsigned`, nullable,  默认 `0` | Blog 统计数 |
| `latest_post_sent_at` | `timestamp` | nullable, 默认 `null` | 最新发布 Blog 时间 |
| `reviewed_at` | `timestamp` | nullable, 默认 `null` | 后台审核时间，存在时间表示通过 |

`blogs` 表索引：

| 字段 | 索引 |
|----|----|
| `id` | `primary` |
| `slug` | `unique` |
| `owner_id` | `unique` |
| `posts_count` | `index` |
| `latest_post_sent_at` | `index` |
| `reviewed_at` | `index` |

计入我们有设计 Blog 表，那么我们也还要设计文章表：

`blog_articles`

| 字段 | 类型 | 属性 | 描述 |
|----|----|----|----|
| `id` | `int` 10 | 自增字段，`unsigned` | 博客自增字段 |
| `title` | `VARCHAR` 150 | | 文章标题 |
| `contents` | `TEXT` | | 文章内容 |
| `blog_id` | `int` 10 | `unsigned` | 所属 Blog |
| `creator_id` |`int` 10 | `unsigned` | 创建者用户 ID |
| `comments_count` | `int` 10 | `unsigned` | 评论统计数 |
| `reviewed_at` | `timestamp` | nullable, 默认 `null` | 审核时间，投稿文章博主审核，存在时间则表示通过 |

| 字段 | 索引 |
|----|----|
| `id` | `primary` |
| `blog_id` | `index` |
| `creator_id` | `index` |
| `reviewed_at` | `index` |

## 创建数据表迁移

我们设计完数据表后，我们应当为拓展包生成数据表迁移，这样就可以将数据表写入到数据库了。我们现在执行：

```
php artisan package:handle plus-blog-dev make-model
```

然后输入 `Blog`，接着输入 `blogs`，第三个确认输入 `yes` 等到完成，完成后我们继续下一个迁移创建。

我们继续执行一次上看的命令，然后输入 `article`，然后输入 `blog_articles`，第三个也是输入 `yes` 等待完成。

现在，我们打开应用下的 `database/migrations/` 目录，你会看到有一个 `create_blogs_table` 结尾的 PHP 文件，我已经将上面的表设计转化为迁移 PHP 代码，你只需要写入即可：

<<< @/guide/dev/blog/codes/database/migrations/2018_09_30_040837_create_blogs_table.php

接下来，我们接着编写 `blog_articles` 迁移文件，和上面一样，我们找到 `create_blog_articles_table` 结尾的 PHP 文件，写入下面的内容：

<<< @/guide/dev/blog/codes/database/migrations/2018_09_30_042237_create_blog_articles_table.php

::: tip
需要用到的 Laravel 知识👉[数据库迁移](https://laravel-china.org/docs/laravel/5.7/migrations/2291)
:::

## 默认设置填充

我们设计了数据表，因为假设的逻辑是：“后台可以开启创建博客是否需要审核”，所以我们创建一个默认设置填充文件：

```bash
php artisan package:handle plus-blog-dev make-seeder
```

然后我们输入 `Settings` 回车即可，会在 `database/seeds` 下面创建一个名为 `SettingsSeeder.php` 的文件。我们打开这个文件输入如下内容：

<<< @/guide/dev/blog/codes/database/seeds/SettingsSeeder.php

然后我们打开拓展包的 `database/seeds/DatabaseSeeder.php` 文件，在 `run` 方法中输入下面的高亮内容：

<<< @/guide/dev/blog/codes/database/seeds/DatabaseSeeder.php{16}

::: tip
需要用到的 Laravel 知识👉[数据填充](https://laravel-china.org/docs/laravel/5.7/seeding/2292)

其实这里用不到，这是为了你在开发的时候你向填充一些数据做一个演示！你需要填充数据请执行：

```bash
php artisan package:handle plus-blog db-seed
```

千万不要重复执行，因为如果是插入操作，重复执行数据库已存在的记录则会报错！
:::

## 迁移数据表

我们创建完迁移文件，使用下面的命令进行数据表的创建操作：

```bash
php artisan migrate -vvv
```

等到执行完成后，我们可以去数据库查看，已经创建好这两张表了！
