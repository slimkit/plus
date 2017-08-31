# Users

- [获取一个用户](#获取一个用户)
- [获取认证用户资料](#获取认证用户资料)
- [更新认证用户资料](#更新认证用户资料)
- [获取所有用户](#获取所有用户)
- [用户找回密码](#用户找回密码)
- [解除用户 Phone 或者 E-Mail 绑定](#解除用户-phone-或者-e-mail-绑定);

## 获取一个用户

- [获取一个用户头像](#获取一个用户头像)

```
GET /users/:user
```

#### 参数

| 参数 | 类型 | 描述 |
|:----:|:----:|----|
| following | Integer | 检查请求用户是否关注了指定的用户，传递要检查的用户 ID，默认为当前登录用户。 |
| follower | Integer | 检查请求用户是否被某个用户关注，传递要检查的用户 ID，默认为当前登录用户。 |

##### 响应

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
        "icon": null,
        "description": "xxxxx"
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

| 字段 | 描述 |
|:----:|----|
| id | 用户 ID。 |
| name | 用户名 |
| bio | 用户的个人简介。（用户的简短传记或者描述） |
| sex | 用户性别, `0` - Unknown, `1` - 男, `2` - 女. |
| location | 用户的位置信息 |
| created_at | 用户的注册时间 |
| updated_at | 用户核心资料更新时间 |
| following | 这个用户是否关注了你，或者你指定检查的用户。 |
| follower | 你是否是这个用户的关注者，或者你指定的用户。 |
| avatar | 用户头像接口地址，没有头像为 `null`。 |
| bg | 用户背景图片地址，没有背景图片为 `null`。 |
| extra.likes_count | 用户收到的喜欢（赞）统计总数。 |
| extra.comments_count | 用户所发出的评论总数统计 |
| extra.followers_count | 这个用户的关注者总数统计。 |
| extra.followings_count | 这个用户关注了多少人总数统计。 |
| extra.updated_at | 用户次要资料更新时间。 |
| verified | 用户的认证信息，未认证用户该值为 `null`。 |
| verified.type | 用户认证类型。字符串，`user`、`org` |
| verified.icon | 用户认证类型的 Icon。图片地址。 |
| verified.description | 用户认证描述 |

### 获取一个用户头像

```
GET /users/:user/avatar
```

#### 参数

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| s | 数字 | 用户尺寸，最小 0，最大 500. |

##### 响应

```
Status: 302 > 200 | 304
Etag: "59698999-592a"
```

## 获取认证用户资料

```
GET /user
```

##### 响应

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

| 名称 | 描述 |
|:----:|----|
| phone | 用户手机号码。 |
| email | 用户电子邮箱地址 |
| wallet.balance | 用户钱包余额。 |
| wallet.updated_at | 用户上次产生交易时间。 |

> 只有认证用户才获取得到钱包信息和手机邮箱等敏感信息。

## 更新认证用户资料

- [更新认证用户头像](#更新认证用户头像)
- [更新认证用户背景图片](#更新认证用户背景图片)
- [更新认证用户的手机号码和邮箱](#更新认证用户的手机号码和邮箱)
- [更新认证用户密码](#更新认证用户密码)

```
PATCH /user
```

#### 输入

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| name | 字符串 | 用户新的用户名。 |
| bio | 字符串 | 用户新的个人传记。 |
| sex | 数字 | 用户新的性别。 |
| location | 字符串 | 用户新的位置信息。 |

##### 响应

```
Status: 204 No Content
```

### 更新认证用户头像

```
POST /user/avatar
```

#### Input

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| avatar | 文件 | 用户新的头像，头像比例为：`1:1`，尺寸范围：`100px - 500px` |

##### 响应

```
Status: 204 No Content
```

### 更新认证用户背景图片

```
POST /user/bg
```

#### Input

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| image | 文件 | 用户新的背景图片。 |

##### 响应

```
Status: 204 No Content
```

### 更新认证用户的手机号码和邮箱

```
PUT /user
```

#### 输入

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| phone | 字符串 | **如果 `email` 不存在则必须**，用户新的手机号码。 |
| email | 字符串 | **如果 `phone` 不存在则必须**，用户新的邮箱地址。 |
| verifiable_code | 字符串或者数字 | **必须**，验证码。 |

##### 响应

```
Status: 204 No Content
```

### 更新认证用户密码

```
PUT /user/password
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| old_password | 字符串 | **用户已设置密码时必须**，用户密码。 |
| password | 字符串 | **必须**，用户的新密码 |
| password_confirmation | 字符串 | **必须**，用户的新密码，必须和 `password` 一致。 |

##### 响应

```
Status: 204 No Content
```

## 获取所有用户

```
GET /users
```

#### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | 整数 | 请求获取的数据量，默认为 `20` 条，最低获取 `1` 条，最多获取 `50` 条。 |
| order | 枚举：`asc` 或者 `desc` | 排序方式，默认 `desc`。 |
| since | 整数 | 上次请求的最后一条的 `id` ，用于获取这个用户之后的数据。 |
| name | 字符串 | 用于检索包含 `name` 传递字符串用户名的用户。 |
| id | 整数或者字符串 | 获取一个或者多个指定的用户，如果获取多个请使用 `,` 将用户 ID进行字符串拼接。 |

##### 响应

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

## 用户找回密码

```
PUT /user/retrieve-password
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| verifiable_type | 枚举：`mail` 或者 `sms` | **必须**，验证码发送模式。 |
| verifiable_code | 字符串或者整数 | **必须**，用户收到的验证码。 |
| email | 字符串 | 如果 `verifiable_type` 值为 `mail`，那么这个字段为必须，用户邮箱。 |
| phone | 字符串 | 如果 `verifiable_type` 值为 `sms`。那么这个字段为必须，用户手机号码。 |
| password | 字符串 | 用户新密码。 |

##### 响应

```
Status: 204 No Content
```

## 解除用户 Phone 或者 E-Mail 绑定

解除用户 Phone 绑定:

```
DELETE /api/v2/user/phone
```

解除用户 E-Mail 绑定:

```
DELETE /api/v2/user/email
```

#### 输入

| 名称 | 类型 | 描述 |
|:-----:|:----:|----|
| password | String | 用户密码。 |
| verifiable_code | Int 或者 String | 手机号码或者邮箱验证码。 |

#### 响应

```
Status: 204 No Content
```
