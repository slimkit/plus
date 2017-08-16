# 排行

- [获取全站粉丝排行](#获取全站粉丝排行)
- [获取财富达人排行](#获取财富达人排行)
- [获取全站收入排行](#获取全站收入排行)

## 获取全站粉丝排行

根据全站的用户粉丝数进行的排序

```
GET /user/ranks/followers
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
{
    "user_count": 0,
    "ranks": [
        {
            "id": 3,
            "name": "root2",
            "count": 7,
            "rank": 1,
            "following": false,
            "follower": false,
            "avatar": null,
            "bg": null,
            "verified": null
        },
        {
            "id": 2,
            "name": "root1",
            "count": 1,
            "rank": 2,
            "following": false,
            "follower": false,
            "avatar": null,
            "bg": null,
            "verified": null
        }
    ]
}
```

### 返回参数
| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| user_count | int | 当前用户收到的点赞数 |
| ranks | array | 排行信息 |
| ranks.id | int | 用户id |
| ranks.name | string | 用户名称 |
| ranks.count | int | 用户粉丝数 |
| ranks.rank | int | 用户排名 |
| ranks.following | bool | 对方用户是否关注了当前用户 |
| ranks.follower | bool | 对方用户是否被当前用户关注 |
| ranks.avatar | string/null | 用户头像 |
| ranks.bg | string/null | 用户背景图片 |
| ranks.verified | array/null | 用户认证资料 |

## 获取财富达人排行

根据全站的钱包余额进行的排序

```
GET /user/ranks/balance
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
        "rank": 1,
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null
    },
    {
        "id": 3,
        "name": "root2",
        "rank": 2,
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null
    },
    {
        "id": 2,
        "name": "root1",
        "rank": 3,
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null
    }
]
```

### 返回参数
| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| id | int | 用户id |
| name | string | 用户名称 ||
| rank | int | 用户排名 |
| following | bool | 对方用户是否关注了当前用户 |
| follower | bool | 对方用户是否被当前用户关注 |
| avatar | string/null | 用户头像 |
| bg | string/null | 用户背景图片 |
| verified | array/null | 用户认证资料 |

## 获取全站收入排行

根据全站的收入记录总额进行的排序

```
GET /user/ranks/income
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
        "rank": 1,
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null
    },
    {
        "id": 3,
        "name": "root2",
        "rank": 2,
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null
    },
    {
        "id": 2,
        "name": "root1",
        "rank": 3,
        "following": false,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null
    }
]
```

### 返回参数
| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| id | int | 用户id |
| name | string | 用户名称 ||
| rank | int | 用户排名 |
| following | bool | 对方用户是否关注了当前用户 |
| follower | bool | 对方用户是否被当前用户关注 |
| avatar | string/null | 用户头像 |
| bg | string/null | 用户背景图片 |
| verified | array/null | 用户认证资料 |