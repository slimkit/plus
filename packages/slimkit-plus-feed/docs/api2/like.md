# 动态赞相关接口

- [点赞](#点赞)
- [取消赞](#取消赞)
- [赞的人列表](#赞的人列表)

## 点赞

```
POST /feeds/:feed/like
```

#### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "操作成功"
    ]
}
```

#### 通知类型

```json5
{
    "channel": "feed:digg", // 通知关键字
    "target": 325, // 动态id
    "content": "@2222 点赞了你的动态",
    "extra": {
        "user_id": 2 // 点赞者id
    }
}
```

#### 用户收到的点赞

```json5
  {
    "id": 7,
    "user_id": 2,
    "target_user": 2,
    "likeable_id": 327,
    "likeable_type": "feeds",
    "created_at": "2017-07-14 07:35:38",
    "updated_at": "2017-07-14 07:35:38",
    "likeable": {
        ... // 动态内容  参考单条动态内容
    }
  }
```
## 取消赞

```
DELETE /feeds/:feed/unlike
```

#### Response

```
Status: 204 Not Content
```

## 赞的人列表

```
GET /feeds/:feed/likes
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认 20 |
| after | Integer | `id` 获取之后数据，默认 0 |

#### Response

```
Status: 200 OK
```

```json
[
    {
        "id": 3,
        "user_id": 2,
        "target_user": 1,
        "likeable_id": 1,
        "likeable_type": "feeds",
        "created_at": "2017-07-12 08:09:07",
        "updated_at": null,
        "user": {
            "id": 2,
            "name": "test1",
            "bio": "0",
            "sex": 0,
            "location": "0",
            "created_at": "2017-06-12 07:38:55",
            "updated_at": "2017-06-12 07:38:55",
            "following": true,
            "follower": false,
            "avatar": null,
            "bg": null,
            "verified": null,
            "extra": null
        }
    }
]
```
| 字段 | 描述 |
|:-----|:-----|
| id | 赞 ID |
| user_id | 点赞用户 ID |
| target_user | 接收赞用户 ID |
| likeable_id | 喜欢的资源 ID，配置 `likeable_type` 表示不同资源 |
| likeable_type | 喜欢的资源类型。 |
| created_at | 点赞时间 |
| updated_at | 更新时间 |
| user | 点赞的用户资料，结构参考 「用户信息」接口说明。 |
