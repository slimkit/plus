# 排行

- [获取全站粉丝排行](#获取全站粉丝排行)
- [获取财富达人排行](#获取财富达人排行)
- [获取全站收入排行](#获取全站收入排行)

## 获取全站粉丝排行

根据全站的用户粉丝数进行的排序

```
GET /ranks/followers
```

## 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 数据返回条数 默认10条 |
| offset | int | -   | 偏移量 默认为0 |

## 响应

```
Http Status 200 Ok
```

```json5
[
    {
        "id": 1,
        "name": "baishi",
        "bio": null,
        "sex": 1,
        "location": null,
        "created_at": "2017-07-31 03:16:19",
        "updated_at": "2017-08-09 10:09:28",
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 1,
            "likes_count": 2,
            "comments_count": 9,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-17 07:05:06",
            "feeds_count": 0,
            "questions_count": 0,
            "answers_count": 19,
            "count": 0,
            "rank": 1
        }
    }
]
```

### 返回参数
| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| extra.count | int | 粉丝数 |
| extra.rank | int | 排行 |

> 其他数据结构参考「用户」接口用户资料

## 获取财富达人排行

根据全站的钱包余额进行的排序

```
GET /ranks/balance
```

## 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 数据返回条数 默认10条 |
| offset | int | -   | 偏移量 默认为0 |

## 响应

```
Http Status 200 Ok
```

```json5
[
    {
        "id": 1,
        "name": "baishi",
        "bio": null,
        "sex": 1,
        "location": null,
        "created_at": "2017-07-31 03:16:19",
        "updated_at": "2017-08-09 10:09:28",
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 1,
            "likes_count": 2,
            "comments_count": 9,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-17 07:05:06",
            "feeds_count": 0,
            "questions_count": 0,
            "answers_count": 19,
            "rank": 1
        }
    }
]
```

### 返回参数

| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| extra.rank | int | 排行 |

> 其他数据结构参考「用户」接口用户资料
## 获取全站收入排行

根据全站的收入记录总额进行的排序

```
GET /ranks/income
```

## 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 数据返回条数 默认10条 |
| offset | int | -   | 偏移量 默认为0 |

## 响应

```
Http Status 200 Ok
```

```json5
[
    {
        "id": 1,
        "name": "baishi",
        "bio": null,
        "sex": 1,
        "location": null,
        "created_at": "2017-07-31 03:16:19",
        "updated_at": "2017-08-09 10:09:28",
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 1,
            "likes_count": 2,
            "comments_count": 9,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-17 07:05:06",
            "feeds_count": 0,
            "questions_count": 0,
            "answers_count": 19,
            "rank": 1
        }
    }
]
```

### 返回参数

| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| extra.rank | int | 排行 |

> 其他数据结构参考「用户」接口用户资料