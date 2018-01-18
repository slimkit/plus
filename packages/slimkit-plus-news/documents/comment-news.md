# 对一条资讯或一条资讯评论进行评论

## 接口地址

/api/v1/news/{news_id}/comment

## 请求方法

POST

## 请求字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| comment_content | string   | yes    | 评论内容 |
| reply_to_user_id     | int     | no    | 被评论者id 对评论进行评论时传入|

### HTTP Status Code

201

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "评论成功",
  "data": 1,
}
```