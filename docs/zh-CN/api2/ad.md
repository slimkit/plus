# 广告

- [获取所有广告位](#查询所有广告位)
- [获取一个广告位的广告列表](#获取一个广告位的广告列表)

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
        "allow_type": "image", // 广告位允许的广告类型 (当前app中只有图片类型)
        "created_at": "2017-07-27 06:56:36",
        "updated_at": "2017-07-27 06:56:36"
    },
    {
        "id": 2,
        "channel": "feed",
        "space": "feed:list:top",
        "alias": "动态列表顶部广告",
        "allow_type": "image",
        "created_at": "2017-07-27 07:04:50",
        "updated_at": "2017-07-27 07:04:50"
    },
    {
        "id": 3,
        "channel": "feed",
        "space": "feed:single",
        "alias": "动态详情广告",
        "allow_type": "image",
        "created_at": "2017-07-27 07:04:50",
        "updated_at": "2017-07-27 07:04:50"
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
        "created_at": "2017-07-27 15:09:15",
        "updated_at": "2017-07-27 15:09:16"
    }
]
```