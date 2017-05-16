# 添加对话成员限制

##接口地址

`/api/v1/im/conversations/members/limited/{cid}`

##请求方法

`post `

##特别说明:

地址中的cid为对话id,如果该对话id不存在会返回错误

##请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
|uids	| array,string|yes	  | 限制的成员uid标识，数组集合或字符串列表如``"1,2,3,4"`|

### HTTP Status Code

404

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "操作成功",
  "data": {
    "expire": 0,
    "cid": 13
  }
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
|cid		|int		|yes		|对话id|
|uids		|array	   | yes		 |本次限制的成员uid标识|
|expire		|int		|yes		| 限制的时长,0为永久  其他为过期时间戳|


code请参见[消息对照表](消息对照表.md)
