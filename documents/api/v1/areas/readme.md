# 全部地区获取接口

该接口获取全部地区数据，支持简单的数据检索。

## 请求接口

```
/api/v1/areas
```

## 请求方式

```
GET
```

## 可全请求参数

> query string.

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | number, array, string | no | 以id为条件检索，可以传递数组或者string将id使用 **,** 符号分割. |
| pid      | number, array, string | no | 以pid为条件检索，可以专递数组，或者string将pid使用 **,** 符号分割  |

## 成功返回状态

- 200: 获取成功

## 成功返回体

```json5
{
    "status":true,
    "code":0,
    "message":"操作成功",
    "data":[
        {
            "id":1, // 主键ID
            "name":"中国", // 地区显示名称
            "pid":0, // 父地区主键ID
            "extends":"3", // 可选拓展字段, 具体用处依据需求和项目开发人员沟通使用.
            "created_at":"2017-04-01 06:38:48", // 创建时间
            "updated_at":"2017-04-01 06:38:48" // 更新时间
        },
        {
            "id":3517,
            "name":"hehe",
            "pid":0,
            "extends":"2",
            "created_at":"2017-04-03 01:58:39",
            "updated_at":"2017-04-03 02:03:16"
        }
        // ...
        // more.
    ]
}
```
