#获取用户关注状态

##接口地址

```
/api/v1/users/followstatus
```

##请求方式

```
GET
```
##请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:-----------:|
| user_ids | string   | yes      | 以逗号隔开  |

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
      "user_id": "2",
      "follow_status": 1,
      "my_follow_status": 0
    }
  ]
}
```

## 返回变量

| name              | must     | description |
|-------------------|:--------:|:-----------:|
| user_id           | yes      | 目标用户的user_id |
| follow_status     | yes      | 对方关注状态  0-对方未关注当前用户 1-对方已关注当前用户|
| my_follow_status  | yes      | 当前用户关注状态  0-当前用户未关注对方 1-当前用户已关注对方|
code请参见[消息对照表](消息对照表.md)