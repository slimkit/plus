# 获取动态

- [单条](#单条)
- [批量](#批量)

## 单条

```
GET /feeds/:feed
```

#### Response

```
Status: 201 OK
```
```json5
{
    "id": 13,
    "created_at": "2017-06-21 01:54:52",
    "updated_at": "2017-06-21 01:54:52",
    "deleted_at": null,
    "user_id": 1, // 发布动态的用户
    "feed_content": "动态内容", // 内容
    "feed_from": 2,
    "like_count": 0, // 点赞数
    "feed_view_count": 0, // 查看数
    "feed_comment_count": 0, // 评论数
    "feed_latitude": null, //  纬度
    "feed_longtitude": null, // 经度
    "feed_geohash": null, // GeoHash
    "audit_status": 1, // 审核状态
    "feed_mark": 12,
    "has_like": true, // 是否点赞
    "has_collect": false, // 用户是否收藏当前动态
    "paid_node": {
        "paid": true, // 当前用户是否已经付费
        "node": 9, // 付费节点
        "amount": 20 // 付费金额
    },
    "comment_paid_node": { // 评论收费信息.
        "paid": true,
        "node": 11,
        "amount": 50
    },
    "reward": {
        "count": 3, // 被打赏次数
        "amount": "600" // 被打赏总金额
    },
    "images": [ // 图片
        {
            "file": 4, // 文件 file_with 标识 不收费图片只存在 file 这一个字段。
            "size": null, // 图像尺寸，非图片为 null，图片没有尺寸也为 null，
            "amount": 100, // 收费多少
            "type": "download", // 收费方式
            "paid": false, // 当前用户是否购买
            "paid_node": 10 付费节点
        },
        {
            "file": 5,
            "size": '1930x1930' // 当图片有尺寸的时候采用 width x height 格式返回。
        }
    ],
    "likes": [
        {
            "id": 2,
            "user_id": 1,
            "target_user": 1,
            "likeable_id": 1,
            "likeable_type": "feeds",
            "created_at": "2017-07-12 08:09:07",
            "updated_at": "2017-07-12 08:09:07"
        }
    ]
}
```

##### Not paid

```json5
{
    "message": [
        "请购买动态"
    ],
    "paid_node": 9, // 付费节点
    "amount": 20 // 动态价格
}
```

## 批量

```
GET /feeds
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 20 ，获取条数 |
| after | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |
| type | String | 可选，默认值 new，可选值 `new` 、`hot` 、 `follow` 、`users` |
| search | String | type = `new`时可选，搜索关键字 |
| user | Integer | type = `users` 时可选，默认值为当前用户id |
| screen | string | type = `users` 时可选，`paid`-付费动态 `pinned` - 置顶动态 |

> 列表为倒序

#### Response

```
Status: 200 OK
```
```json5
{
    "ad": null,
    "pinned": [...], // 置顶动态列表
    "feeds": [
        {
            "id": 1,
            "user_id": 1,
            "feed_content": "12312312312",
            "feed_from": 1,
            "like_count": 0,
            "feed_view_count": 4,
            "feed_comment_count": 3,
            "feed_latitude": "",
            "feed_longtitude": "",
            "feed_geohash": "",
            "audit_status": 1,
            "feed_mark": 12312312,
            "pinned": 1,
            "pinned_amount": 0,
            "created_at": "2017-08-01 16:46:19",
            "updated_at": "2017-08-05 03:29:55",
            "deleted_at": null,
            "comments": [
                {
                    "id": 4,
                    "user_id": 1,
                    "target_user": 1,
                    "reply_user": 0,
                    "body": "辣鸡啊啊啊啊",
                    "commentable_id": 1,
                    "commentable_type": "feeds",
                    "created_at": "2017-08-05 03:29:55",
                    "updated_at": "2017-08-05 03:29:55",
                    "pinned": true
                },
                {
                    "id": 3,
                    "user_id": 1,
                    "target_user": 1,
                    "reply_user": 0,
                    "body": "辣鸡啊啊啊啊",
                    "commentable_id": 1,
                    "commentable_type": "feeds",
                    "created_at": "2017-08-05 03:29:53",
                    "updated_at": "2017-08-05 03:29:53",
                    "pinned": true
                },
                {
                    "id": 3,
                    "user_id": 1,
                    "target_user": 1,
                    "reply_user": 0,
                    "body": "辣鸡啊啊啊啊",
                    "commentable_id": 1,
                    "commentable_type": "feeds",
                    "created_at": "2017-08-05 03:29:53",
                    "updated_at": "2017-08-05 03:29:53"
                },
                {
                    "id": 2,
                    "user_id": 1,
                    "target_user": 1,
                    "reply_user": 0,
                    "body": "辣鸡啊啊啊啊",
                    "commentable_id": 1,
                    "commentable_type": "feeds",
                    "created_at": "2017-08-05 03:29:51",
                    "updated_at": "2017-08-05 03:29:51"
                }
            ],
            "has_collect": false,
            "has_like": false,
            "images": [],
            "paid_node": null
        }
    ]
}
```

### 返回参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| id   | int  | 动态数据id |
| user_id | int | 发布者id |
| feed_content | string | 动态内容 |
| feed_from | int | 动态来源 1:pc 2:h5 3:ios 4:android 5:其他 |
| like_count | int | 点赞数 |
| feed_view_count | int | 查看数 |
| feed_comment_count | int | 评论数 |
| feed_latitude | string | 纬度 |
| feed_longtitude | string | 经度 |
| feed_geohash | string | GEO |
| audit_status | int | 审核状态 |
| feed_mark | int | 标记 |
| pinned | int | 置顶标记 |
| pinned_amount | int | 置顶金额 |
| comments | array | 动态评论 列表中返回五条 |
| comments.id | int | 评论id |
| comments.user_id | int | 评论者id |
| comments.target_user | int | 资源作者id |
| comments.reply_user | int | 被回复者id |
| comments.body | string | 评论内容 |
| comments.pinned | bool | 评论置顶标记 不存在则为普通评论 |
| has_collect | bool | 是否已收藏 |
| has_like | bool | 是否已赞 |
| images | array | 图片信息 同单条动态数据结构一致 |
| paid_node | array | 付费节点信息 同单条动态数据结构一致 不存在时为null |

> `feed_content` 字段在列表中，如果是收费动态则只返回 100 个文字。
