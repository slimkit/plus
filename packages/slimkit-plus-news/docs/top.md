# 置顶
- [申请资讯置顶](#申请资讯置顶)
- [查看申请置顶的资讯列表](#查看申请置顶的资讯列表)
- [申请资讯评论置顶](#申请资讯评论置顶)
- [查看申请置顶的评论列表](#查看资讯中申请置顶的评论列表)
- [审核评论置顶](#审核评论置顶)
- [拒绝评论置顶](#拒绝待审核评论置顶)
- [取消置顶](#取消评论置顶)

# 申请资讯置顶

## API
```
POST /news/{news}/pinneds
```

### prams
| 参数 | 说明 |
| :---: | :---: |
| news | 资讯id |

### 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| day  | int  | Y    | 申请置顶天数 |
| amout | int | Y    | 申请置顶金额 | 

### Response

Headers

```
Status: 201 OK
```
```json5
{
    "message": [
        "申请成功"
    ]
}
```

| 只有资讯创建者才有资格发起审核

# 查看申请置顶的资讯列表

| 查看当前用户所有申请置顶的资讯

### 参数说明
| 参数 | 说明 |
| :---: | :---: |
| limit | 选填，每页数量 默认15 |
| after | 首次获取不填， 分页查询必填，分页参数，当前页数据的最小id |

```
GET /news/pinneds
```

### Response

Headers

```
Status: 200 OK
```
```json5
[
    {
        "id": 13,
        "created_at": "2017-07-27 09:10:04",
        "updated_at": "2017-07-27 09:10:04", // 当state为1 或 2时，此项为审核者操作时间
        "channel": "news",
        "state": 1, // 审核是否通过 1: 通过, 0: 待审核, 2: 被拒绝
        "raw": 0,
        "target": 1,
        "user_id": 2,
        "target_user": 0,
        "amount": 50,
        "day": 5,
        "cate_id": null,
        "expires_at": "2017-07-25 00:00:00" // 当state为1是此项为置顶过期时间
    }
]
```

# 申请资讯评论置顶

## API
```
POST /news/{news}/comments/{comment}/pinneds
```

### prams
| 参数 | 说明 |
| :---: | :---: |
| news | 资讯id |
| comment| 评论id |

### 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| day  | int  | Y    | 申请置顶天数 |
| amout | int | Y    | 申请置顶金额 | 

### Response

Headers

```
Status: 201 OK
```
```json5
{
    "message": [
        "申请成功"
    ]
}
```

# 查看资讯中申请置顶的评论列表

| 查看当前用户发布的资讯中所有申请置顶的评论列表

### 参数说明
| 参数 | 说明 |
| :---: | :---: |
| limit | 选填，每页数量 默认15 |
| after | 首次获取不填， 分页查询必填，分页参数，当前页数据的最小id |

```
GET /news/comments/pinneds
```

### Response

Headers

```
Status: 200 OK
```
```json5
[
    {
        "id": 12,
        "created_at": "2017-07-27 08:43:33",
        "updated_at": "2017-07-27 08:44:20", // 当state为1 或 2时，此项为审核者操作时间
        "channel": "news:comment",
        "state": 1, // 审核是否通过 1: 通过, 0: 待审核, 2: 被拒绝
        "raw": 1,
        "target": 1,
        "user_id": 2,
        "target_user": 2,
        "amount": 50,
        "day": 5,
        "cate_id": null,
        "expires_at": "2017-08-01 08:44:20", // 当state为1是此项为置顶过期时间
        "news": {
            "id": 1,
            "created_at": "2017-07-25 00:00:00",
            "updated_at": "2017-07-27 08:58:02",
            "title": "资讯标题",
            "content": "阿斯顿发生地方爱上地方爱上地方阿斯顿",
            "digg_count": 0,
            "comment_count": 1,
            "hits": 1,
            "from": "1",
            "is_recommend": 1,
            "subject": "潇洒地方",
            "author": "哈哈哈",
            "audit_status": 0,
            "audit_count": 0,
            "user_id": 2,
            "category": {
                "id": 1,
                "name": "分类1",
                "rank": 0
            },
            "image": {
                "id": 1,
                "size": "1920x1080"
            },
            "pinned": {
                "id": 13,
                "created_at": "2017-07-27 09:10:04",
                "updated_at": "2017-07-27 09:10:04",
                "channel": "news",
                "state": 1,
                "raw": 0,
                "target": 1,
                "user_id": 2,
                "target_user": 0,
                "amount": 50,
                "day": 5,
                "cate_id": null,
                "expires_at": "2017-07-25 00:00:00"
            }
        },
        "comment": {
            "id": 1,
            "user_id": 2,
            "target_user": 2,
            "reply_user": 0,
            "body": "sldkfjalksdjflakjsdflkajsd",
            "commentable_id": 1,
            "commentable_type": "news",
            "created_at": "2017-07-25 00:00:00",
            "updated_at": "2017-07-25 00:00:00"
        }
    }
]
```

| 判断是否通过审核根据 expires_at 字段是否为空进行判断，如果该字段的值小于或者等于当前时间，要么是审核不通过，要么是置顶已经过期

# 审核评论置顶

### API
```
PATCH /news/{news}/comments/{comment}/pinneds/{pinned}
```

### prams
| 参数 | 说明 |
| :---: | :---: |
| news | 资讯id |
| comment| 评论id |
| pinned | 获取的申请评论置顶中的id [查看](top-comments-list.md) |

### Response

Headers

```
Status: 201 OK
```
```json5
{
    "message": [
        "申请成功"
    ]
}
```

# 拒绝待审核评论置顶

### API
```
PATCH /news/{news}/comments/{comment}/pinneds/{pinned}/reject
```

### prams
| 参数 | 说明 |
| :---: | :---: |
| pinned | 获取的申请评论置顶中的id [查看](top-comments-list.md) |

### Response

Headers

```
Status: 204 No Content
```

# 取消评论置顶

### API
```
DELETE /news/{news}/comments/{comment}/pinneds/{pinned}
```

### prams
| 参数 | 说明 |
| :---: | :---: |
| news | 资讯id |
| comment| 评论id |
| pinned | 获取的申请评论置顶中的id [查看](top-comments-list.md) |

### Response

Headers

```
Status: 204 No Content
```