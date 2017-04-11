# 用户点赞排行

##接口地址

`/api/v1/diggsrank`

##请求方法

`GET`

##请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| page     | int      | no       | 页码 默认为1 |
| limit    | int      | no       | 返回数据条数 默认15条 |

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": [
    {
      "id": 6,
      "user_id": 2,
      "value": "3"
    },
    {
      "id": 5,
      "user_id": 1,
      "value": "1"
    }
  ]
}
```

## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| user_id  | int      | yes      | 用户ID |
| value    | string	  | yes		 | 点赞数 |


code请参见[消息对照表](消息对照表.md)
