# 启动信息

启动信息是在调用接口前的需求，可以在应用启动的时候一次性获取全部通用配置信息。

## 列出所有启动者配置

```
GET /bootstrappers
```

### Response

Headers

```
Status: 200 OK
```

Bdoy

```json5
{
    "im:serve": "127.0.0.1:9900"
    // ...
}
```
