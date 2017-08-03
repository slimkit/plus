# 用户关注

- [列出一个用户的关注者](#列出一个用户的关注者)
- [列出用户正在关注的人](#列出用户正在关注的人)
- [关注一个用户](#关注一个用户)
- [取消关注一个用户](#取消关注一个用户)

## 列出一个用户的关注者

列出一个用户的关注者:

```
GET /users/:user/followers
```

列出授权用户的关注者:

```
GET /user/followers
```

### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认 20 |
| after | Integer | 获取之后数据，默认 0 |

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "name": "Seven",
        "bio": "Seven 的个人传记",
        "sex": 2,
        "location": "成都 中国",
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-25 03:59:39",
        "following": false,
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
            "updated_at": "2017-08-01 06:06:37",
            "feeds_count": 0,
            "questions_count": 5,
            "answers_count": 3
        }
    }
]
```

## 列出用户正在关注的人

列出一个用户正在关注的人：

```
GET /users/:user/followings
```

列出用户授权用户正在关注的人

```
GET /user/followings
```

### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认 20 |
| after | Integer | 获取之后数据，默认 0 |

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "name": "Seven",
        "bio": "Seven 的个人传记",
        "sex": 2,
        "location": "成都 中国",
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-25 03:59:39",
        "following": false,
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
            "updated_at": "2017-08-01 06:06:37",
            "feeds_count": 0,
            "questions_count": 5,
            "answers_count": 3
        }
    }
]
```

## 关注一个用户

```
PUT /user/followings/:user
```

#### 响应

```
Status: 204 No Content
```

## 取消关注一个用户

```
DELETE /user/followings/:user
```

#### 响应

```
Status: 204 No Content
```
