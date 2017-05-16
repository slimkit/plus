# 获取用户收到的评论

## 接口地址

```
/api/v1/users/mycomments
```

## 请求方式

```
GET
```
## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:-----------:|
| max_id   | int      | no       | 用来翻页数据体记录id |
| limit    | int      | no       | 每次翻页返回数据条数 |

### HTTP Status Code

200

## 返回体

```
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": [
    {
      "id": 1,
      "component": "feed",
      "comment_table": "feed_comments",
      "comment_id": 48,
      "comment_content": "sadfsdf",
      "source_table": "feeds",
      "source_id": 17,
      "source_cover": 0,
      "source_content": "13213",
      "user_id": 1,
      "to_user_id": 1,
      "reply_to_user_id": 0,
      "created_at": "2017-04-12 07:13:49",
      "updated_at": "2017-04-12 07:13:49"
    }
  ]
}
```

## 返回变量

| name              | must     | description |
|-------------------|:--------:|:-----------:|
| id                | yes      | 数据体id |
| component         | yes      | 数据所属扩展包名 目前可能的参数有 feed music news |
| comment_table     | yes      | 评论所属数据表 目前可能的参数有 feed_comments music_comments news_comments|
| comment_id        | yes      | 关联评论id  |
| comment_content   | yes      | 关联评论内容 |
| source_table      | yes      | 所属资源所在表 目前可能参数有 feeds musics music_specials news (建议以该字段解析资源参数 例如音乐模块 存在对音乐评论和对音乐专辑评论两种) |
| source_id         | yes      | 关联资源id  |
| source_cover      | yes      | 关联资源封面 默认为0 |
| source_content    | yes      | 关联资源内容|
| user_id           | yes      | 评论者id    |
| to_user_id        | yes      | 资源作者id  |
| reply_to_user_id  | yes      | 被回复着id  |


code请参见[消息对照表](消息对照表.md)