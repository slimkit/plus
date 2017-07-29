# 用户标签

- [获取一个用户的标签](#获取一个用户的标签)
- [获取当前认证用户的标签](#获取当前认证用户的标签)
- [当前认证用户附加一个标签](#当前认证用户附加一个标签)
- [当前认证用户分离一个标签](#当前认证用户分离一个标签)

## 获取一个用户的标签

```
GET /users/:user/tags
```

## 获取当前认证用户的标签

```
GET /user/tags
```

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "name": "标签1",
        "tag_category_id": 1
    }
]
```

| 字段 | 描述 |
|:----:|----|
| id | 标签 ID。 |
| name | 标签名称 |
| tag_category_id | 标签分类 ID |

## 当前认证用户附加一个标签

```
PUT /user/tags/:tag
```

#### 响应

```
Status: 204 No Content
```

## 当前认证用户分离一个标签

```
DELETE /user/tags/:tag
```

#### 响应

```
Status: 204 No Content
```
