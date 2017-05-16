# 用户登录

用户登录获取授权 TOKEN

## 创建用户 TOKEN

```
POST /login
```

### 请求参数

| 名称 | 类型 | 描述 |
|----|:----:|----|
| phone | string | 用户用于登陆的中国大陆合法手机号码 |
| password | string | 用户登陆密码 |

### 响应

> Status: 201 Created

```json
{
  "token": "51WAM1rIOtJYMDIIeyGf2TUE7HCrjc7gFvP4dWWnEmnkWpjEtG42JaudABNyNjMM",
  "refresh_token": "Zbn2YQXNZfZa7zzJaZR7Y0n27OXY3E8NyKVdIvErnMlY3TdoTiXvBC5AZcUBaYjD",
  "user_id": 1,
  "expires": 0,
  "state": 1,
  "updated_at": "2017-05-16 05:50:37",
  "created_at": "2017-05-16 05:50:37",
  "id": 5
}
```
