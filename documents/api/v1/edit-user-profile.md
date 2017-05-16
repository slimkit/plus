# 修改用户资料

## 接口地址

`/api/v1/users`

## 请求方法

`PATCH`

## 请求体

| name      | type     | must     | description |
|-----------|:--------:|:--------:|:--------:|
| sex       | string   | no       | 1-男 2-女 3-其他|
| location  | string   | no       | 地区     |
| intro     | string   | no       | 简介     |
| province  | string   | no       | 省       |
| city      | string   | no       | 市       |
| area      | string   | no       | 区       |
| education | string   | no       | 学历     |
| name      | string   | no       | 昵称     |
| storage_task_id | string| no    | 头像     |
| cover_storage_task_id | string | no | 用户个人主页背景图  |

###说明

已上为传递说明
请求内容没有固定的内容，提交自为做过更改的字段，字段来源为 配置接口中用户配置字段

### HTTP Status Code

201

## 返回体
```json5
{
  "status": true,
  "code": 0,
  "message": "操作成功",
  "data": null
}
```

code请参见[消息对照表](消息对照表.md)
