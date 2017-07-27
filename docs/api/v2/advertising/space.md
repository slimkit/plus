# 查询广告位

查询当前系统中所有已存在的广告位

```
GET /advertisingspace
```

##### Response

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