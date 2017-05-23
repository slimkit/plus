# 创建用户（用户注册）

创建用户和用户成功后信息一致，区别在于用户登陆用于已存在用户，用户创建则实现无用户的情况下创建用户并返回承载令牌。

```
POST /users
```

### Input

| 名称 | 类型 | 描述 |
|----|:----:|:----:|
| name | string | 用户名，规则为 非数字和特殊字符开头，长度单字节字符算 0.5 长度，多字节字符算 1 长度，总长度不能超过 12 |
| phone | string | 大陆地区合法手机号码 |
| password | string | 用户密码，长度最小可无，最大不能超过 64 位 |
| verify_code | int,string | 手机验证码，长度在 4 - 6 位之间，目前暂定开发环境每 6 秒可获取一次，正式环境 300 秒。（以后增加后台可配） |

```json
{
    "phone": "187xxxxxxxx",
    "name": "user1",
    "password": "123456",
    "verify_code": "614873"
}
```

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
