# 储存任务通知

## 接口

```
/api/v1/storages/task/{storage_task_id}
```

## 请求方式

```
PATCH
```

## 接口变量

| name     | must     | description |
|----------|:--------:|:--------:|
| storage_task_id | yes | 任务ID |

## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| message  | string   | yes      | 附加上传接口返回的原样数据 |

### HTTP Status Code

201

## 返回体

无

## 返回说明

返回将以通用message格式返回上传的附件是成功还是失败等状态信息。