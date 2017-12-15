# 打赏

- [打赏动态](#打赏动态)
- [动态打赏列表](#动态打赏列表)

## 打赏动态

```
POST /feeds/{feed}/rewards
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

## 动态打赏列表

```
GET /feeds/{feed}/rewards
```

### Parameters

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| limit    | int      | no       | 列表返回数据条数 |
| offset   | int      | no       | 数据偏移量，翻页时传入 |
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
        "id": 6,
        "user_id": 1,
        "target_user": 1,
        "amount": 200,
        "rewardable_id": 1,
        "rewardable_type": "feeds",
        "created_at": "2017-08-01 08:55:49",
        "updated_at": "2017-08-01 08:55:49",
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
        "id": 5,
        "user_id": 1,
        "target_user": 1,
        "amount": 200,
        "rewardable_id": 1,
        "rewardable_type": "feeds",
        "created_at": "2017-08-01 08:54:29",
        "updated_at": "2017-08-01 08:54:29",
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
        "id": 4,
        "user_id": 1,
        "target_user": 1,
        "amount": 200,
        "rewardable_id": 1,
        "rewardable_type": "feeds",
        "created_at": "2017-08-01 08:46:22",
        "updated_at": "2017-08-01 08:46:22",
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
    }
]
```

| 名称 | 描述 |
|:----:|------|
| id   | 打赏记录id |
| user_id | 打赏用户id |
| target_user | 打赏目标用户id |
| amount | 打赏金额 |
| user | 打赏用户信息 |
| user.name | 打赏用户名称 |
| user.avatar | 打赏用户头像 |
