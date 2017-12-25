# 音乐

- [获取音乐详情](#获取音乐详情)
- [获取已购买的音乐](#获取已购买的音乐)
- [增加音乐分享数](#增加音乐分享数)

## 获取音乐详情

```
GET /music/{music}
```

#### Response

```
Status: 201 OK
```
```json5
{
    "id": 1, // 音乐id
    "created_at": "2017-03-16 17:11:26",
    "updated_at": "2017-07-20 03:39:00",
    "deleted_at": null,
    "title": "水手公园", // 音乐标题
    "singer": { // 歌手信息
        "id": 1, // 歌手id
        "created_at": "2017-03-16 17:22:04",
        "updated_at": "2017-03-16 17:22:08",
        "name": "群星", // 歌手名称
        "cover": { // 歌手图片
            "id": 108, // 图片id
            "size": "3024x3024" // 图片尺寸
        }
    },
    "storage": { // 音乐附件信息
        "id": 105, // 附件id
        "amount": 100, // 付费金额 音乐免费时该字段不存在
        "type": "download", // 付费类型  音乐免费时该字段不存在
        "paid": true, // 是否已付费 音乐免费时 该字段不存在
        "paid_node": 2 // 付费节点  音乐免费时 该字段不存在
    },
    "last_time": 180, // 歌曲时间(app暂时自行下载解析时间)
    "lyric": "lalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallal", // 歌词
    "taste_count": 314, // 播放数
    "share_count": 0, // 分享数
    "comment_count": 12, // 评论数
    "has_like": true // 是否已赞
}
```

## 获取已购买的音乐

```
GET /music/paids
```

### 输入参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 20 ，获取条数 |
| max_id | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

### 响应

```
Status 200 Ok
```

```json5
[ // 音乐数据参见音乐详情
    {
        "id": 7,
        "created_at": "2017-04-17 15:27:59",
        "updated_at": "2017-07-06 03:53:04",
        "deleted_at": null,
        "title": "umbrella",
        "singer": {
            "id": 2,
            "created_at": "2017-03-16 17:22:18",
            "updated_at": "2017-03-16 17:22:20",
            "name": "佚名",
            "cover": {
                "id": 1,
                "size": "370x370"
            }
        },
        "storage": {
            "id": 112
        },
        "last_time": 300,
        "lyric": null,
        "taste_count": 0,
        "share_count": 0,
        "comment_count": 0,
        "has_like": true
    },
    {
        "id": 3,
        "created_at": "2017-03-16 17:21:09",
        "updated_at": "2017-07-06 08:01:18",
        "deleted_at": null,
        "title": "别碰我的人",
        "singer": {
            "id": 1,
            "created_at": "2017-03-16 17:22:04",
            "updated_at": "2017-03-16 17:22:08",
            "name": "群星",
            "cover": {
                "id": 1,
                "size": "370x370"
            }
        },
        "storage": { // 音乐付费时
            "id": 133,
            "amount": "200",
            "type": "download",
            "paid": "false",
            "paid_node": "12" 
        },
        "last_time": 200,
        "lyric": null,
        "taste_count": 297,
        "share_count": 0,
        "comment_count": 23,
        "has_like": true
    }
]
```

## 增加音乐分享数

供移动端分享音乐时调用，统计音乐分享数

```
PATCH /music/{music}/share
```

#### Response

```
Status: 204 No Content
```