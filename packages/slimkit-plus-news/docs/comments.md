# 评论

- [评论一条资讯](#评论一条资讯)
- [获取一条资讯的评论列表](#获取一条咨询的评论列表)
- [删除一条资讯评论](#删除一条资讯评论)

## 评论一条资讯

```
POST /news/{news}/comments
```

## 参数
| 名称 | 描述 |
|:----:|------|
| body | 评论内容 |
| reply_user | 被回复用户id 默认为0 |

### Response

Headers

```
Status: 201 Created
```
```json5
{
    "message": [
        "操作成功"
    ],
    "comment": {
        "user_id": 1,
        "reply_user": 0,
        "target_user": 1,
        "body": "baishi",
        "commentable_type": "news",
        "commentable_id": 2,
        "updated_at": "2017-08-10 09:31:58",
        "created_at": "2017-08-10 09:31:58",
        "id": 5
    }
}
```
| 名称 | 描述 |
|:----:|------|
| message | 消息 |
| comment | 评论信息 |
| comment.id   | 评论id |
| comment.user_id | 评论者id |
| comment.target_user | 资讯发布者id |
| comment.reply_user | 被回复者id |
| comment.body | 评论内容 |



## 获取一条资讯的评论列表

```
GET /news/{news}/comments
```

## 参数
| 名称  | 类型 | 必须 | 说明 |
|:-----:|:-----|:----:|------|
| limit | int  | -    | 数据返回条数 |
| after | int  | -    | 数据翻页标识 |

### Response

Headers

```
Status: 200 OK
```
```json5
{
    "pinneds": [],
    "comments": [
        {
            "id": 2389,
            "user_id": 215,
            "target_user": 0,
            "reply_user": 0,
            "created_at": "2017-07-12 01:13:45",
            "updated_at": "2017-07-12 01:13:45",
            "commentable_type": "news",
            "commentable_id": 31,
            "body": "而且其实我想把这篇资讯分享到我的动态，然而……"
        },
        {
            "id": 2388,
            "user_id": 215,
            "target_user": 0,
            "reply_user": 0,
            "created_at": "2017-07-12 01:13:01",
            "updated_at": "2017-07-12 01:13:01",
            "commentable_type": "news",
            "commentable_id": 31,
            "body": "我下拉到底部后，评论编辑窗口不见了……，"
        }
    ]
}
```

### 返回参数

| 名称 | 描述 |
|:----:|------|
| pinneds | 置顶评论 |
| comments | 评论列表 |
| id   | 评论id |
| user_id | 评论者id |
| target_user | 资讯发布者id |
| reply_user | 被回复者id |
| body | 评论内容 |

## 删除一条资讯评论

```
DELETE /news/{news}/comments/{comment}
```

### Response

Headers

```
Status: 204 No Content
```