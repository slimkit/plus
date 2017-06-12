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
    // 可选提现的「提现方式」，按照现在系统预设，只有 alipay 和 wechat
    // type: array|null 如果 alipay 和 wechat 都不存在，则代表关闭提现功能
    "cash": [
        "alipay"
    ],
    "case_min_amount": 1, // 真实金额分单位，用户最低提现金额。
    "recharge_type": [ // 对于移动端而言，alipay wx 不存在则表示关闭了充值功能，单个不存在则表示关闭单个充值选项，iOS多一个 apple pay 选项，其他端，例如 h5 或者 pc 参考平台后缀。例如没有 alipay_wap 表示关闭 h5 的支付宝。
        "alipay",
        "alipay_wap",
        "wx",
        "wx_wap",
        "applepay_upacp"
    ]
}
```
