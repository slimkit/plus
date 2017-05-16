# 储存任务创建

## 接口地址

```
/api/v1/storages/task
```

## 请求方式

```
POST
```

## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| hash     | string   | yes      | 待上传文件hash值，hash方式md5 |
| origin_filename | string | yes | 原始文件名称 |
| mime_type| string   | yes      | 文件mimeType |
| width    | float    | no       | 图片宽度,小数位2 |
| height   | float    | no       | 图片高度,小数位2 |

### HTTP Status Code

201

## 返回体
情况1:
```json5
{
    "storage_id": 7,
    "storage_task_id": 3
}
```
data字段直接返回`storage_id`储存唯一标识字段，表示跳过上传步骤和通知步骤，直接上传成功。

情况2:

```json5
{
    "uri": "http://plus.io/api/v1/storages/1",
    "method": "PUT",
    "storage_task_id": 1,
    "input": "file",
    "headers": {
      "Authorization": "fb0581e7a50d8a6fd19bed5b7f299b32"
    },
    "options": []
}
```

字段解析：

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| uri      | string   | yes      | 上传附件的地址 |
| method   | string   | yes      | 请求附件上传的方式 |
| storage_task_id | int | yes    | 任务ID |
| input    | string   | yes      | 上传资源的表单名称 |
| headers  | object   | yes      | 请求头 为空时为空数组|
| options  | object   | yes      | 请求体 为空时为空数组|
