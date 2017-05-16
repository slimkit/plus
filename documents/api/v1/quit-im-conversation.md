# 退出聊天对话

##接口地址

`/api/v1/im/conversations/members/{cid}`

##请求方法

`DELETE `

##特别说明:

地址中的cid为对话id,如果该对话id不存在会返回错误

### HTTP Status Code

404

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "操作成功",
  "data": {
    "cid": 12
  }
}
```

## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| cid  | int      | yes      | 当前退出的聊天对话ID |


code请参见[消息对照表](消息对照表.md)
