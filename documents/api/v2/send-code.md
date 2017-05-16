# 发送手机验证码

## 接口地址

`api/v2/auth/phone/{phone}/code`

## 请求方式

`POST`

### HTTP Status Code

201

## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| type     | string   | yes      | 发送验证码的类型，固定三个值(register、login、change) <br /> register: 注册 <br /> login: 登录 <br /> change: 修改 |

## 返回体

无返回体