# 概述

## 数据结构

因为后端为PHP的关系，所以，在返回数据的时候，如果是空，将会返回 `[]`、对象情况为 `{...}`

## Timezones
接口返回的所有API中如果有携带时间，其格式均为时间空间字符串格式，时区都为UTC+0时区的时间，请自行本地化。

## 多个数字
接口返回的所有数据,如果携带多个数字时,其格式均为数字组成的数组,而不是字符串.`返回[1, 2, 3]而不是"1,2,3".`

请求接口时,携带的参数有多个数字时,非必要尽量使用数组.`例如 get 请求携带参数时可以使用字符串格式数组,具体细节看相关接口说明.`

# 前置信息

前置信息会描述 网络状态和请求体状态的关系。

## RESTful API

99% 的接口都会按照 RESTful 要求返回状态消息，所以，结构体外层是建立在需要返回 response 的情况下才有的。

> 其中，RESTful 状态消息和 response 外层结构状态码并不冲突，RESTful 是为了标识请求状态，外层结构状态码是为了使得客户端能更加友好的提示在 UI 层面。

> RESTful API 不太具体要求请查看 [阮一峰：RESTful API 设计指南](http://www.ruanyifeng.com/blog/2014/05/restful_api.html) 具体规范请查看 [RESTful - Wikipedia](https://en.wikipedia.org/wiki/Representational_state_transfer)

> laravel框架内  设置HTTP Status Code 为 `204`、`304` 时，将不会返回数据体

## response外层结构体
```json
{
    "status": false,
    "code": 0,
    "message": "",
    "data": null
}
```
| name     | type     | default  |description|
|----------|:--------:|:--------:|:---------:|
| status   | bool     | false    | 标示请求处理的处理类型，true: 成功处理，false: 错误处理|
| code     | int      | 0        | 消息码，可能涉及到友好的用户界面提示消息代码|
| message  | string   | ""       | 消息，多数用于不协商code消息码场景，后端直接给出消息，前端展示消息等场景 |
| data     | any      | null     | 消息数据体，any类型，代表任意类型数据，数据类型根据API的不同决定。|

⚠️：
- 按照 RESTful API 状态要求所有有返回体的response最外层都将是上述结构体，所以注意下data字段的any类型解析。
- API请求实例cURL中的地址为相对地址，参数全部为test数据，并不保证真实有效，请自动行补全。

## 字段详解
* status: 接口请求，正确处理是 true, 错误处理是 false, 如果返回false,一定是错误信息输出.
* code: 当status是true的时候不会存在code信息，所以是默认值“0”，当false的时候会存在code码，当然，也存在特殊情况，例如后台需要强制输出提示信息的时候，message字段会存在信息，这个时候参照码不再是code，而是依据status状态输出message信息.
* message: 消息，字段不和任何字段挂钩，但是优先级低于于code，行为单一，code用于标示错误类型，客户端依据code作出友好的提示文案，message信息则更弱势一些，如果存在message信息，但是不存在coce则输出message信息。
* data: 当接口处理成功的返回数据存放字段，一般情况下只有status为true的时候会存在信息，但是不排除debug模式中便于接口调试信息（客户端无须处理）。部分接口即是处理成功也不需要返回信息 ，例如获取验证码接口。只需要标示status位true即可。

## http(s) status codes.
在处理请求中，一但前端服务器没有拦截请求并自己返回结果，且把请求转发到程序中。

程序会依据不同的处理结果，抛出不同的http响应状态码，和响应数据，响应状态码应该参照[HTTP状态码](https://zh.wikipedia.org/wiki/HTTP%E7%8A%B6%E6%80%81%E7%A0%81)

## 推送参数格式

推送的别名统一使用用户的uid注册

在推送中，extras有两个必要参数 

`type` 推送模块类型 

| name | description  |
|------|:------------:|
| feed | 动态模块     |
| im   | 即时聊天模块 |
| channel | 频道模块  |
| music | 音乐模块    |
| news | 资讯模块     |
| user | 用户模块     |
| system | 系统通知模块 |

`action` 推送操作类型

| name | description  |
|------|:------------:|
| comment | 评论操作  |
| digg | 点赞操作     |
| follow | 关注操作   |
| notice | 系统通知   |
