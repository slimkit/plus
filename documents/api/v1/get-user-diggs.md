# 获取用户收到的点赞

## 接口地址

```
/api/v1/users/mydiggs
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
      "id": 4,
      "component": "feed",
      "digg_table": "feed_diggs",
      "digg_id": 5,
      "source_table": "feeds",
      "source_id": 17,
      "source_cover": 0,
      "source_content": "13213",
      "user_id": 1,
      "to_user_id": 1,
      "created_at": "2017-04-11 02:41:42",
      "updated_at": "2017-04-11 02:41:42",
    }
  ]
}
```

## 返回变量

| name              | must     | description |
|-------------------|:--------:|:-----------:|
| id                | yes      | 数据体id |
| component         | yes      | 数据所属扩展包名 目前可能的参数有 feed |
| digg_table        | yes      | 点赞记录所属数据表 目前可能的参数有 feed_diggs |
| digg_id           | yes      | 关联点赞id  |
| source_table      | yes      | 所属资源所在表 目前可能参数有 feeds |
| source_id         | yes      | 关联资源id  |
| source_cover      | yes      | 关联资源封面 默认为0 |
| source_content    | yes      | 关联资源内容|
| user_id           | yes      | 点赞者id    |
| to_user_id        | yes      | 资源作者id  |

code请参见[消息对照表](消息对照表.md)