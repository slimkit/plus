# 置顶

- [动态置顶](#动态置顶)
- [评论置顶](#评论置顶)
- [动态评论置顶审核列表](#动态评论置顶审核列表)
- [评论置顶审核通过](#评论置顶审核通过)
- [拒绝动态评论置顶申请](#拒绝动态评论置顶申请)
- [删除动态置顶评论](#删除动态置顶评论)

## 动态置顶

```
POST /feeds/:feed/pinneds
```

#### Input

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| amount | Integer | 必须，置顶总价格，单位分。 |
| day | Integer | 必须，置顶天数。|

#### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "申请成功"
    ]
}
```

## 评论置顶

```
POST /feeds/:feed/comments/:comment/pinneds
```

#### Input

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| amount | Integer | 必须，置顶总价格，单位分。 |
| day | Integer | 必须，置顶天数。|

#### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "申请成功"
    ]
}
```

#### 通知类型

```json5
{
    "channel": "feed:pinned-comment", // 通知关键字
    "target": 332, // 动态id
    "content": "@3 在你的动态中申请置顶评论《特殊时期上市日期》",
    "extra": {
        "user_id": 3 // 用户id
    }
}
```

## 动态评论置顶审核列表

```
GET /user/feed-comment-pinneds
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取的条数, 默认 20 |
| after | Integer | 上次请求列表倒叙最后一条 ID |

#### Response

```
Status: 200 OK
```
```json5
[
    {
        "id": 4,
        "channel": "comment",
        "target": 1,
        "user_id": 1,
        "amount": 1,
        "day": 3,
        "expires_at": null,
        "created_at": "2017-07-21 03:47:09",
        "updated_at": "2017-07-21 03:47:09",
        "target_user": 1,
        "raw": 1,
        "feed": {
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
        },
        "comment": {
            "id": 1,
            "user_id": 1,
            "target_user": 1,
            "reply_user": 0,
            "body": "我是第一条评论",
            "commentable_id": 1,
            "commentable_type": "feeds",
            "created_at": "2017-07-20 08:34:41",
            "updated_at": "2017-07-20 08:34:41"
        }
    }
]
```

> 状态以 `expires_at` 为准，`null` 状态为待审核，存在时间，标记为 **已处理**

#### 返回参数

| 名称 | 类型 | 描述 |
|:----:|:----:|------|
| id   | int  | 审核记录id |
| channel | string | 审核标识，该接口列表中 总为`comment` |
| target | int | 目标id，`channel` = `comment`时，该值为评论id |
| user_id | int | 申请用户id |
| amount | int | 申请金额 |
| day | int | 申请置顶天数 |
| expires_at | date | 置顶到期时间，未被处理时该值为`null`。审核通过时，该值为当前处理时间加上置顶天数的时间，不通过时，该值为当前处理时间 |
| target_user | int | 申请目标，该接口中为动态的发布者 |
| raw | int | 动态id |
| feed | array/null | 动态资源数据，参考动态列表 |
| comment | array/null | 评论资源数据，参考评论列表 |


## 评论置顶审核通过

```
PATCH /feeds/:feed/comments/:comment/pinneds/:pinned
```

#### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "置顶成功"
    ]
}
```

## 拒绝动态评论置顶申请

```
DELETE /user/feed-comment-pinneds/:pinned
```

#### Response

```
Status: 204 No Centent
```

## 删除动态置顶评论

```
DELETE /feeds/:feed/comments/:comment/unpinned
```

#### Response

```
Status: 204 No Centent
```
