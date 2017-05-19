# 获取单个用户信息

获取单一用户的基本信息。

```
GET /users/{user}
```

## Curl
| 名称 | 类型 | 描述 |
|------|:----:|------|
| user | int  | 用户id|

``curl -X GET /users/1 -H 'accept: application/json'``

### Response

##### Headers

```
Status: 200 OK
```

##### Body

```json5
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
}
```

