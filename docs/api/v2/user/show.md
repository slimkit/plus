# Users

- [Get a single user](#get-a-single-user)
- [Get the authenticated user](#get-the-authenticated-user)
- [Update the authenticated user](#update-the-authenticated-user)
- [Get all users](#get-all-users)

## Get a single user

- [Get a user avatar](#get-a-user-avatar)

```
GET /users/:user
```

#### Parameters

| Name | Type | Description |
|:----:|:----:|----|
| following | Integer | Check whether the specified user is paying attention to the current user, the default is the current authentication user. |
| follower | Integer | Check whether the specified user is concerned about the acquisition of the user, the default is the current authentication user. |

##### Response

```
Status: 200 OK
```
```json
{
    "id": 1,
    "name": "创始人",
    "bio": "我是大管理员",
    "sex": 0,
    "location": "成都市 四川省 中国",
    "created_at": "2017-06-02 08:43:54",
    "updated_at": "2017-07-06 07:04:06",
    "following": false,
    "follower": false,
    "avatar": "http://plus.io/api/v2/users/1/avatar",
    "bg": null,
    "extra": {
        "user_id": 1,
        "likes_count": 0,
        "comments_count": 0,
        "followers_count": 0,
        "followings_count": 1,
        "updated_at": "2017-07-16 09:44:25",
        "feeds_count": 0
    }
}
```

### Get a user avatar

```
GET /users/:user/avatar
```

##### Response

```
Status: 302 > 200 | 304
Etag: "59698999-592a"
```

## Get the authenticated user

```
GET /user
```

##### Response

```
Status: 200 OK
```
```json
{
    "id": 1,
    "name": "创始人",
    "phone": "18781993582",
    "email": "shiweidu@outlook.com",
    "bio": "我是大管理员",
    "sex": 0,
    "location": "成都市 四川省 中国",
    "created_at": "2017-06-02 08:43:54",
    "updated_at": "2017-07-06 07:04:06",
    "avatar": "http://plus.io/api/v2/users/1/avatar",
    "bg": null,
    "extra": {
        "user_id": 1,
        "likes_count": 0,
        "comments_count": 0,
        "followers_count": 0,
        "followings_count": 1,
        "updated_at": "2017-07-16 09:44:25",
        "feeds_count": 0
    },
    "wallet": {
        "id": 1,
        "user_id": 1,
        "balance": 90,
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-05 08:29:49",
        "deleted_at": null
    }
}
```

## Update the authenticated user

- [Update avatar of the authenticated user](#update-avatar-of-the-authenticated-user)
- [Update user background image of the authenticated user](#update-user-background-image-of-the-authenticated-user)

### Update avatar of the authenticated user

```
POST /user/avatar
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| avatar | File | The user new avatar, *scale*: `1:1`, *size*: `100px` - `500px`. |

##### Response

```
Status: 204 No Content
```

### Update user background image of the authenticated user

## Get all users

```
GET /users
```

#### Parameters

| Name | Type | Description |
|:----:|:----:|----|
| limit | Integer | List user limit, minimum `1` max `50`. |
| order | Enum: `asc`, `desc` | Sorting. |
| since | Integer | The integer ID of the last User that you've seen. |
| name | String | Used to retrieve users whose username contains `name`. |
| id | Integer | Get multiple designated users, multiple `ID`s using `,` split. |

##### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "name": "创始人",
        "bio": "我是大管理员",
        "sex": 0,
        "location": "成都市 四川省 中国",
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-06 07:04:06",
        "following": false,
        "follower": false,
        "avatar": "http://plus.io/api/v2/users/1/avatar",
        "bg": null,
        "extra": {
            "user_id": 1,
            "likes_count": 0,
            "comments_count": 0,
            "followers_count": 0,
            "followings_count": 1,
            "updated_at": "2017-07-16 09:44:25",
            "feeds_count": 0
        }
    }
]
```