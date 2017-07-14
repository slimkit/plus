# 收到的评论

```
GET /user/comments
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认值 20 |
| after | Integer | 获取之后数据，默认 0 |

#### Response

```
Status: 200 OK
```

```json5
[
    // ...
    {
        "id": 5, // 评论 ID
        "user_id": 1, // 评论发送用户
        "target_user": 1, // 目标用户
        "reply_user": 0, // 被回复用户
        "channel": "feed", // 评论来自频道，目前预留参数 feed-动态 music-音乐 music_special-音乐专辑 news-资讯
        "target": "8", // 来自频道目标id 例如 channel = feed 则 target 就是 feed 频道评论 ID,
        "created_at": "2017-07-11 09:53:21", // 评论时间
        "updated_at": "2017-07-11 09:53:21", // 更新时间
        "comment_content": "测试一些评论发送", // 评论内容
        "target_image": 0, // 目标频道图片 ID
        "target_title": "动态内容", // 目标频道标题
        "target_id": 1 // 目标频道ID，例如 channel = feed 则 target_id 就是 feed_id
    }
]
```