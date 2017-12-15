# 收藏

- [收藏资讯](#收藏资讯)
- [取消收藏资讯](#取消收藏资讯)
- [获取收藏资讯](#获取收藏资讯)

## 收藏资讯

```
POST /news/{news}/collections
```
### Response

Headers

```
Status: 201 Created
```

## 取消收藏资讯

```
DELETE /news/{news}/collections
```

### Response

Headers

```
Status: 204 No Content
```

## 获取收藏资讯

```
GET /news/collections
```

### 可选参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 数据返回条数 |
| after | int | -    | 数据翻页标识 |

### Response

Headers

```
Status: 200 Ok
```

```json5
[
    {
        "id": 2,
        "title": "asdasdasdad",
        "subject": "阿拉拉啊拉",
        "created_at": "2017-08-02 03:28:38",
        "updated_at": "2017-08-03 07:53:34",
        "from": "原创",
        "author": "root",
        "user_id": 1,
        "hits": 5,
        "has_collect": true,
        "has_like": false,
        "category": {
            "id": 1,
            "name": "1121",
            "rank": 0
        },
        "image": {
            "id": 2,
            "size": "370x370"
        }
    }
]
```

### 返回参数

| 名称 | 类型 | 说明 |
|:----:|:----:|------|
| id   | int  | 数据id |
| title | string | 资讯标题 |
| subject | string | 副标题 |
| from | string | 来源 |
| author | string | 作者 |
| user_id | int | 发布者id |
| hits | int  | 点击数 |
| has_collect | bool | 当前用户是否已收藏 |
| has_like | bool | 当前用户是否已点赞 |
| category | array | 所属分类信息 |
| category.id | int | 所属分类id |
| category.name | string | 所属分类名称 |
| category.rank | int | 所属分类排序 |
| image | array | 资讯封面信息 |
| image.id | int | 资讯封面附件id |
| image.size | string | 资讯封面尺寸 |

