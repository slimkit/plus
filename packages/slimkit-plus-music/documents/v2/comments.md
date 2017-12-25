# 评论

- [音乐评论列表](#音乐评论列表)
- [专辑评论列表](#专辑评论列表)
- [添加音乐评论](#添加音乐评论)
- [添加专辑评论](#添加专辑评论)
- [删除音乐评论](#删除音乐评论)
- [删除专辑评论](#删除专辑评论)

## 音乐评论列表

```
GET /music/{music}/comments
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 15 ，获取条数 |
| max_id | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

#### Response

```
Status: 200 OK
```

```json5
[
    {
        "id": 2,
        "commentable_type": "musics", // 资源标识
        "commentable_id": 1, // 资源id
        "user_id": 1, // 评论者
        "target_user": 0, // 资源作者
        "reply_user": 0, // 回复者
        "created_at": "2017-07-24 15:09:27",
        "updated_at": "2017-07-24 15:09:28",
        "body": "啦啦啦啦啦" // 评论内容
    }
]
```

## 专辑评论列表

```
GET /music/specials/{special}/comments
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 15 ，获取条数 |
| max_id | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

#### Response

```
Status: 200 OK
```

```json5
[
    {
        "id": 1,
        "commentable_type": "music_specials", // 资源标识
        "commentable_id": 1,
        "user_id": 1,
        "target_user": 0,
        "reply_user": 0,
        "created_at": "2017-07-24 15:09:27",
        "updated_at": "2017-07-24 15:09:28",
        "body": "辣鸡啊"
    }
]
```

## 添加音乐评论

```
POST /music/{music}/comments
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| body | string | 评论内容 |
| reply_user | Integer | 被回复者 默认为0 |

#### Response

```
Status: 201 Created
```

```json5
{
    "message": [
        "操作成功"
    ],
    "comment": { // 评论信息
        "user_id": 1,
        "reply_user": 0,
        "target_user": 0,
        "body": "辣鸡啊啊啊啊",
        "commentable_type": "musics",
        "commentable_id": 1,
        "updated_at": "2017-07-24 09:12:03",
        "created_at": "2017-07-24 09:12:03",
        "id": 13
    }
}
```

## 添加专辑评论

```
POST /music/specials/{special}/comments
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| body | string | 评论内容 |
| reply_user | Integer | 被回复者 默认为0 |

#### Response

```
Status: 200 OK
```

```json5
{
    "message": [
        "操作成功"
    ],
    "comment": { // 评论信息
        "user_id": 1,
        "reply_user": 0,
        "target_user": 0,
        "body": "因吹斯听",
        "commentable_type": "music_specials",
        "commentable_id": 1,
        "updated_at": "2017-07-24 09:12:03",
        "created_at": "2017-07-24 09:12:03",
        "id": 13
    }
}
```

## 删除音乐评论

```
DELETE /music/{music}/comments/{comment}
```

#### Response

```
Status: 204 No Content
```

## 删除专辑评论

```
DELETE /music/specials/{special}/comments/{comment}
```

#### Response

```
Status: 204 No Conetent
```