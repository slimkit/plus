# 概述

# 资讯部分具有投稿配置，配置信息会在「启动信息」接口中提供，格式如下：

```json5
{
    "news:contribute": [],
    "news:pay_conyribute": 100,
}
```

| `news:contribute` 用户投稿限制，数组中可能的组合有：

- `[]`：默认所有人自由投稿
- `['verified']`：只允许认证用户投稿
- `['pay']`：所有用户均可付费投稿
- `['verified', 'pay']`：只允许认证用户付费投稿

| `news:pay_conyribute` 付费投稿金额
