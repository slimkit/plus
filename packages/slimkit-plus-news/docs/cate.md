# 资讯分类

- [获取资讯分类](#获取资讯分类)
- [订阅分类](#订阅分类)

# 获取资讯分类

返回全部资讯分类和已订阅的分类，在未登录或未订阅状态下已订阅分类随机返回5个分类

```
GET /news/cates
```

### Response

Headers

```
Status: 200 OK
```

Body

```json5
{
  "my_cates": [
    {
      "id": 1,
      "name": "热门"
    },
    {
      "id": 2,
      "name": "娱乐"
    },
    {
      "id": 3,
      "name": "体育"
    },
    //...
  ],
  "more_cates": [
    {
      "id": 6,
      "name": "社会"
    },
    {
      "id": 7,
      "name": "股票"
    },
    {
      "id": 8,
      "name": "艺术"
    },
    //...
  ]
}
```
### prams
| 参数 | 说明 |
| :---: | :---: |
| my_cates | 我订阅的资讯分类 |
| more_cates | 其他未订阅的资讯分类 |

## 订阅分类

```
PATCH /news/categories/follows 
```

## 请求字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| follows  | string   | yes      | 订阅的资讯分类  多个以逗号隔开|

### HTTP Status Code

201 Created

## 返回体

```json5
{
    "message": [
        "订阅成功"
    ]
}
```