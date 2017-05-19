# 批量获取用户信息

批量获取用户的基本信息。

```
GET /users
```

### Curl

| 名称 | 类型 | 描述 |
|------|:----:|------|
| user | string | 用户id 多个以逗号隔开 |

``curl -X GET '/users?user=1%2C2' -H 'accept: application/json'``

### Response

##### Headers

```
Status: 200 OK
```

##### Body

```json5
[
  {
    "id": 1, // 用户id
    "name": "创始人", // 用户昵称
    "email": null, // 用户邮箱
    "phone": "admin", // 用户手机号/账户
    "created_at": "2017-04-28 07:49:48", // 用户数据创建时间
    "updated_at": "2017-04-28 07:49:48", // 用户数据更新时间
    "deleted_at": null, //用户软删除标记时间
    "is_followed": 0, // 是否关注当前登录用户
    "is_following": 0, // 当前登录用户是否关注
    "datas": [
      {
        "id": 1, // 用户资料字段id
        "profile": "sex", // 用户资料字段关键字
        "profile_name": "性别", // 用户资料名称
        "type": "radio", // 后台选项类型
        "default_options": "1:男|2:女|3:其他", // 后台选项可选类型
        "pivot": {
          "user_id": 1, 
          "user_profile_setting_id": 1,
          "user_profile_setting_data": "1", // 用户资料字段值
          "created_at": "2017-04-28 07:49:48",
          "updated_at": "2017-04-28 07:49:48"
        }
      },
      // ...
      // more
    ],
    "counts": []  // 用户统计数据
  },
  {
    "id": 2,
    "name": "测试服账号",
    "email": null,
    "phone": "13488975248",
    "created_at": "2017-05-02 01:58:20",
    "updated_at": "2017-05-02 01:58:20",
    "deleted_at": null,
    "is_followed": 0,
    "is_following": 0,
    "datas": [],
    "counts": [
      {
        "id": 3,
        "user_id": 2,
        "key": "feeds_count", // 用户发出的动态数
        "value": "1",
        "created_at": "2017-05-02 02:15:14",
        "updated_at": "2017-05-02 02:15:14"
      },
      {
        "id": 16,
        "user_id": 2,
        "key": "diggs_count", // 用户收到的点赞数
        "value": "0",
        "created_at": "2017-05-04 02:01:54",
        "updated_at": "2017-05-04 02:02:06"
      }
    ]
  }
]
```

