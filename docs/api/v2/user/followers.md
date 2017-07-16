# User Followers

- [List followers of a user](#list-followers-of-a-user)
- [List users followed by another user](#list-users-followed-by-another-user)
- [Follow a user](#follow-a-user)
- [Unfollow a user](#unfollow-a-user)

## List followers of a user

List a user's followers:

```
GET /users/:user/followers
```

List the authenticated user's followers:

```
GET /user/followers
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认 20 |
| after | Integer | 获取之后数据，默认 0 |

#### Response

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

## List users followed by another user

List who a user is following:

```
GET /users/:user/followings
```

List who the authenticated user is following:

```
GET /user/followings
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认 20 |
| after | Integer | 获取之后数据，默认 0 |

#### Response

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

## Follow a user

```
PUT /user/followings/:user
```

#### Response

```
Status: 204 No Content
```

## Unfollow a user

```
DELETE /user/followings/:user
```

#### Response

```
Status: 204 No Content
```
