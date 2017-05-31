# 钱包信息

钱包信息提供进入钱包页面所需的附加信息记录接口。

> 在未来开发中，一些信息可能会被移动到「启动者」中。

```
GET /wallet
```

##### Headers

```
Status: 200 OK
```

##### Body

```json5
{
    "labels":[ // 充值选项。
        550,
        2000,
        9900
    ],
    "ratio":200, // 转换比例（在启动者中也有提供哟）
    "rule":"我是积分规则纯文本.", // 充值提现规则。（以后需求中，可能是 markdown 目前是多行文本）
    "alipay":{ // 支付宝信息
        "open":false // 是否开启支付宝支付选项
    },
    "apple":{ // Apple Pay 信息 （iOS独享）
        "open":false // 是否开启 Apple Pay 支付选项
    },
    "wechat":{ // 微信支付信息
        "open":false // 是否开启微信支付选项
    },
    "cash": [ // 可选提现的「提现方式」，按照现在系统预设，只有 alipay 和 wechat
        "alipay"
    ]
}
```

> 目前部分是预设数据，例如 alipay 后续会增加，但是结构不会改。
