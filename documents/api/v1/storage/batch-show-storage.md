# 批量获取资源

> 用于需要一次性拿到所有真实情况资源数据，图片资源可以设置批处理转换。

## 接口地址

```
/api/v1/storages
```

## 请求方式

```
GET
```

## 请求参数

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | int,string,array | yes | 需要被获取的 id ,可以是单个值，也可以是一个数组，如果是一个字符串形态且是多个，也可以用 **,** 符号对附件 id 进行分割。 |
| process  | int,string,array | no  | 该参数只对 image 类型有资源生效，其他资源则自动跳过，用法和上述 “id” 描述一致 |

> process 的使用，如果传递多个资源 id 获取需要用到转换参数，转换参数的值对应和 “id” 的下标一致。

## 参数各个形态

> 拟定现在请求资源 id 为 「1」、「2」、「3」三个资源

### id

- int: 只能请求单个资源。如 `api/v1/storages?id=1`
- string: 可以请求多个值，用「,」分割。如 `api/v1/storages/id=1,2,3`
- array: 可以请求多个值，此部分来源于 queryString 规范，如 `api/v1/storages?id[]=1&id[]=2&id[]=3`

### process

process 的用法和上述 「id」的用法一致

> id 和 process 的三种形态用法，可以任意交叉混用，无任何统一限制，但是多个资源的情况下推荐使用 **string** 形态，该形态可以发送最小的数据到服务端。

## 请求实例

> 演示真实情况下的请求

## 请求地址

```
https://plus.io/api/v1/storages?id=1,2,3&process=40,,60
```

> 上述 process 第二个值留空表示 id 下标 第二个值 不转换或者不是一个 image 资源，即使设置了耶无所谓，接口会自动忽略转换值。

## 实例返回

```json
{
    "status":true,
    "code":0,
    "message":"操作成功",
    "data":{
        "1":"http://plus.io/storage/2017/04/17/0921/45bf7f199c8e9779f1e32d77d26bb82c.jpg",
        "2":"http://plus.io/storage/2017/04/17/0952/64836e6aae4b984de77b3212c4219513.jpg"
    }
}
```

> 其中 data 的下标就是请求资源的资源 id 。
