# 自动认证

用户认证概述

## 自动认证+接口逻辑

![逻辑图](../../.images/api/auth-overview.png)

## 自动认证方式

自动认证是通过request header方式进行

| key      | description |
|----------|:--------:|
| Authorization | 用户认证token |

> 之前的 `ACCESS-TOKEN` 仍然保留，但会选择合适的时间移除，请开发人员使用 Authorization 替代。
