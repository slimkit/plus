# 文件上传

```
POST /files
```

## Input

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| file | File | 待上传的文件 |

#### Response

```
Status: 201 Created
```

```json
{
    "message": [
        "上传成功"
    ],
    "id": 1
}
```
