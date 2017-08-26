# 找人

- [热门用户](#热门用户)
- [最新用户](#最新用户)
- [推荐用户(后台推荐)](#后台推荐用户)
- [推荐用户(按标签)](#推荐用户)
- [搜索用户](#搜索用户)

## 后台推荐用户

```
get /user/recommends
```
#### Response
```
Status: 200 OK
```
```json5
[
    {
        "id": 29,
        "name": "袖子",
        "location": "四川省 成都市 武侯区",
        "sex": 2,
        "bio": "咨询QQ 3298713109 电话：18108035545。购买授权、产品咨询、定制开发、TS二次开发",
        "created_at": "2017-04-17 08:47:49",
        "updated_at": "2017-08-01 08:20:26",
        "following": false,
        "follower": false,
        "avatar": "http://tsplus.zhibocloud.cn/api/v2/users/29/avatar",
        "bg": null,
        "verified": {
            "type": "user",
            "icon": null
        },
        "extra": {
            "user_id": 29,
            "likes_count": 65,
            "comments_count": 77,
            "followers_count": 43,
            "followings_count": 129,
            "updated_at": "2017-08-26 08:17:40",
            "feeds_count": 56,
            "questions_count": 0,
            "answers_count": 0,
            "checkin_count": 0,
            "last_checkin_count": 0
        }
    }
]
```


## 热门用户

```
get /user/populars
```

## 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | integer | 每页数量 |
| offset | integer | 偏移量, 注: 此参数为之前获取数量的总和 |

#### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 2,
        "name": "wayne",
        "bio": null,
        "sex": 0,
        "location": null,
        "created_at": "2017-08-06 04:46:13",
        "updated_at": "2017-08-07 04:06:56",
        "following": false,
        "follower": false,
        "avatar": "http://192.168.2.104/api/v2/users/2/avatar",
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 2,
            "likes_count": 0,
            "comments_count": 23,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-07 09:55:50",
            "feeds_count": 0,
            "questions_count": 1,
            "answers_count": 0
        }
    }
]
```

## 最新用户

```
get /user/latests
```

## 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | integer | 每页数量 |
| offset | integer | 偏移量, 注: 此参数为之前获取数量的总和 |

#### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 2,
        "name": "wayne",
        "bio": null,
        "sex": 0,
        "location": null,
        "created_at": "2017-08-06 04:46:13",
        "updated_at": "2017-08-07 04:06:56",
        "following": false,
        "follower": false,
        "avatar": "http://192.168.2.104/api/v2/users/2/avatar",
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 2,
            "likes_count": 0,
            "comments_count": 23,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-07 09:55:50",
            "feeds_count": 0,
            "questions_count": 1,
            "answers_count": 0
        }
    }
]
```

## 推荐用户

```
get /user/find-by-tags
```

## 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | integer | 每页数量 |
| offset | integer | 偏移量, 注: 此参数为之前获取数量的总和 |

#### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 2,
        "name": "wayne",
        "bio": null,
        "sex": 0,
        "location": null,
        "created_at": "2017-08-06 04:46:13",
        "updated_at": "2017-08-07 04:06:56",
        "following": false,
        "follower": false,
        "avatar": "http://192.168.2.104/api/v2/users/2/avatar",
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 2,
            "likes_count": 0,
            "comments_count": 23,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-07 09:55:50",
            "feeds_count": 0,
            "questions_count": 1,
            "answers_count": 0
        }
    }
]
```

## 搜索用户

```
get /user/search
```

## 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | integer | 每页数量 |
| offset | integer | 偏移量, 注: 此参数为之前获取数量的总和 |
| keyword | string | 关键字, 查找分页时也为必填项 |

#### Request

http://test-plus.zhibocloud.cn/api/v2/user/search?keyword=用户名

#### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 2,
        "name": "wayne",
        "bio": null,
        "sex": 0,
        "location": null,
        "created_at": "2017-08-06 04:46:13",
        "updated_at": "2017-08-07 04:06:56",
        "following": false,
        "follower": false,
        "avatar": "http://192.168.2.104/api/v2/users/2/avatar",
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 2,
            "likes_count": 0,
            "comments_count": 23,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-07 09:55:50",
            "feeds_count": 0,
            "questions_count": 1,
            "answers_count": 0
        }
    }
]
```

## 搜索用户

```
post /user/find-by-phone
```

## 输入

- 单次请求手机数量不超过100条

#### Request

{
	"phones": [
		18877778888,
		18999998888,
		17700001111
	]
}

#### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 2,
        "name": "wayne",
        "bio": null,
        "sex": 0,
        "location": null,
        "created_at": "2017-08-06 04:46:13",
        "updated_at": "2017-08-07 04:06:56",
        "mobi": "18908019700",
        "following": false,
        "follower": false,
        "avatar": "http://192.168.2.104/api/v2/users/2/avatar",
        "bg": null,
        "verified": null,
        "extra": {
            "user_id": 2,
            "likes_count": 0,
            "comments_count": 23,
            "followers_count": 0,
            "followings_count": 0,
            "updated_at": "2017-08-07 09:55:50",
            "feeds_count": 0,
            "questions_count": 1,
            "answers_count": 0
        }
    }
]
```

- 返回体中 手机号码字段为 [mobi]