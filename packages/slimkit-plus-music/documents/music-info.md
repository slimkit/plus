# 歌曲详情

## 接口地址

/api/v1/music/{music_id}

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
    "created_at": "2017-03-16 17:11:26",
    "updated_at": "2017-03-16 17:11:29",
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
    "lyric": "lalaallalalaallalalaal",
    "taste_count": 0,
    "share_count": 0,
    "comment_count": 0,
    "isdiggmusic": 0
  }
}
```
## 返回字段

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| id       | int      | yes      | 专辑id  |
| title    | int      | yes      | 专辑标题 |
| updated_at | int    | yes      | 资讯创建时间 |
| storage  | int      | yes      | 封面附件id|
| taste_count | int   | yes      | 播放数 |
| share_count | int   | yes      | 分享数 | 
| comment_count | int | yes      | 评论数 |
| isdiggmusic | int   | yes      | 是否已赞歌曲 0-未赞 1-已赞 |
| singer   | array    | yes      | 歌手信息 |
| name     | string   | yes      | 歌手名称 |
| cover    | int      | yes      | 歌手封面 |
| lyric    | string   | yes      | 歌词   |     