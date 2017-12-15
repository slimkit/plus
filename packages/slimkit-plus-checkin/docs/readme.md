# 用户签到

- [签到开关状态](#签到开关状态)
- [获取签到信息](#获取签到信息)
- [签到](#签到)
- [累计签到排行榜](#累计签到排行榜)

## 签到开关状态

签到应用具有开关性质，管理员可从后台控制签到是否被开启或者关闭，而开关会在「启动信息」接口中提供。提供格式如下：

```json5
{
    "checkin": true
    // Or
    "checkin": false
}
```

签到金额格式：

```json5
{
    "checkin:attach_balance": 0
}
```
> 金额为0时表示未配置

但是特殊情况除外，就是管理员开启了签到功能，但是并没有配置签到奖励，那么则会在「[签到](#签到)」接口中返回 HTTP Status Code 为 `403` 并提示，但是 `403` 状态还有一种情况在「[签到](#签到)」接口中返回，用户已签到也会返回 `403` 状态提示没有权限操作。

## 获取签到信息

```
GET /user/checkin
```

#### 响应

```
Status: 200 OK
```
```json
{
    "rank_users": [
        {
            "id": 1,
            "name": "Seven",
            "bio": "Seven 的个人传记",
            "sex": 2,
            "location": "成都 中国",
            "created_at": "2017-06-02 08:43:54",
            "updated_at": "2017-07-25 03:59:39",
            "avatar": "http://plus.io/api/v2/users/1/avatar",
            "bg": "http://plus.io/storage/user-bg/000/000/000/01.png",
            "verified": null,
            "extra": {
                "user_id": 1,
                "likes_count": 0,
                "comments_count": 8,
                "followers_count": 0,
                "followings_count": 1,
                "updated_at": "2017-08-11 01:32:36",
                "feeds_count": 0,
                "questions_count": 5,
                "answers_count": 3,
                "checkin_count": 2,
                "last_checkin_count": 2
            }
        }
    ],
    "checked_in": true,
    "checkin_count": 2,
    "last_checkin_count": 2,
    "attach_balance": 0
}
```

| 字段 | 描述 |
|:----:|----|
| rank_users | 当日前五签到用户，按照签到时间顺序排列。（参考「用户资料」接口文档） |
| checked_in | 当前用户是否已签到。 |
| checkin_count | 当前用户签到总天数。 |
| last_checkin_count | 当前用户连续签到天数。 |
| attach_balance | 签到用户积分增加值，单位是真实货币「分」单位。 |


## 签到

```
PUT /user/checkin
```

#### 响应

```
Status: 204 No Content
```

## 累计签到排行榜

```
GET /checkin-ranks
```

#### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| offset | Integer | 数据偏移数，默认为 `0`。 |
| limit | Integer | 查询数据条数 |

#### 响应

```
Status: 200 OK
```
```
[
    {
        "id": 1,
        "name": "Seven",
        "bio": "Seven 的个人传记",
        "sex": 2,
        "location": "成都 中国",
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-25 03:59:39",
        "follwing": false,
        "follower": false,
        "avatar": "http://plus.io/api/v2/users/1/avatar",
        "bg": "http://plus.io/storage/user-bg/000/000/000/01.png",
        "verified": null,
        "extra": {
            "user_id": 1,
            "likes_count": 0,
            "comments_count": 8,
            "followers_count": 0,
            "followings_count": 1,
            "updated_at": "2017-08-11 01:32:36",
            "feeds_count": 0,
            "questions_count": 5,
            "answers_count": 3,
            "checkin_count": 2,
            "last_checkin_count": 2
        }
    },
    {
        "id": 2,
        "name": "test1",
        "bio": null,
        "sex": 0,
        "location": "0",
        "created_at": "2017-06-12 07:38:55",
        "updated_at": "2017-06-12 07:38:55",
        "follwing": true,
        "follower": false,
        "avatar": null,
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 2,
            "likes_count": 0,
            "comments_count": 0,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": null,
            "feeds_count": 0,
            "questions_count": 0,
            "answers_count": 0,
            "checkin_count": 0,
            "last_checkin_count": 0
        }
    }
]
```

> 数据结构参考「用户」接口用户资料。
