# 根据id获取评论列表

## 接口地址

/api/v1/news/comments

##请求方法

GET

##额外请求参数（get传入）

comment_ids 以逗号隔开或者数组形式的资讯评论id

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
      "id": 3,
      "created_at": "2017-03-13 16:35:33",
      "comment_content": "爱我的",
      "user_id": 1,
      "reply_to_user_id": 0
    },
    {
      "id": 2,
      "created_at": "2017-03-13 16:35:33",
      "comment_content": "爱我的",
      "user_id": 1,
      "reply_to_user_id": 0
    },
    {
      "id": 1,
      "created_at": "2017-03-13 16:35:33",
      "comment_content": "爱我的",
      "user_id": 1,
      "reply_to_user_id": 0
    }
  ]
}
```

## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | string   | yes      | 评论id |
| created_at | string	| yes		   | 评论时间 |
| comment_content | string | yes | 评论内容 |
| user_id  | int      | yes      | 评论者id |
| reply_to_user_id | int | yes   | 被回复者id |
