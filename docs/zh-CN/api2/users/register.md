# 用户注册

```
POST /users
```

### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| name | 字符串 | **必须**，用户名 |
| phone | 字符串 | **如果 `verifiable_type` 为 `sms` 则必须**, 手机号码。 |
| email | String | **如果 `verifiable_type` 为 `mail` 则必须**, E-Mail。 |
| password | String | **必须**，密码。 |
| verifiable_type | 枚举: `mail` 或 `sms` | **必须**，验证码发送模式。 |
| verifiable_code | 字符串或数字 | **必须**，用户收到的验证码。 |

#### 响应

```
Status: 201 Created
```
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9wbHVzLmlvL2FwaS92Mi90b2tlbnMiLCJpYXQiOjE1MDAzNjU5MzQsImV4cCI6MTUwMTU3NTUzNCwibmJmIjoxNTAwMzY1OTM0LCJqdGkiOiJ1aXlvdTQwNnJsdU9pa3l3In0.OTM4mbH3QW7busunRsFUsheE5vysuIfrBrwjWnd0J6k",
    "ttl": 20160,
    "refresh_ttl": 40320
}
```
