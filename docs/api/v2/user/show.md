# 获取用户信息
    
- [获取当前用户](#获取当前用户)
- [获取指定用户](#获取指定用户)
- [批量获取指定用户](#批量获取指定用户)
- [用户头像](#用户头像)

### 获取当前用户

```
GET /user
```

##### Headers

```
Status: 200 OK
```

##### Body

```json5
{
    "id": 1, // 用户id
    "name": "创始人", // 用户名
    "phone": "187xxxxxxxx", // 用户手机号码
    "email": "shiweidu@outlook.com" // 用户邮箱
    "bio": "我是大管理员", // 用户简介
    "sex": 0, // 用户性别，0 - 未知，1 - 男，2 - 女
    "location": "成都市 四川省 中国", // 用户位置
    "created_at": "2017-06-02 08:43:54",
    "updated_at": "2017-07-06 07:04:06",
    "avatar": "http://plus.io/api/v2/users/1/avatar", // 头像
    "extra": {
        "user_id": 1,
        "likes_count": 0, // 被喜欢统计数
        "comments_count": 0, // 用户发出的评论统计
        "followers_count": 0, // 用户粉丝数
        "followings_count": 1, // 用户关注数
        "updated_at": "2017-07-16 09:44:25", // 更新时间
        "feeds_count": 0 // 发布的动态统计，没有安装 动态应用则不存在
    },
    "wallet": {
        "id": 1,
        "user_id": 1,
        "balance": 90, // 用户余额
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-05 08:29:49",
        "deleted_at": null
    }
}
```

### 获取指定用户

```
GET /users/1?following={user}&follower={user}
```

其中 `following`、`follower` 是可选参数，验证用户我是否关注以及是否关注我的用户 id ，默认为当前登陆用户。

##### Headers

```
Status: 200 OK
```

##### Body

```json5
{
    "id": 1, // 用户id
    "name": "创始人", // 用户名
    "bio": "我是大管理员", // 用户简介
    "sex": 0, // 用户性别，0 - 未知，1 - 男，2 - 女
    "location": "成都市 四川省 中国", // 用户位置
    "created_at": "2017-06-02 08:43:54",
    "updated_at": "2017-07-06 07:04:06",
    "avatar": "http://plus.io/api/v2/users/1/avatar", // 头像
    "following": false, // 获取用户是否关注了指定用户
    "follower": false, // 指定用户是否关注获取用户
    "extra": {
        "user_id": 1,
        "likes_count": 0, // 被喜欢统计数
        "comments_count": 0, // 用户发出的评论统计
        "followers_count": 0, // 用户粉丝数
        "followings_count": 1, // 用户关注数
        "updated_at": "2017-07-16 09:44:25", // 更新时间
        "feeds_count": 0 // 发布的动态统计，没有安装 动态应用则不存在
    }
}
```

### 批量获取指定用户

```
GET /users?user=1,2
```

> user 可以是一个值，或者多个值，多个值的时候用英文半角 `,` 分割。

##### Headers

```
Status: 200 OK
```

##### Body

```json5
[
    {
        "id": 1, // 用户id
        "name": "创始人", // 用户名
        "bio": "我是大管理员", // 用户简介
        "sex": 0, // 用户性别，0 - 未知，1 - 男，2 - 女
        "location": "成都市 四川省 中国", // 用户位置
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-06 07:04:06",
        "avatar": "http://plus.io/api/v2/users/1/avatar", // 头像
        "following": false, // 获取用户是否关注了指定用户
        "follower": false, // 指定用户是否关注获取用户
        "extra": {
            "user_id": 1,
            "likes_count": 0, // 被喜欢统计数
            "comments_count": 0, // 用户发出的评论统计
            "followers_count": 0, // 用户粉丝数
            "followings_count": 1, // 用户关注数
            "updated_at": "2017-07-16 09:44:25", // 更新时间
            "feeds_count": 0 // 发布的动态统计，没有安装 动态应用则不存在
        }
    }
]
```

## 用户头像

- [获取头像](#获取头像)
- [上传头像](#上传头像)

### 获取头像

```
GET /users/:user/avatar
```

#### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| s | Integer | 获取头像尺寸，范围是 0 - 500，其中 0 和 500 都是表示获取原始尺寸，默认值 0 |

##### Response

```
Status: 302 > 200 | 304
Etag: "59698999-592a"
```
> Etag 为 200 后缓存值。在用户资料中已经返回了 `avatar` 字段，为头像地址，也可使用接口拼接。

### 上传头像

```
POST /user/avatar
```

#### Input

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| avatar | File | 用户头像，文件限制，必须是正方形，切头像尺寸必须在 100px - 500px 之间。

##### Response

```
Status: 201 Created
```

```json
{
    "message": [
        "上传成功"
    ]
}
```
