# 广告

- [获取所有广告位](#查询所有广告位)
- [获取一个广告位的广告列表](#获取一个广告位的广告列表)
- [批量获取广告列表](#批量获取广告列表)

## 查询所有广告位

```
GET /advertisingspace
```

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "channel": "boot", // 广告位所属模块
        "space": "boot", // 广告位标识
        "alias": "启动图广告", // 广告位别名
        "allow_type": "image", // 广告位允许的广告类型 (当前app中只有图片类型) 多个类型将以逗号隔开
        "format": { // 广告数据格式
            "image": {
                "image": "图片|string",
                "link": "链接|string"
            }
        },
        "created_at": "2017-07-27 06:56:36",
        "updated_at": "2017-07-27 06:56:36"
    },
    {
        "id": 2,
        "channel": "feed",
        "space": "feed:list:top",
        "alias": "动态列表顶部广告",
        "allow_type": "image",
        "format": {
            "image": {
                "image": "图片|string",
                "link": "链接|string"
            }
        },
        "created_at": "2017-07-27 07:04:50",
        "updated_at": "2017-07-27 07:04:50"
    },
    {
        "id": 3,
        "channel": "feed",
        "space": "feed:single",
        "alias": "动态详情广告",
        "allow_type": "image",
        "format": {
            "image": {
                "image": "图片|string",
                "link": "链接|string"
            }
        },
        "created_at": "2017-07-27 07:04:50",
        "updated_at": "2017-07-27 07:04:50"
    },
    {
        "id": 4,
        "channel": "feed",
        "space": "feed:list:analog",
        "alias": "动态列表模拟数据广告",
        "allow_type": "analog",
        "format": {
            "analog": {
                "avatar": "头像图|string",
                "name": "用户名|string",
                "content": "内容|string",
                "image": "图片|string",
                "time": "时间|date"
            }
        },
        "created_at": "2017-07-31 03:18:02",
        "updated_at": "2017-07-31 03:18:02"
    }
]
```

## 获取一个广告位的广告列表

```
GET /advertisingspace/:space/advertising
```

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "space_id": 1, // 广告位id
        "title": "广告1", // 广告标题
        "type": "image", // 广告类型
        "data": { // 广告数据
            "image": "http://plus.bai/api/v2/files/1", // 广告图片地址
            "link": "http://www.baidu.com" // 广告链接
        },
        "sort": 2,
        "created_at": "2017-07-27 15:09:15",
        "updated_at": "2017-07-27 15:09:16"
    },
    {
        "id": 2,
        "space_id": 1,
        "title": "广告2",
        "type": "image",
        "data": {
            "image": "http://plus.bai/api/v2/files/1",
            "link": "http://www.baidu.com"
        },
        "sort": 3,
        "created_at": "2017-07-27 15:09:15",
        "updated_at": "2017-07-27 15:09:16"
    }
]
```

| 名称 | 类型 | 描述 |
|:----:|:----:|------|
| id   | int  | 数据id |
| space_id | int | 所属广告位id |
| title | string | 广告位标题 |
| data | array | 广告数据 参见对应广告位数据格式 |
| sort | int   | 广告顺序 |


## 批量获取广告列表

```
GET /advertisingspace/advertising
```


#### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| space | 字符串 | 广告位id，多个以逗号隔开 |

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "space_id": 1, // 广告位id
        "title": "广告1", // 广告标题
        "type": "image", // 广告类型
        "data": { // 广告数据
            "image": "http://plus.bai/api/v2/files/1", // 广告图片地址
            "link": "http://www.baidu.com" // 广告链接
        },
        "sort": 2,
        "created_at": "2017-07-27 15:09:15",
        "updated_at": "2017-07-27 15:09:16"
    },
    {
        "id": 2,
        "space_id": 2,
        "title": "广告2",
        "type": "image",
        "data": {
            "image": "http://plus.bai/api/v2/files/1",
            "link": "http://www.baidu.com"
        },
        "sort": 3,
        "created_at": "2017-07-27 15:09:15",
        "updated_at": "2017-07-27 15:09:16"
    }
]
```