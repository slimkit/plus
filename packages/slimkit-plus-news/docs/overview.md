# 概述

# 资讯部分具有投稿配置，配置信息会在「启动信息」接口中提供，格式如下：

```json5
{
    "news:contribute": {
        "verified": true, // 是否开启只允许认证用户投稿
        "pay": true // 是否开启付费投稿
    },
    "news:pay_conyribute": 100, // 付费投稿金额，开启付费投稿时投稿会自动扣除
}
```
