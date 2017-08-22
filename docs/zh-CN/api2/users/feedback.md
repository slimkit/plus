# 意见反馈

- [发送意见反馈](#发送意见反馈)

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