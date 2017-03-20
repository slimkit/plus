# 创建会话

##接口地址

`/api/v1/im/conversations`

##请求方法

`post `

##请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| type  | int      | yes      | 会话类型 `0` 私有会话 `1` 群组会话 `2`聊天室会话 |
| name  | string   | no		  | 会话名称|
| pwd	| string   | no		  | 会话加入密码,type=`0`时该参数无效|
|uids	| array,string|no	  | 会话初始成员，数组集合或字符串列表``"1,2,3,4"` type=`0`时需要两个uid、type=`1`时需要至少一个、type=`2`时此参数将忽略;注意：如果不合法的uid或uid未注册到IM,将直接忽略|

##特别说明

所有发起创建会话的用户默认会加入到会话中,即uids参数中不需要定义发起用户的uid

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "操作成功",
  "data": {
    "user_id": 13,
    "cid": 8,
    "name": "",
    "pwd": "",
    "type": 0,
    "uids": "13,1002"
  }
}
```
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
