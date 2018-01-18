# 排行榜

- [获取资讯排行](#获取资讯排行)

## 获取资讯排行

根据一定时间内的资讯获得的浏览量量进行的排序

```
GET /news/ranks
```

## 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 数据返回条数 默认10条 |
| type | string | -  | 筛选类型 `day` - 日排行 `week` - 周排行  `month` - 月排行 |
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
            "count": 53,
            "rank": 1
        }
    }
]
```

### 返回参数

| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| extra.count | int | 点击量 |
| extra.rank | int | 排行 |

> 其他数据结构参考「用户」接口用户资料