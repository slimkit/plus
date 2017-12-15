# 搜索资讯

## 接口地址

/api/v1/news/search

## 请求方法

GET

## 请求字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| max_id   | int      | no       | 用来翻页的记录id(对应数据体里的id) |
| limit    | int      | no       | 每页返回数据条数 默认15条 |
| key      | string   | no       | 搜索关键字 |
| news_ids | mix      | no       | 以逗号隔开或数组形式的资讯id |

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
        "title": "123123",
        "updated_at": "2017-03-13 09:59:32",
        "storage": {
          "id": 1,
          "size": "100x200"
        },
        "from": null
      }
    ],
    "recommend": [
      {
        "id": 1,
        "created_at": "2017-03-16 11:31:52",
        "updated_at": "2017-03-16 11:31:52",
        "cate_id": 2,
        "type": "false",
        'data': null,
        "cover": {
          "id": 1,
          "size": "100x200"
        },
        "sort": 0
      }
    ]
  }
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | int      | yes      | 资讯id  |
| title    | int      | yes      | 资讯标题 |
| updated_at | int    | yes      | 资讯创建时间 |
| storage  | array    | yes      | 封面附件信息|
| list     | array    | yes      | 资讯列表 |
| recomend | array    | yes      | 列表置顶推荐 |
| type     | string   | yes      | 推荐类型 false-仅展示 news-资讯 url-跳转链接 |
| data     | string   | yes      | 对应关联类型  |