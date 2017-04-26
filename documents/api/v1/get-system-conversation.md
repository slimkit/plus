# 获取系统会话列表

## 接口

```
/api/v1/system/conversations
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
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": [
    {
      "id": 1,
      "type": "system",
      "user_id": 1,
      "to_user_id": 0,
      "content": "123123",
      "options": null,
      "created_at": "2017-03-02 08:14:13",
      "updated_at": "2017-03-02 08:14:13",
      "system_mark": 212111111
    },
    {
      "id": 2,
      "type": "system",
      "user_id": 1,
      "to_user_id": 0,
      "content": "123123",
      "options": null,
      "created_at": "2017-03-02 08:14:13",
      "updated_at": "2017-03-02 08:14:13",
      "system_mark": 212111111
    }
  ]
}
```

## 返回字段

|name       | type     | must     | description |
|-----------|:--------:|:--------:|:-----------:|
|id         | int	     | yes		| 数据id      |
|type       | string	 | yes		| 会话类型 system-系统通知  feedback-用户意见反馈 |
|user_id    | int	     | yes		| 发送者id   系统通知时为0 |
|to_user_id | int	     | yes      | 接收者id    系统广播通知及意见反馈时为0  |
|content    | Text	     | yes		| 内容      |
|options    | Text	     | yes		| 系统通知额外扩展参数      |
|system_mark | int     | yes      | 移动端存储标记 |


