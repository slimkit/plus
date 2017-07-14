# 用户收到的赞

```
GET /user/likes
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认 20 |
| after | Integer | 获取之后数据，默认 0 |

#### Response

```
Status: 20 OK
```

```json5
[
    {
        "id": 2, // 点赞标识
        "user_id": 1, // 点赞用户
        "target_user": 1, // 接收用户（你能收到就是因为这个ID就是你）
        "likeable_id": 1, // 赞来源ID
        "likeable_type": "feeds", // 赞来源 feeds 表示来自动态，所以 likeable_id 就是动态ID，目前预留参数 feeds-动态 news-资讯
        "created_at": "2017-07-12 08:09:07", // 赞时间
        "updated_at": "2017-07-12 08:09:07", // 更新时间
        "likeable": { // 如同 likeable_id 这里也是根据 likeable_type 返回不同来源数据，例如为 feeds 这里则为动态数据。
            "id": 1,
            "user_id": 1,
            "feed_content": "动态内容",
            "feed_from": 1,
            "like_count": 1,
            "feed_view_count": 0,
            "feed_comment_count": 3,
            "feed_latitude": null,
            "feed_longtitude": null,
            "feed_geohash": null,
            "audit_status": 1,
            "feed_mark": 1,
            "pinned": 0,
            "created_at": "2017-06-27 07:04:32",
            "updated_at": "2017-07-11 09:53:21",
            "deleted_at": null,
            "pinned_amount": 0,
            "images": []
        }
    }
]
```
