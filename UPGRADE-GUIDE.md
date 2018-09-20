## 1.9 升级 2.0 指南

升级指南引导你将你的程序升级至指定版本的变更，**首先，你应该先暂停你的 Web 服务，然后将你的 1.9 代码 更新至 2.0.0 版本。**

### 数据表表变动

数据表变动是一项缓慢的工作，升级耗时预计：**10 分钟**

- `at_messages` 增加表（无需操作）
- `users` 用户

    | 字段 | 可为空 | 默认值 | 其他 | 描述 | 操作 |
    |-----|----|----|----|----|-----|
    | `avatar` | `true` | `null` | | VARCHAR 类型，用户头像 | 添加 |
    | `bg` | `true` | `null` | | VARCHAR 类型，个人主页背景 | 添加 |
- `feed_topics` 动态

    | 字段 | 可为空 | 默认值 | 其他 | 描述 | 操作 |
    |-----|----|----|----|----|----|
    | `logo` | | | | | 删除 |
    | `logo` | `true` | `null` | | VARCHAR 类型，话题 logo | 添加 |
- `feed_topic_user_links` 动态

    | 字段 | 可为空 | 默认值 | 其他 | 描述 | 操作 |
    |-----|----|----|----|----|----|
    | `following_at` | `true` | `null` | | 修改为 `timestamp` 类型 | 关注话题时间 | 改变 |
- `feeds` 动态
    * 表字段
    
        | 字段 | 可为空 | 默认值 | 其他 | 描述 | 操作 |
        |-----|----|----|----|----|----|
        | `repostable_type` | `true` | `null` | | VARCHAR 类型，可转发的资源类型 | 添加 |
        | `repostable_id` | `true` | `0` | | integer 类型，unsigned ,可转发的资源 ID | 添加 |
        | `hot` | `true` | `0` | | int 类型，unsigned ,热门排序值 | 添加 |
    * 索引
    
        | 字段 | 索引类型 |
        |----|----|
        | `hot` | index |
        | `created_at` | index |
- `groups` **授权**，圈子

    | 字段 | 可为空 | 默认值 | 其他 | 描述 | 操作 |
    |-----|----|----|----|----|----|
    | `im_group_id` | `true` | `null` | | varchar 类型，环信群组 ID | 添加 |
    | `excellen_posts_count` | `true` | | `0` | integer 类型，unsigned，圈子精华贴统计 | 添加 |
    | `avatar` | `true` | `null` | VARCHAR 类型，圈子头像 | 添加 |
- `group_posts` **授权**，圈子
    * 表字段
    
        | 字段 | 可为空 | 默认值 | 其他 | 描述 | 操作 |
        |-----|----|----|----|----|----|
        | `excellent_at` | `true` | `null` | | timestamp 类型，设置精华时间，也表示是否是精华 | 添加 |
        | `comment_updated_at` | `true` | `null` | | timestamp 类型，评论最后更新时间 | 添加 |
    * 索引
    
        | 字段 | 索引类型 |
        | ----|----|
        | `group_id` | index |
        | `user_id` | index |
        | `excellent_at` | index |
        | `comment_updated_at` | index |
- `topics` **授权**，问答

    | 字段 | 可为空 | 默认值 | 其他 | 描述 | 操作 |
    |-----|----|----|----|----|----|
    | `avatar` | `true` | `null` | | varchar 类型，专题头像 | 添加 |

### 执行命令

修改完成数据表后请执行下面的命令填充新增数据表：

```bash
php artisan migrate
```
