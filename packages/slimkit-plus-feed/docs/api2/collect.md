# 收藏

- [收藏](#收藏)
- [取消收藏](#取消收藏)
- [收藏列表](#收藏列表)

## 收藏

```
POST /feeds/:feed/collections
```

#### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "收藏成功"
    ]
}
```

## 取消收藏

```
DELETE /feeds/:feed/uncollect
```

#### Response

```
Status: 204 No Centent
```

## 收藏列表

```
GET /feeds/collections
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 20 ，获取条数 |
| offset | Integer | 可选，偏移量，用于翻页，传入已请求过的数据总数 |
| user | Integer | type = `users` 时可选，默认值为当前用户id |

> 列表为倒序

#### Response

```
Status: 200 OK
```
```json5
[
	{...}  // 数据参考动态单条内容
]
```

> `feed_content` 字段在列表中，如果是收费动态则只返回 100 个文字。