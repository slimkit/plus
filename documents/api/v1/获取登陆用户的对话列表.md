# 获取登陆用户的对话列表

##接口地址

`/api/v1/im/conversations/list/all`

##请求方法

`get`

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "操作成功",
  "data": [
    {
      "user_id": 13,
      "cid": 8,
      "name": "",
      "pwd": "",
      "type": 0,
	  "uids": "13,1002"
    }
  ]
}
```
## 特别说明

如果当前登陆用户没有聊天列表,返回的data为空数组

## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
|user_id			|int		|yes		|创建用户的uid|
|cid		|int		|yes		|会话id|
|name		|string	   | yes		 |会话名称|
|pwd		|string	   | yes		 |加入密钥，字符串，type=0时此项为空字符串|
|type  		| int      | yes      | 当前会话类型|
|uids		|string	   | yes		 |逗号分隔的聊天成员ID|

code请参见[消息对照表](消息对照表.md)
