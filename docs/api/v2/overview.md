# 概述

整体 V2 文档前缀均为 `/api/v2` 所以在后面的接口中会省略前缀。

## Time Zone

接口中返回都带有时间，但是并没有按照 REST ful 的动态时间，而是发送的 UTC 事件格式，客户端需要自行处理本地化时间。

> UTC 即我们常说的 **零时区**。

## REST ful

所以接口尽量靠近 REST ful 规范进行开发，以请求方式标示「动作」，地址标示「资源」，HTTP 状态码标示是否是正确的。

> 不清楚 REST ful 请参考：《[阮一峰：RESTful API 设计指南](http://www.ruanyifeng.com/blog/2014/05/restful_api.html)》、《[RESTful - Wikipedia](https://en.wikipedia.org/wiki/Representational_state_transfer)》进行了解。

## Authorization

用户认证需要在 headers 中加入 Authorization，示例：

```shell
curl -v -H "Authorization: Bearer TOKEN" https://plus.io/api/v2/bootstrappers
```

> 格式为 `Authorization: Bearer TOKEN` 其中 **TOKEN** 是获取授权得到的。
> 针对 GET 请求在无法传递 Authorization 的情况下，增加了 URL 参数来进行认证。格式：
> `access_token=TOKEN` ， 如：`https://plus.io/api/v2/bootstrappers?access_token=TOKEN`
> 移动端管的 GET 请求通过请求头传递 Authorization.

## 媒体类型

接口不会直接给出 JSON 数据，所以在需要在 headers 中加入 Accept，示例：

```shell
curl -v -H "Accept: application/json" https://plus.io/api/v2/bootstrappers
```

## 错误响应

在请求错误的大多数情况下，返回的错误都是 `"inputName": ["message"]` 的格式，如下：

```json5
{
    "phone": [
        "手机号码错误"
    ],
    "password": [
        "用户密码错误"
    ]
}
```

这是可预知的错误情况，在不知道具体错误的情况：

```json5
{
    "message": [
        "错误消息1"
        // ...
    ]
}
```

> 在非程序主动抛出错误外，返回体不会有上述结构。
