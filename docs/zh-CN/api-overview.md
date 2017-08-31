# Overview

- [选择版本](#选择版本)
- [媒体类型](#媒体类型)
- [架构](#架构)
- [时区](#时区)
- [参数](#参数)
- [HTTP 重定向](#http-重定向)
- [HTTP 动词](#http-动词)
- [速率限制](#速率限制)
- [授权](#授权)
- [自定义的 Markdown 标签](#自定义的-markdown-标签);
- [消息列举](#messages)

## 选择版本

当前接口情况下，所有的请求都应该发送到 `/api/v2` 上。

## 媒体类型

API 在大多数情况下会直接给出 **JSON** 格式的数据，但是一些通用异常处理，例如「验证错误」的返回，会依据请求环境选择，所以客户端在调用接口的时候应当在请求头重设置 **媒体类型** 。

想要 API 在正常情况下都给出 **JSON** 数据，应当在请求头中设置 `Accept` ，并且其值应该为 `application/json`:

```shell
curl -v -H "Accept: application/json" https://plus.io/api/v2/bootstrappers
```

## 架构

所有的数据都应该使用 **JSON** 来进行传输交换。

可选字段在大多数时候允许值为 `null`, 但是不建议这么做，一些接口是强制性的要求省略字段，所以可选字段在为空的情况下可以不用传递。

## 时区

所有的时间都会是 **UTC** 格式：

> YYYY-MM-DD HH:MM:SS

## 参数

许多 API 采用可选参数，对于 GET 请求任何参数都可以在 URL 中传递：

```shell
curl -i "https://plus.io/api/v2/users?user=1"
```

对于 POST 、 PATCH 、 PUT 和 DELETE 请求， URL 中没有包含的参数应该为请求体的 JSON，其中，请求头应该设置 `Content-Type` 为 `application/json` ：

```shell
curl -i -d '{"login": ":username", "password": ":password"}' https://plus.io/api/v2/tokens
```

## HTTP 重定向

API 会在适当的情况下使用 HTTP 重定向，客户端应该假设任何请求都有可能导致重定向。接收 HTTP 重定向不是错误，所以客户端应该遵循该重定向的要求。重定向具有一个 `Location` 响应头标记，该标记为客户端应该重新请求的新 URI。

| 状态码 | 描述 |
|:----|----|
| 301 | 永久重定向。你所发出的 URI 被 `Location` 所指定的 URI 所取代，以后所有对于这个资源的请求都会被标记到这个新的 URI 上。 |
| 302, 307 | 临时重定向。这个请求应该请求 `Location` 所指定的新 URI 上，但是客户端应该继续使用原来的 URI 进行资源请求。 |

## HTTP 动词

在允许的情况下，API 为每个动作使用适当的 HTTP 动词。

| 动词 | 描述 |
|:----:|----|
| HEAD | 可以针对任何资源发出HTTP头信息。 |
| GET | 用于获取一个资源 |
| POST |用于创建一个资源 |
| PATCH | 用于更新部分资源，PATCH 可以接受一个或者多个来更新某个资源。 |
| PUT | 用于整体替换、创建或者使用集合，对于没有 body 的 PUT 请求，请确保请求头 `Content-Length` 设置为零。 |
| DELETE | 用于删除一个资源。 |

## 速率限制

任何 API 请求的 HTTP 头都会返回你当前的速率限制情况：

```shell
curl -i https://plus.io/api/v2/users/1
HTTP/1.1 200 OK
Date: Mon, 01 Jul 2017 17:27:06 GMT
Status: 200 OK
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 56
```

| 请求头字段 | 描述 |
|:----:|----|
| X-RateLimit-Limit | 允许每分钟最大的请求数量总数。 |
| X-RateLimit-Remaining | 你当前请求后剩余的请求数率数量。 |

速率的间隔刷新时间为一分钟。

## 授权

一旦用户使用授权凭证，下一步应该使用令牌来请求用户信息，以便可以将其显示为“已登陆”。

要使用 HTTP 认证，你应该设置请求头的 `Authorization`:

```
Authorization: Bearer {TOKEN}
```

或者你可以使用请求参数来包含令牌：

```
https://plus.io/api/v2/user?token={TOKEN}
```

在请求需要授权的接口时，若返回`Http Status`为`401`即为授权失效，此时需要刷新授权，若刷新授权失败，则说明该授权失效，需要重新获取授权。

## 自定义的 Markdown 标签

| 标签 | 描述 |
|:----:|----|
| `@![title](file id)` | 改造自 `![title](url)`, 增加 `@` 前缀来表示为系统图片附件，而 `url` 更改为文件 ID。 |

<a name="messages"></a>
## 消息列举 {#messages}

正确情况有以下三种情况：

第一种：
```json
{ "message": "This is a message." }
```

第二种：
```json
{ "message": [ "This is a message array item." ] }
```

第三种：
```json
{ ... }
```

> 第三种是获取数据常用情况，`...` 代表的就是实际数据。

错误消息响应体：

第一种：
```json
{ "message": "this is a message." }
```

第二种：
```json
{ "message": [ "This is amessage array item." ] }
```

第三种：
```json
{
    "key": [ "value" ],
    "key2": [ "value", "value2" ]
}
```

第四种：
```json
{
    "message": "This is a message",
    "errors": {
        "key1": [ "value1" ],
        "key2": [ "value1", "value2" ]
    }
}
```

第五种（这种情况，移动端可以当「第一种」处理，并抛弃掉其他字段，这种情况的出现是由于服务器需要 debug）：
```json
{
    "message": "xxx",
    "file": "xxx",
    "line": 100,
    "trace": []
}
```
