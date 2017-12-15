# 资讯分类列表

## 接口地址

/api/v1/news/cates

## 请求方法

GET

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": {
    "my_cates": [],
    "more_cates": [
      {
        "id": 1,
        "name": "分类1"
      },
      {
        "id": 2,
        "name": "分类2"
      }
    ]
  }
}
```

## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | string   | yes      | 分类id |
| name     | string	  | yes		   | 分类名称 |
| my_cates | array    | yes      | 我的订阅分类列表 |
| more_cates | array  | yes      | 其他分类列表 |
