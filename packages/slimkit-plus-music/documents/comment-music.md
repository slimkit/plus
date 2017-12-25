# 评论歌曲

## 接口地址

/api/v1/music/{music_id}/comment

## 请求字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| comment_content | int | no     | 评论内容 |

## 请求方法

POST

### HTTP Status Code

201

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "评论成功",
  "data": 4
}
```