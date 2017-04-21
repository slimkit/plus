#查看扩展包安装状态

##接口地址

```
/api/v1/system/component/status
```

##请求方式

```
GET
```

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "操作成功",
  "data": {
    "im": true
  }
}
```

## 接口变量

| name     | must     | description |
|----------|:--------:|:--------:|
| im       | boolean  |  模块是否被安装 true 已安装 |

扩展模块拥有配置项时  将在这里扩展
目前有模块 im feed music news channel等

code请参见[消息对照表](消息对照表.md)