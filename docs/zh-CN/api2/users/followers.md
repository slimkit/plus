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
        "name": "创始人",
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-06 07:04:06",
        "deleted_at": null,
        "following": false,
        "follower": false,
        "avatar": "http://plus.io/api/v2/users/1/avatar",
        "extra": {
            "user_id": 1,
            "likes_count": 0,
            "comments_count": 0,
            "followers_count": 0,
            "followings_count": 0,
            "created_at": "2017-07-16 08:45:51",
            "updated_at": "2017-07-16 08:46:54",
            "deleted_at": null
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
        "name": "创始人",
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-06 07:04:06",
        "deleted_at": null,
        "following": false,
        "follower": false,
        "avatar": "http://plus.io/api/v2/users/1/avatar",
        "extra": {
            "user_id": 1,
            "likes_count": 0,
            "comments_count": 0,
            "followers_count": 0,
            "followings_count": 0,
            "created_at": "2017-07-16 08:45:51",
            "updated_at": "2017-07-16 08:46:54",
            "deleted_at": null
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
