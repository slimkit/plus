# 概述

整体 V2 文档前缀均为 `/api/v2` 所以在后面的接口中会省略前缀。

## Time Zone

接口中返回都带有时间，但是并没有按照 REST ful 的动态时间，而是发送的 UTC 事件格式，客户端需要自行处理本地化时间。

> UTC 即我们常说的 **零时区**。

## REST ful

所以接口尽量靠近 REST ful 规范进行开发，以请求方式标示「动作」，地址标示「资源」，HTTP 状态码标示是否是正确的。

> 不清楚 REST ful 请参考：《[阮一峰：RESTful API 设计指南](http://www.ruanyifeng.com/blog/2014/05/restful_api.html)》、《[RESTful - Wikipedia](https://en.wikipedia.org/wiki/Representational_state_transfer)》进行了解。
