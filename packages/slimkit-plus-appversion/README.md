# ThinkSNS+ 用户客户端版本更新管理拓展

## 安装拓展

首先，你应该先安装好 [ThinkSNS+](https://github.com/slimkit/thinksns-plus) 系统，然后通过在命令行进入 [ThinkSNS+](https://github.com/slimkit/thinksns-plus) 系统的根目录，然后使用 `composer` 执行：

```shell
composer require slimkit/plus-appversion
```

使用 `artisan` 运行数据表迁移：

```shell
php artisan package:handle plus-appversion migrate
```


## 接口列表。

- [获取客户端更新列表](#获取客户端更新列表)


### 获取客户端更新列表

```
GET /api/v2/plus-appversion
```

### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|------|
| version_code | int | 查询标识，传入当前客户端的版本号 |
| type | string | 客户端版本类型，暂定参数有 `android`、`ios` |
| limit | int | 返回数据条数，默认15条 |

### 响应

```json5
[
    {
        "id": 1,
        "type": "android",
        "version": "v1.0.0",
        "version_code": 88,
        "description": "### 安卓初始版本上线",
        "link": "http://127.0..0.1/ad.apk",
        "is_forced": 0,
        "created_at": "2017-09-12 17:29:33",
        "updated_at": "2017-09-12 17:29:34"
    }
]
```

### 返回参数

| 名称 | 类型 | 描述  |
|:----:|:----:|-------|
| id   | int  | 更新记录自增id，可以此判断版本顺序 |
| type | string | 客户端的版本类型 |
| version | string | 后台填写的版本 |
| version_code | int | 版本号，客户端对比该数字进行版本更新 |
| description | string | ***markdown*** 格式的更新说明 |
| link | string | 更新包链接 ，`ios` 版本可能为AppStore的跳转链接 |
| is_forced | int | 是否强制更新，0-非强制更新 1-强制更新 |
| created_at | date | 版本更新记录创建时间 |