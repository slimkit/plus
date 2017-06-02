# 提现相关

用户账户提现相关接口

- [提现申请](#提现申请)
- [提现申请列表](#提现申请列表)

## 提现申请

```
POST /wallet/cashes
```

### Input

| 名称 | 类型 | 描述 |
|----|:----:|:----:|
| value | int | 用户需要提现的余额数量 |
| type | string | 用户提现账户方式 |
| account | string | 用户提现账户 |

```json5
{
    "value": 100,
    "type": "alipay"
    "account": "xxx@alipay.com"
}
```

> value 的值是用户输入的余额值按照「转换比例」转换后再转化为「分」单位
> 分的转换值为 100，按照比例转换单位后乘100以保证没有小数出现

##### Headers

```
Status: 201 Created
```

##### Body

```json5
{
  "value": [
    "请输入提现金额", // 用户没用输入
    "发送的数据错误", // 发送错误的数据给服务器（非正整数）
    "输入的提现金额不合法", // 发送给服务器小于 1
    "提现金额超出账户余额", // 用户提现金额超出余额
  ],
  "type": [
    "请选择提现方式", // 没有发送提现方式
    "你选择的提现方式不支持" // 提现的方式后台设置不允许提现
  ],
  "account": [
    "请输入你的提现账户" // 没有输入账户
  ],
  "message": [
    "申请提现成功", // 成功
    "申请失败" // 失败
  ]
}
```

## 提现申请列表

```
GET /wallet/cashes
```

> limit=20&after=3
> limit 可以设置获取数量
> after 获取更多数据，上一次获取列表的最后一条 ID

#### Headers

```
Status: 200 OK
```

#### Body

```json5
[
  {
    "id": 4, // 提现记录ID
    "value": 10, // 提现金额
    "type": "alipay", // 提现方式
    "account": "xxx@alipay.com", // 提现账户
    "status": 0, // 提现状态， 0 - 待审批，1 - 已审批，2 - 被拒绝
    "remark": null, // 备注，审批或者拒绝的时候由管理填写
    "created_at": "2017-06-01 09:30:22" // 申请时间
  },
  {
    "id": 3,
    "value": 10,
    "type": "wechat",
    "account": "xxxx",
    "status": 0,
    "remark": null,
    "created_at": "2017-06-01 09:29:09"
  }
]
```
