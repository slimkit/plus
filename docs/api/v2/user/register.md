# 用户注册

根据手机号、验证码、密码等注册用户基本信息,注册完成后直接调用登录返回登录相关信息

```
POST /users
```

-----------

### Input

| 名称  | 类型   | 描述 |
|-------|:------:|------|
| phone | string | 被发送验证码的中国大陆合法手机号码 |
| name  | string | 4-48位且不与他人的用户名 |
| code  | int    | 300秒内注册手机号收到的合法验证码 |
| password | string | 无特殊符号的用户自定义密码 |

```json
{
    "phone": "手机号码",
    "name": "用户名",
    "code": "验证码",
    "password": "密码"
}
```

### Response

##### Headers

```
Status: 201 Created
```

##### Body

```json5
{
  "token": "08tdhLk4xi59AIfZOnHmMqy9nS0mpnYMo3oOEoH7ybhVBu1puUSFW8xQKdJxLDhv",
  "refresh_token": "XLS0pmhQ1fEWGwObXKWIkmNndyTFuf0D1nplerNan6whUD9RZQdTTEn37HOzj3yc",
  "user_id": 16,
  "expires": 0,
  "state": 1,
  "updated_at": "2017-05-19 07:49:51",
  "created_at": "2017-05-19 07:49:51",
  "id": 259
}
```

