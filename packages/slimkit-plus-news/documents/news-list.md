# 资讯列表

## 接口地址

/api/v1/news

## 请求方法

GET

## 请求字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| cate_id  | string   | yes      | 订阅分类  -1 推荐  -2 热门  其他对应资讯分类id  |
| max_id   | int      | no       | 用来翻页的记录id(对应数据体里的id) |
| limit    | int      | no       | 每页返回数据条数 默认15条 |
| key      | string   | no       | 搜索关键字 |

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": {
    "list": [
      {
        "id": 1,
        "title": "啦啦啦啦",
        "updated_at": "2017-03-20 11:51:23",
        "storage": {
          "id": 1,
          "size": "1200x2400",
        },
        "from": "啦啦",
        "is_collection_news": 0
      }
    ],
    "recommend": []
  }
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | int      | yes      | 资讯id  |
| title    | int      | yes      | 资讯标题 |
| updated_at | int    | yes      | 资讯创建时间 |
| is_collection_news | int |yes  | 是否已收藏该资讯 0-未收藏 1-已收藏 |
| storage  | array    | yes      | 封面附件信息|
| list     | array    | yes      | 资讯列表 |
| recomend | array    | yes      | 列表置顶推荐 |
| type     | string   | yes      | 推荐类型 false-仅展示 news-资讯 url-跳转链接 |
| data     | string   | yes      | 对应关联类型  |