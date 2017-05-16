# 获取用户信息

## 接口地址

`/api/v1/system/feedback`

## 请求方法

```POST ```

## 接口变量

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| content  | string   | yes      | 反馈内容    |
| system_mark | int   | yes      | 移动端存储标记 |

### HTTP Status Code

201

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "反馈成功",
  "data": 1
}
```

code请参见[消息对照表](消息对照表.md)
