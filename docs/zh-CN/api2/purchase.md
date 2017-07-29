# 付费

- [查询付费节点信息](#查询付费节点信息)
- [支付付费节点](#支付付费节点)

## 查询付费节点信息

查询信息还会返回当前认证用户的付费状态

```
GET /purchases/:node
```

#### 响应

```
Status: 200 OK
```

```json5
{
    "id": 9, // 付费节点 ID
    "channel": "feed", // 来自付费频道（应用）
    "raw": "13", // 频道关联 ID，例如频道是 feed 则字段就是 动态ID，如果是 file 则就是 file_with
    "subject": "购买动态《第12条测试？》", // 收费标题
    "body": "购买动态《第12条测试？》", // 收费内容
    "amount": 20, // 金额
    "user_id": 1, // 发布节点用户
    "extra": null, // 拓展信息，例如 channel 是 file 的时候字段则为 read 或者 download
    "created_at": "2017-06-21 01:54:52",
    "updated_at": "2017-06-21 01:54:52",
    "paid": true // 当前认证用户是否付费了该节点，如果认证用户是发布者，则直接是 true
}
```

## 支付付费节点

```
POST /purchases/:node
```

> 无需传递任何参数

#### 响应

```
Status: 201 Created
```
```
{
    "message": [
        "支付成功"
    ]
}
```