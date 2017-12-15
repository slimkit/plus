# 打赏

- [打赏资讯](#打赏资讯)
- [资讯打赏列表](#资讯打赏列表)
- [资讯打赏统计](#资讯打赏统计)

## 打赏资讯

```
POST /news/{news}/rewards
```

### Parameters

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| amount   | int      | yes      | 打赏金额 |

### Response

Headers

```
Status: 201 Created
```

Body

```json5
{
    "message": [
        "打赏成功"
    ]
}
```

## 资讯打赏列表

```
GET /news/{news}/rewards
```

### Parameters

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| limit    | int      | no       | 列表返回数据条数 |
| since    | int      | no       | 翻页标识 时间排序时为数据id 金额排序时为打赏金额amount |
| order    | string   | no       | 排序 正序-asc 倒序desc |
| order_type | string | no       | 排序规则 date-按时间 amount-按金额 |

### Response

Headers

```
Status: 200 OK
```

Body

```json5
[
    {
        "id": 1,
        "user_id": 1,
        "target_user": 1,
        "amount": 200,
        "rewardable_id": 1,
        "rewardable_type": "news",
        "created_at": "2017-08-01 02:13:28",
        "updated_at": "2017-08-01 02:13:28",
        "user": {
            "id": 1,
            "name": "root",
            "bio": null,
            "sex": 0,
            "location": null,
            "created_at": "2017-07-31 03:16:19",
            "updated_at": "2017-07-31 03:16:19",
            "avatar": null,
            "bg": null,
            "verified": null,
            "extra": {
                "user_id": 1,
                "likes_count": 0,
                "comments_count": 1,
                "followers_count": 0,
                "followings_count": 0,
                "updated_at": "2017-07-31 06:06:58",
                "feeds_count": 0
            }
        }
    },
    {
        "id": 2,
        "user_id": 2,
        "target_user": 1,
        "amount": 210,
        "rewardable_id": 1,
        "rewardable_type": "news",
        "created_at": "2017-08-01 02:13:28",
        "updated_at": "2017-08-01 02:13:28",
        "user": {
            "id": 2,
            "name": "root1",
            "bio": null,
            "sex": 0,
            "location": null,
            "created_at": "2017-07-31 03:16:19",
            "updated_at": "2017-07-31 03:16:19",
            "avatar": null,
            "bg": null,
            "verified": null,
            "extra": null
        }
    },
    {
        "id": 3,
        "user_id": 3,
        "target_user": 1,
        "amount": 220,
        "rewardable_id": 1,
        "rewardable_type": "news",
        "created_at": "2017-08-01 02:13:28",
        "updated_at": "2017-08-01 02:13:28",
        "user": {
            "id": 3,
            "name": "root2",
            "bio": null,
            "sex": 0,
            "location": null,
            "created_at": "2017-07-31 03:16:19",
            "updated_at": "2017-07-31 03:16:19",
            "avatar": null,
            "bg": null,
            "verified": null,
            "extra": null
        }
    }
]
```

| 名称 | 描述 |
|:----:|------|
| id   | 打赏记录id |
| user_id | 打赏用户id |
| target_user | 被打赏用户id |
| amount | 打赏金额 |
| rewardable_id | 被打赏资源id |
| rewardable_type | 被打赏资源标识 |
| user | 打赏用户信息 |
| user.name | 打赏用户名称 |
| user.avatar | 打赏用户头像 |
| user.bio | 打赏用户简介 |
| user.sex | 打赏目标性别 |
| user.location | 打赏目标地区 |
| user.bg | 打赏目标背景图片 |
| user.verified | 打赏目标认证 |
| user.extra | 打赏目标扩展字段 |

## 资讯打赏统计

```
GET /news/{news}/rewards/sum
```

### Response

Headers

```
Status: 200 OK
```

```json5
{
    "count": 3,
    "amount": "630"
}
```

| 名称 | 描述 |
|:----:|------|
| count | 资讯打赏总记录 无记录时为0 |
| amount | 资讯打赏总额 无记录时为null |