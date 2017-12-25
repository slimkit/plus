# 专辑列表

## 接口地址

/api/v1/music/specials

## 请求方法

GET

## 请求字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| max_id   | int      | no       | 用来翻页的记录id(对应数据体里的id) |
| limit    | int      | no       | 每页返回数据条数 默认15条 |

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "专辑列表获取成功",
  "data": [
    {
      "id": 1,
      "created_at": "2017-03-10 18:05:02",
      "updated_at": "2017-03-10 18:05:03",
      "title": "专辑1",
      "intro": "这里是简介",
      "storage": 1,
      "taste_count": 0,
      "share_count": 0,
      "comment_count": 0,
      "collect_count": 0,
      "is_collection": 1
    }
  ]
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | int      | yes      | 专辑id  |
| title    | int      | yes      | 专辑标题 |
| intro    | string   | yes      | 专辑简介 |
| updated_at | int    | yes      | 资讯创建时间 |
| storage  | array      | yes    | 封面附件信息 |
| is_collection | int | yes      | 是否已收藏 1-已收藏 |