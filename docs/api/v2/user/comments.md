# List all comments of the authenticated user

```
GET /user/comments
```

#### Parameters

| Name | Type | Description |
|:----:|:----:|----|
| limit | Integer | List comments limit. By default `20` |
| after | Integer | The integer ID of the last Comment that you've seen. |

##### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 3,
        "user_id": 1,
        "target_user": 1,
        "reply_user": 0,
        "body": "我是第三条评论",
        "commentable_id": 1,
        "commentable_type": "feeds",
        "created_at": "2017-07-20 08:53:24",
        "updated_at": "2017-07-20 08:53:24",
        "commentable": {
            "id": 1,
            "user_id": 1,
            "feed_content": "动态内容",
            "feed_from": 1,
            "like_count": 1,
            "feed_view_count": 0,
            "feed_comment_count": 6,
            "feed_latitude": null,
            "feed_longtitude": null,
            "feed_geohash": null,
            "audit_status": 1,
            "feed_mark": 1,
            "pinned": 0,
            "created_at": "2017-06-27 07:04:32",
            "updated_at": "2017-07-20 08:53:24",
            "deleted_at": null,
            "pinned_amount": 0,
            "images": [],
            "paid_node": null
        }
    }
]
```

| Name | Description |
|:----:|-----|
| id | The ID of the comment. |
| user_id | Commentator. |
| target_user | Own dynamic publisher. |
| reply_user | Reverted to the user.|
| body | The `body` of the comment. |
| commentable_id | The `commentable_id` of the commentable type. |
| commentable_type | The commentable type. |
| created_at | Comment release time. |
| updated_at | Comment update time. |
| commentable | the `commentable` of the commentable type source. Source delete the `commentable` is `null`. |
