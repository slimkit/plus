# 获取关注列表

##接口地址

```
/api/v1/follows/follows/{user_id}/{max_id?}
```

##请求方式

```
GET
```

## 请求参数

| name     | must     | description |
|----------|:--------:|:--------:|
|limit     | no       | 每页显示条数 |

### HTTP Status Code

200

## 返回体

```
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": {
    "follows": [
      {
        "id": 6,
        "user_id": 2,
        "my_follow_status": 1,
        "follow_status": 0
      }
    ]
  }
}
```

## 接口变量

| name     | must     | description |
|----------|:--------:|:--------:|
| user_id  | yes      | 目标用户的user_id |
| my_follow_status  | yes      | 当前用户的关注状态  1-已关注 0-未关注 |
| follow_status  | yes      | 对方用户的关注状态 1-已关注 0-未关注 |
| max_id   | no       | 分页查询，上一页的最大id |
code请参见[消息对照表](消息对照表.md)
