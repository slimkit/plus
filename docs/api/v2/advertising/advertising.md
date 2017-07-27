# 查询广告列表

查询某个广告位的广告列表

```
GET /advertisingspace/{space}/advertising
```

##### Response

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