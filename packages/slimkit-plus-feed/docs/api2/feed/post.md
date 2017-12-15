# 动态发布

```
POST /feeds
```

### Input

| Name | Type | Description |
|:----:|:----:|----|
| feed_content | string | 分享内容。**如果存在附件，则为可选，否则必须存在** |
| feed_from | integer | 客户端标识，1-PC、2-Wap、3-iOS、4-android、5-其他 |
| feed_mark | mixed | 客户端请求唯一标识 |
| feed_latitude | string | 纬度，**当经度， GeoHash 任意一个存在，则本字段必须存在** |
| feed_longtitude | string | 经度，**当纬度， GeoHash 任意一个存在，则本字段必须存在** |
| feed_geohash | string | GeoHash，**当纬度、经度 任意一个存在，则本字段必须存在** |
| amount | inteter | 动态收费，**不存在表示不收费，存在表示收费。**|
| images | array | 结构：`{ id: <id>, amount: <amount>, type: <read\|download> }`，**amount 为可选，id 必须存在，amount 为收费金额，单位分, type 为收费方式** |


### Example
```json5
{
    "feed_content": "内容",
    "feed_from": "5",
    "feed_mark": "xxxxx1",
    "images": [
        {
            "id": 1
        },
        {
            "id": 1
            "amount": 100,
            "type": "read"
        }
    ],
    "feed_latitude": "12.32132123",
    "feed_longtitude": "32.33332123",
    "feed_geohash": "GdUDHyfghjd==",
    "amount": 450
}
```

### Response

```
Status: 201 Created
```
```json5
{
    "message": [
        "发布成功"
    ],
    "id": 1
}
```
