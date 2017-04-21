# 登录

## 接口地址

```
api/v1/auth
````

## 请求方式

`POST`

## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| phone    		| string   | yes   | 登录的用户手机号码 |
| password 		| string   | yes   | 用户密码 |
| device_code   | string   | yes   | 设备号 |

### HTTP Status Code

201

## 返回体

```json5
{
    "token": "bc272b2e87037ded8a5962b33a8cc054", //token
    "refresh_token": "2eb91c14e8e2780d8c2822ce69a30b3e", //刷新token
    "created_at": 1483098241, // token创建时间
    "expires": 0 // token生命周期，0为用不过期
    "user_id" : 1 //登录用户user_id
}
``` 

## 返回体字段说明

| name     | type     | description |
|----------|:--------:|:--------:|
| token    | string   | 用户认证token |
| refresh_token | string | 用户token刷新token |
| created_at | int | 用户权限token开始时间(单位秒的时间戳) |
| expires | int | 用户token的有效期(单位:秒) |

## 注意

为了避免出现移动端验证 token 有效,但是服务器验证 token 无效的情况,移动端会将 expires 时间减少 24小时.
