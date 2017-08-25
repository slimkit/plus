# 意见反馈

- [发送意见反馈](#发送意见反馈)
- [获取系统会话列表](#获取系统会话列表)

## 发送意见反馈

```
POST /api/v2/user/feedback
```

### 输入
| 名称 | 类型 | 描述 |
|:----:|:----:|------|
| content | string | 反馈内容 |
| system_mark | int | 移动端标记，非必填 ，格式为uid+毫秒时间戳|

### 响应

```
Status 201 Created
```

```json5
{
    "message": [
        "反馈成功"
    ],
    "data": {
        "type": "feedback",
        "content": "baishi",
        "user_id": 1,
        "system_mark": 1503369076000,
        "updated_at": "2017-08-22 02:31:16",
        "created_at": "2017-08-22 02:31:16",
        "id": 2
    }
}
```

## 获取系统会话列表

```
GET /api/v2/conversations
```
## 请求方式

```
GET
```

## 请求体

| name     | must     | description |
|----------|:--------:|:--------:|
|max_id    | no       | 用于分页的数据id |
|limit     | no       | 每页显示条数 |

### HTTP Status Code

200

## 返回体

```
[
    {
        "id": 2,
        "type": "feedback",
        "user_id": 1,
        "to_user_id": 0,
        "content": "baishi",
        "options": null,
        "system_mark": 1503369076000,
        "created_at": "2017-08-22 02:31:16",
        "updated_at": "2017-08-22 02:31:16"
    },
    {
        "id": 1,
        "type": "feedback",
        "user_id": 1,
        "to_user_id": 0,
        "content": "baishi",
        "options": null,
        "system_mark": 1503368650000,
        "created_at": "2017-08-22 02:24:10",
        "updated_at": "2017-08-22 02:24:10"
    }
]
```

## 返回字段

|name       | type     | must     | description |
|-----------|:--------:|:--------:|:-----------:|
|id         | int        | yes      | 数据id      |
|type       | string     | yes      | 会话类型 system-系统通知  feedback-用户意见反馈 |
|user_id    | int        | yes      | 发送者id   系统通知时为0 |
|to_user_id | int        | yes      | 接收者id    系统广播通知及意见反馈时为0  |
|content    | Text       | yes      | 内容      |
|options    | Text       | yes      | 系统通知额外扩展参数      |
|system_mark | int     | yes      | 移动端存储标记 |
