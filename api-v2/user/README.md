---
title: 用户接口
---

## 权限节点

```
GET /api/v2/user/abilities
```

响应：

```
Status: 200 OK
```
```json5
[
    {
        "name": "[feed] Delete Feed",     // 权限唯一标识
        "display_name": "[动态]->删除动态", // 权限显示名称
        "description": "删除动态权限"       // 权限描述
    }
]
```

## 根据标签查找用户

```
GET /api/v2/users
```

查询参数：

| 参数 | 类型 | 描述 |
|----|----|----|
| `tag` | `array` | **可选**，标签 ID |

例子：

```
/api/v2/users?tags[]=1&tags[]=3
```
上面的例子表示检索标签 ID 为 `1` 和 `3` 下面用户。

> 注意，这个接口来还有其他参数，轻查看旧文档网站接口参数
