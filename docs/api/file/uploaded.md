# 文件上传前检查

在上传一个文件前对文件进行检查，并创建一个 File with,如果文件存在，当文件存在，则创建一个 with id，不存在抛出 `404`。

```
GET /files/uploaded/:hash
```

##### Response

```
Status: 200 OK
```
```json
{
    "message": "获取成功",
    "id": 1
}
```