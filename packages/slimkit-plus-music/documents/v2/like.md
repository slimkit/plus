# 点赞

- [点赞](#点赞)
- [取消点赞](#取消点赞)

## 点赞

```
POST /music/{music}/like
```

### Reponse

```
Status 201 Created
```

```json5
{
    "message": [
        "点赞成功"
    ]
}
```

## 取消点赞

```
DELETE /music/{music}/like
```

### Reponse

```
Status 204 No Content
```