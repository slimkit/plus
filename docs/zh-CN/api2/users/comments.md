# 用户收到的评论

```
GET /user/comments
```

#### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | 整数 |获取的条数，默认 `20`。 |
| after | 整数 | 传递上次获取的最后一条 `id`。 |

##### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 3,
        "user_id": 1,
        "target_user": 1,
        "reply_user": 0,
        "body": "我是第三条评论",
        "commentable_id": 1,
        "commentable_type": "feeds",
        "created_at": "2017-07-20 08:53:24",
        "updated_at": "2017-07-20 08:53:24",
        "commentable": {
            "id": 1,
            "user_id": 1,
            "feed_content": "动态内容",
            "feed_from": 1,
            "like_count": 1,
            "feed_view_count": 0,
            "feed_comment_count": 6,
            "feed_latitude": null,
            "feed_longtitude": null,
            "feed_geohash": null,
            "audit_status": 1,
            "feed_mark": 1,
            "pinned": 0,
            "created_at": "2017-06-27 07:04:32",
            "updated_at": "2017-07-20 08:53:24",
            "deleted_at": null,
            "pinned_amount": 0,
            "images": [],
            "paid_node": null
        }
    }
]
```

| 字段 | 描述 |
|:----:|-----|
| id | 评论 ID。|
| user_id | 发布评论的人。|
| target_user | 接收评论的目标用户。|
| reply_user | 评论回复的人。|
| body | 评论内容 |
| commentable_id | 评论来源资源 ID，根据 `commentable_type` 判断是来自何处。|
| commentable_type | 评论所属资源类型。|
| created_at | 评论创建时间。|
| updated_at | 评论更新时间。|
| commentable | 评论来源资料，如果来源被删除，则为 `null`。|
