# 专辑评论列表

## 接口地址

/api/v1/music/special/{special_id}/comment

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
  "message": "操作成功",
  "data": [
    {
      "id": 4,
      "created_at": "2017-03-15 07:56:22",
      "updated_at": "2017-03-15 07:56:22",
      "comment_content": "213123",
      "user_id": 1,
      "reply_to_user_id": 0,
      "music_id": 1,
      "special_id": 0
    },
    {
      "id": 3,
      "created_at": "2017-03-15 07:43:43",
      "updated_at": "2017-03-15 07:43:43",
      "comment_content": "213123",
      "user_id": 1,
      "reply_to_user_id": 0,
      "music_id": 0,
      "special_id": 1
    },
    {
      "id": 2,
      "created_at": "2017-03-15 07:43:22",
      "updated_at": "2017-03-15 07:43:22",
      "comment_content": "213123",
      "user_id": 1,
      "reply_to_user_id": 0,
      "music_id": 1,
      "special_id": 0
    },
    {
      "id": 1,
      "created_at": "2017-03-15 07:43:07",
      "updated_at": "2017-03-15 07:43:07",
      "comment_content": "213123",
      "user_id": 1,
      "reply_to_user_id": 0,
      "music_id": 1,
      "special_id": 0
    }
  ]
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | string   | yes      | 评论id |
| created_at | string | yes      | 评论时间 |
| comment_content | string | yes | 评论内容 |
| user_id  | int      | yes      | 评论者id |
| reply_to_user_id | int | yes   | 被回复者id |