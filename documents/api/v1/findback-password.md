# 找回密码

## 接口地址

`/api/v1/auth/forgot`

## 请求方式

`PATCH`

### HTTP Status Code

200

## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| phone    | string   | yes      | 手机号码 |
| code     | int      | yes      | 手机号码验证码 |
| password | string   | yes      | 重置密码的新密码 |

## 返回体

无返回体
