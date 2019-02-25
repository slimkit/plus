---
title: 通知
---

通知类型：

| 类型 | 描述 |
|----|-----|
| `at` | At 我的 |
| `comment` | 评论我的 |
| `like` | 喜欢我的 |
| `system` | 系统通知 |
| `follow` | 用户粉丝 |

## 获取通知统计

```
GET /api/v2/user/notification-statistics
```

响应：
```json
 Status: 200 OK
 {
    "at": {
        "badge": 4,
        "last_created_at": "2019-02-21T10:09:04Z",
        "preview_users_names": [
            "用户1"
        ]
    },
    "comment": {
        "badge": 10,
        "last_created_at": "2019-02-21T10:09:03Z",
        "preview_users_names": [
            "用户1"
        ]
    },
    "like": {
        "badge": 1,
        "last_created_at": "2019-02-22T06:28:39Z",
        "preview_users_names": [
            "用户1"
        ]
    },
    "system": {
        "badge": 0,
        "first": {
            "id": "4307b14e-3576-45cc-8922-f4e388cfa924",
            "created_at": "2019-02-22T06:28:39Z",
            "read_at": null,
            "data": {
                "sender": {
                    "id": 2,
                    "name": "用户1"
                },
                "resource": {
                    "type": 1
                }
            }
        }
    },
    "follow": {
        "badge": 0
    }
}
```

## 获取通知列表

```
GET /api/v2/user/notifications
```

查询参数：

| 字段 | 描述 |
|----|----|
| `type` | **必须**，请传递通知类型 |
| `page` | **可选**，分页页码，默认 1 |

响应：

```json5
Status: 200 OK
{
    "data": [ // 通知列表
        {
            "id": "28290050-b655-4709-b004-dd1be04974e8",
            "created_at": "2019-02-21T10:09:03Z",
            "read_at": null,
            "data": { // 通知荷载数据
                "contents": "\u00ad@root\u00ad 测试 at 消息2",
                "sender": {
                    "id": 2,
                    "name": "用户1"
                },
                "commentable": {
                    "type": "feeds",
                    "id": 1
                }
            }
        }
    ],
    "links": { // 分页接口地址
        "first": "http://plus.local.medz.cn/api/v2/user/notifications?type=comment&page=1", // 第一页
        "last": "http://plus.local.medz.cn/api/v2/user/notifications?type=comment&page=1", // 最后一页
        "prev": null, // 上一页
        "next": null // 下一页
    },
    "meta": { // 分页元数据
        "current_page": 1, // 当前页码
        "from": 1,
        "last_page": 1,
        "path": "http://plus.local.medz.cn/api/v2/user/notifications",
        "per_page": 15, // 每页数据条数
        "to": 10,
        "total": 10 // 数据总条数
    }
}
```

## 标记通知已读

```
PATCH /api/v2/user/notifications
```

输入：

| 名称 | 类型 | 描述 |
|----|----|----|
| `type` | `string` | **必须**，需要标记已读的通知类型 |

响应：

```
Status: 204 No Content
```

## 清理新增关注统计数量

```
PATCH /api/v2/user/clear-follow-notification
```

响应：

```
Status: 204 No Content
```
