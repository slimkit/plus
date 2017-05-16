# 用户注册

## 接口地址

`/api/v1/auth/register`

## 请求方式

`POST`

### HTTP Status Code

201

## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| phone    | string   | yes      | 注册的手机号码 |
| name     | string   | yes      | 用户名 |
| code     | int      | yes      | 手机验证码 |
| password | string   | yes      | 用户密码 |
| device_code | string   | yes   | 设备号 |

## 返回体

注册密码不会在服务端做任何处理然后直接进行加密存储
为了与pc/h5同步，移动端请不要对密码做加密
注册成功后自动调用[auth](用户登录.md)接口，请查看login接口response.
