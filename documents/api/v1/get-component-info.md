# 获取扩展包配置信息

## 接口地址

```
/api/v1/system/component/configs
```

## 请求方式

```
GET
```

## 接口变量

| name      | must     | description |
|----------:|:--------:|:--------:|
| component | yes      | 扩展包名   |

目前component 可选参数有 im

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
      "name": "serverurl",
      "value": "192.168.2.222:9900"
    }
  ]
}
```

## 接口变量

| name     | must     | description |
|----------|:--------:|:--------:|
| name     | string   |  配置名 |
| value    | string   | 配置信息 |


code请参见[消息对照表](消息对照表.md)
