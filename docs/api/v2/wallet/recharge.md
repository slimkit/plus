# 钱包余额充值

接口使用统一的接口对接所有的充值，对接第三发平台为 Ping++，请先阅读 Ping++ 官方文档。

```
POST /wallet/recharge
```

### Input

| 字段 | 必须 | 类型 | 描述 |
|----|:----:|:----:|:----:|
| type | 是 | string | 充值方式 （见「启动信息接口」或者「钱包信息」） |
| amount | 是 | int | 用户充值金额，单位为真实货币「分」单位 |
| extra | 否 | object,array | 拓展信息字段，见 [支付渠道-extra-参数说明](https://www.pingxx.com/api?language=PHP#支付渠道-extra-参数说明) |

```json5
{
    "type": "alipay_wap",
    "amount": 100, // 表示 1.00
    "extra": {
        "success_url": "https://plus.io/web/recharge" // 这个字段只是示例，这是 支付宝 wap 支付独有参数。
    }
}
```

##### Headers

```
Status: 201 Created
```

##### Body

```json5
{
  "id": 8, // ThinkSNS+ 系统凭据 ID
  "charge": { // Ping++ 凭据
    "id": "ch_08anD0a9yjPCLyvbTODqXrnT",
    "object": "charge",
    "created": 1496819712,
    "livemode": false,
    "paid": false,
    "refunded": false,
    "app": "app_5anXP4ezfXvL8m5e",
    "channel": "applepay_upacp",
    "order_no": "a0000000000000000008",
    "client_ip": "127.0.0.1",
    "amount": 500,
    "amount_settle": 500,
    "currency": "cny",
    "subject": "余额充值",
    "body": "账户余额充值",
    "extra": {},
    "time_paid": null,
    "time_expire": 1496906112,
    "time_settle": null,
    "transaction_no": null,
    "refunds": {
      "object": "list",
      "url": "/v1/charges/ch_08anD0a9yjPCLyvbTODqXrnT/refunds",
      "has_more": false,
      "data": []
    },
    "amount_refunded": 0,
    "failure_code": null,
    "failure_msg": null,
    "metadata": {},
    "credential": {
      "object": "credential",
      "applepay_upacp": {
        "tn": "201706071515122891443",
        "mode": "00",
        "merchant_id": "Your app merchant id"
      }
    },
    "description": null
  }
}
```

### Type 场景

| name | 描述 |
|----|----|
| applepay_upacp | Apple Pay (仅对 iOS 有效) |
| alipay | App 发起支付宝支付选项 |
| alipay_wap | 手机网页发起支付宝支付 |
| alipay_pc_direct | PC 网页发起支付宝支付 |
| alipay_qr | 支付宝扫码支付，前度生成二维码 |
| wx | App 发起微信支付 |
| wx_wap | 手机网页发起微信支付 |

