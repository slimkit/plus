# 订阅资讯频道

## 接口地址

/api/v1/news/cates/follow

## 请求方法

POST

## 请求字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| follows  | string   | yes      | 订阅的资讯分类  多个以逗号隔开|

### HTTP Status Code

201

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "订阅成功",
  "data": 1,
}
```