# 获取聊天服务器配置信息

## 接口地址

`/api/v1/system/imserverconfig`

## 请求方法

```GET ```

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": {
    "url": "192.168.2.222",
    "port": "9900"
    //后续聊天服务器地址也会在此返回
  }
}
```

code请参见[消息对照表](消息对照表.md)
