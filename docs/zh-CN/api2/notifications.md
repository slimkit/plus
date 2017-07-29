# 消息通知

- [未读通知数量检查](#未读通知数据检查)
- [通知列表](#通知列表)
- [读取通知](#读取通知)
- [标记通知阅读](#标记通知阅读)
- [数据解析](#数据解析)

## 未读通知数量检查

```
HEAD /user/notifications
```

本接口可用于消息分组显示的客户端，可以提前得到未读消息数量，然后将 `Unread-Notification-Limit` 拼接为 `limit` 参数，type 设置为 `unread` 得到全部未读消息。

> 查看 [通知列表](#通知列表) 请求头


## 通知列表

```
GET /user/notifications
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 获取条数，默认 20 |
| offset | Integer | 数据偏移量，默认 0 |
| type | String | 获取通知类型，可选 `all`,`read`,`unread` 默认 `all` |
| notification | String\|Array | 检索具体通知，可以是由 `,` 拼接的 IDs 组，也可以是 Array |

#### Response

```
Status: 200 OK
Unread-Notification-Limit: 10
```
```json5
[
    // ...
    {
        "id": "98aaae93-9d9e-446e-b894-691569b686b5",
        "read_at": null,
        "data": {
            "channel": "feed:pinned",
            "target": 1,
            "content": "我是测试消息",
            "extra": []
        },
        "created_at": "2017-07-10 04:23:08"
    }
]
```

> `Unread-Notification-Limit` 为当前用户未读消息数量。
> 详情数据结构参考[数据解析](#数据解析)

## 读取通知

```
GET /user/notifications/:notification
```

#### Response

```
Status: 200 OK
```
```json
{
    "id": "98aaae93-9d9e-446e-b894-691569b686b5",
    "read_at": "2017-07-10 09:31:04",
    "data": {
        "channel": "feed:pinned",
        "target": 1,
        "content": "我是测试消息",
        "extra": []
    },
    "created_at": "2017-07-10 04:23:08"
}
```

> 读取通知详情，会使这条通知状态变为已读状态。

## 标记通知阅读

```
PATCH /user/notifications/:notification?
```

> notification 为可选，为单条标记提供快捷而已。

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----:
| notification | String\|Array | 通知ID，可以是由 `,` 拼接的 IDs 组，也可以是 Array |

#### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "操作成功"
    ]
}
```

---------------

## 数据解析

Example:

```json
{
    "id": "98aaae93-9d9e-446e-b894-691569b686b5",
    "read_at": null,
    "data": {
        "channel": "feed:pinned-comment",
        "target": 1,
        "content": "我是测试消息",
        "extra": []
    },
    "created_at": "2017-07-10 04:23:08"
}
```

#### id

通知ID

#### read_at

消息阅读时间，如果是未读消息，则值为 `null`

#### created_at

消息创建时间

#### data

消息数据详情

##### channel

通知来源频道，客户端需要根据 `data.channel` 值进行独立解析。已知频道:

- feed:comment 动态被评论
- feed:reply-comment 动态评论被回复
- feed:pinned-comment 动态评论申请置顶
- feed:digg 动态被点赞

##### target

根据 `data.channel` 的目标标识，例如 `channel=feed:comment` 则表示 `target` 为 动态 ID。

##### content

消息内容

##### extra

为 `Array|Object` 类型，来自 `channel` 标识的额外数据，具体参见不同标识详情进行解析使用。