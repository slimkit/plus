# Users

- [Get a single user](#get-a-single-user)
- [Get the authenticated user](#get-the-authenticated-user)
- [Update the authenticated user](#update-the-authenticated-user)
- [Get all users](#get-all-users)
- [Retrueve user password](#retrueve-user-password)

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
    "verified": {
        "type": "user",
        "icon": null
    },
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

| Name | Description |
|:----:|----|
| id | User ID. |
| name | Username. |
| bio | A little bit about user. |
| sex | The user's gender, `0` - Unknown, `1` - Man, `2` - Woman. |
| location | The user's location. |
| created_at | User registration time. |
| updated_at | The update time of the user's main data. |
| following | Whether the user is following you. |
| follower | Whether you are following this user. |
| avatar | The user's avatar. |
| bg | The background image of this user. |
| extra.likes_count | The number of users who received the number of statistics. |
| extra.comments_count | The comments made by this user. |
| extra.followers_count | Follow this user's statistics. |
| extra.followings_count | This user follows the statistics. |
| extra.updated_at | Secondary data update time. |
| verified | This user does not have information, the default is `null`. |
| verified.type | Verified type. |
| verified.icon | Verified icon. |

### Get a user avatar

```
GET /users/:user/avatar
```

#### Parameters

| Name | Type | Description |
|:----:|:----:|----|
| s | Integer | Avatar size, Min: 0, Max : 500. |

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

| Name | Description |
|:----:|----|
| wallet.balance | The user's wallet balance. |
| wallet.updated_at | User last wallet trading time. |

## Update the authenticated user

- [Update avatar of the authenticated user](#update-avatar-of-the-authenticated-user)
- [Update background image of the authenticated user](#update-background-image-of-the-authenticated-user)
- [Update phone or email of the authenticated user](#update-phone-or-email-of-the-authenticated-user)
- [Update password or the authenticated user](#update-password-or-the-authenticated-user)

```
PATCH /user
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| name | String | The new name of the user. |
| bio | String | The new short biography of the user. |
| sex | Integer | The new sex of the user. |
| location | String | The new location of the user. |

##### Response

```
Status: 204 No Content
```

### Update avatar of the authenticated user

```
POST /user/avatar
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| avatar | File | The user's new avatar, *scale*: `1:1`, *size*: `100px` - `500px`. |

##### Response

```
Status: 204 No Content
```

### Update background image of the authenticated user

```
POST /user/bg
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| image | File | The user's new background image. |

##### Response

```
Status: 204 No Content
```

### Update phone or email of the authenticated user

```
PUT /user
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| phone | String | **Required without `email`**, The new phone of the user. |
| email | String | **Required without `phone`**, The new email of the user. |
| verifiable_code | Strint\|Number | **Required**, Verification code. |

##### Response

```
Status: 204 No Content
```

### Update password or the authenticated user

```
PUT /user/password
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| old_password | String | **Required**, User password. |
| password | String | **required**, The new password of the user. |
| password_confirmation | String | **Required**, Consistent with the user's new password. |

##### Response

```
Status: 204 No Content
```

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
| id | Integer \| String | Get multiple designated users, multiple `ID`s using `,` split. |

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

## Retrueve user password

```
PUT /user/retrieve-password
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| verifiable_type | Enum: mail, sms | **Required**, Notification serve verification type. |
| verifiable_code | Strint\|Number | **Required**, Verification code. |
| email | String | If the `verifiable_type` field is `mail`, then this field is required. |
| phone | String | If the `verifiable_type` field is `sms`, then this field is required. |
| password | String | The new passworf of the user. |

##### Response

```
Status: 204 No Content
```
