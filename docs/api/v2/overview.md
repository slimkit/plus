# Overview

- [Current Version](#current-version)
- [Media Types](media-types)
- [Schema](#schema)
- [Timezone](#timezone)
- [Parameters](#parameters)
- [HTTP Redirects](#http-redirects)
- [HTTP Verbs](#http-verbs)
- [Rate Limiting](#rate-limiting)
- [Authorization](#authorization)
- [Customize the markdown tag](#customize-the-markdown-tag);

## Current Version

In the case of the current interface, all interfaces should be sent to `/api/v2`.

## Media Types

API some of the case will not give **json** directly, so the client should be in the request when the head of the request in the need to set the **type of media**:

API in most cases will not be given directly json, so the client should be sent in the request header to add the **Accept** field and set the value for the `application/json`:

```shell
curl -v -H "Accept: application/json" https://plus.io/api/v2/bootstrappers
```

## Schema

All data is sent and received as JSON.

Blank fields are allowed in most cases as `null`, but there are some interfaces that need to omit fields to choose to pass.

## Timezone

All timestamps return in **UTC** format:

> YYYY-MM-DD HH:MM:SS

## Parameters

Many API methods take optional parameters. For GET requests, any parameters not specified as a segment in the path can be passed as an HTTP query string parameter:

```shell
curl -i "https://plus.io/api/v2/users?user=1"
```

For POST, PATCH, PUT, and DELETE requests, parameters not included in the URL should be encoded as JSON with a **Content-Type** of `application/json`:

```shell
curl -i -d '{"login": ":username", "password": ":password"}' https://plus.io/api/v2/tokens
```

## HTTP Redirects

API v2 uses HTTP redirection where appropriate. Clients should assume that any request may result in a redirection. Receiving an HTTP redirection is not an error and clients should follow that redirect. Redirect responses will have a Location header field which contains the URI of the resource to which the client should repeat the requests.

| Status Code | Description |
|:----|----|
| 301 | Permanent redirection. The URI you used to make the request has been superseded by the one specified in the Location header field. This and all future requests to this resource should be directed to the new URI. |
| 302, 307 | Temporary redirection. The request should be repeated verbatim to the URI specified in the Location header field but clients should continue to use the original URI for future requests. |

## HTTP Verbs

Where possible, API v2 strives to use appropriate HTTP verbs for each action.

| Verb | Description |
|:----:|----|
| HEAD | Can be issued against any resource to get just the HTTP header info. |
| GET | Used for retrieving resources. |
| POST | Used for creating resources. |
| PATCH | Used for updating resources with partial JSON data. For instance, an Issue resource has title and body attributes. A PATCH request may accept one or more of the attributes to update the resource. PATCH is a relatively new and uncommon HTTP verb, so resource endpoints also accept POST requests. |
| PUT | Used for replacing resources or collections. For PUT requests with no body attribute, be sure to set the Content-Length header to zero. |
| DELETE | Used for deleting resources. |

## Rate Limiting

The returned HTTP headers of any API request show your current rate limit status:

```shell
curl -i https://plus.io/api/v2/users/1
HTTP/1.1 200 OK
Date: Mon, 01 Jul 2017 17:27:06 GMT
Status: 200 OK
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 56
```

| Header Name | Description |
|:----:|----|
| X-RateLimit-Limit | The maximum number of requests you're permitted to make per hour. |
| X-RateLimit-Remaining | The number of requests remaining in the current rate limit window. |

Limit the rate interval to one minute

## Authorization

Once a user has logged in with their credentials, then the next step would be to make a subsequent request, with the token, to retrieve the users' details, so you can show them as being logged in.

To make authenticated requests via http using the built in methods, you will need to set an authorization header as follows:

```
Authorization: Bearer {TOKEN}
```

Alternatively you can include the token via a query string

```
https://plus.io/api/v2/user?token={TOKEN}
```

## Customize the markdown tag

| Tag name | Description |
|:----:|----|
| `@![title](file id)` | 改造自 `![title](url)`, 增加 `@` 前缀来表示为系统图片附件，而 `url` 更改为文件 ID。 |
