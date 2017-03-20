# 移除对话成员限制

##接口地址

`/api/v1/im/conversations/members/limited/{cid}/{uid}`

##请求方法

`delete `

##特别说明:

地址中的cid为对话id,如果该对话id不存在会返回错误,uid为需要解除限制的用户uid

### HTTP Status Code

404

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "操作成功",
  "data": {
    "uid": 1001,
    "cid": 13
  }
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
|cid		|int		|yes		|对话id|
|uid		|int	   | yes		 |本次解除限制的成员uid标识|


code请参见[消息对照表](消息对照表.md)
