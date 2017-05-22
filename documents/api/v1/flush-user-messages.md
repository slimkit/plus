# 获取用户收到的最新消息

## 接口地址

```
/api/v1/users/flushmessages
```

## 请求方式

```
GET
```
## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:-----------:|
| time     | int      | no      | 零时区的秒级时间戳 不传为获取5条历史记录|
| key      | string   | no       | 查询关键字 默认查询全部  多个以逗号隔开  可选参数有 diggs comments follows notices|

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
      "key": "diggs",
      "uids": [1],
      "count": 1,
      "time": "2017-04-13 01:45:37",
      "max_id": 1
    },
    {
      "key": "follows",
      "uids": [21,19],
      "count": 2,
      "time": "2017-04-12 07:09:33",
      "max_id": 2
    },
    {
      "key": "comments",
      "uids": [],
      "count": 0,
      "time": "2017-04-13 08:06:48",
      "max_id": 0
    },
    {
      "key": "notices",
      "uids": [],
      "count": 0,
      "time": "2017-04-13 08:06:48",
      "max_id": 0
    }
  ]
}
```

## 返回变量

| name              | must     | description |
|-------------------|:--------:|:-----------:|
| key               | yes      | 消息关键字  |
| uids              | yes      | 操作者id 多个以逗号隔开 第一条为最新消息的操作者  |
| count             | yes      | 新消息数量  |
| time              | yes      | 最后一条数据的时间 无数据时为当前时间 |
| max_id            | yes      | 最后一条数据的id |

## 由于请求接口数据时间是以秒级时间戳  建议调用传入时间间隔1秒以上 以防止数据重复

code请参见[消息对照表](消息对照表.md)