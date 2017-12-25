# 收藏

- [收藏列表](#获取用户收藏的专辑)
- [添加收藏](#收藏专辑)
- [取消收藏](#取消收藏)

## 获取用户收藏的专辑

```
GET /music/collections
```

#### Response

```
Status: 200 OK
```
```json5
[
    {
        "id": 2, // 专辑id
        "created_at": "2017-03-15 17:04:31",
        "updated_at": "2017-06-27 18:40:56",
        "title": "少女情怀总是诗", // 专辑名称
        "intro": "耶嘿 杀乌鸡", // 专辑简介
        "storage": { // 专辑封面
            "id": 108, // 封面图片id
            "size": "3024x3024" // 图片尺寸
        },
        "taste_count": 845, // 播放数
        "share_count": 21, // 分享数
        "comment_count": 97, // 评论数
        "collect_count": 9, // 收藏数
        "paid_node": { // 专辑为免费时  该字段为null
            "paid": true, // 是否已付费
            "node": 1, // 付费节点
            "amount": 200 // 付费金额
        }
    }
]
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 20 ，获取条数 |
| max_id | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

## 收藏专辑

```
POST /music/specials/{special}/collection
```

### Response

```
Status: 201 Created
```

```json5
{
    "message": [
        "收藏成功"
    ]
}
```

## 取消收藏

```
DELETE /music/specials/{special}/collection
```

### Response

```
Status: 204 No Content
```