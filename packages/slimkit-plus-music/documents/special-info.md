# 专辑详情

## 接口地址

/api/v1/music/specials/{special_id}

## 请求方法

GET

### HTTP Status Code

200

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": {
    "id": 1,
    "created_at": "2017-03-15 17:01:17",
    "updated_at": "2017-03-21 02:29:48",
    "title": "专辑1",
    "intro": "这里是简介",
    "storage": 2,
    "taste_count": 4,
    "share_count": 0,
    "comment_count": 0,
    "collect_count": 0,
    "is_collection", 0,
    "musics": [
      {
        "id": 1,
        "created_at": "2017-03-16 17:22:39",
        "updated_at": "2017-03-16 17:22:42",
        "special_id": 1,
        "music_id": 1,
        "music_info": {
          "id": 1,
          "created_at": "2017-03-16 17:11:26",
          "updated_at": "2017-03-21 02:29:48",
          "deleted_at": null,
          "title": "水手公园",
          "singer": {
            "id": 1,
            "created_at": "2017-03-16 17:22:04",
            "updated_at": "2017-03-16 17:22:08",
            "name": "汤圆毛",
            "cover": {
              "id": 2,
              "size": '3264x2448'
            }
          },
          "storage": 129,
          "last_time": 180,
          "lyric": "lalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallal",
          "taste_count": 4,
          "share_count": 0,
          "comment_count": 0,
          "isdiggmusic": 1
        }
      },
      {
        "id": 2,
        "created_at": "2017-03-16 17:22:48",
        "updated_at": "2017-03-16 17:22:50",
        "special_id": 1,
        "music_id": 2,
        "music_info": {
          "id": 2,
          "created_at": "2017-03-16 17:20:40",
          "updated_at": "2017-03-16 17:20:43",
          "deleted_at": null,
          "title": "thankyou",
          "singer": {
            "id": 2,
            "created_at": "2017-03-16 17:22:18",
            "updated_at": "2017-03-16 17:22:20",
            "name": "刘zz",
            "cover": {
              "id": 54,
              "size": '690x932'
            }
          },
          "storage": 130,
          "last_time": 240,
          "lyric": "sdafasfasdfasdfasdfasdfsadf",
          "taste_count": 0,
          "share_count": 0,
          "comment_count": 0,
          "isdiggmusic": 0
        }
      }
    ]
  }
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | int      | yes      | 专辑id  |
| title    | int      | yes      | 专辑标题 |
| intro    | string   | yes      | 专辑简介 |
| updated_at | int    | yes      | 资讯创建时间 |
| storage  | int      | yes      | 封面附件id|
| taste_count | int   | yes      | 播放数 |
| share_count | int   | yes      | 分享数 | 
| comment_count | int | yes      | 评论数 |
| is_collection | int | yes      | 是否已收藏该专辑 0-未收藏 |
| isdiggmusic | int   | yes      | 是否已赞该歌曲 0-未赞 1-已赞 |
| singer   | array    | yes      | 歌手信息 |
| lyric    | string   | yes      | 歌词   |     