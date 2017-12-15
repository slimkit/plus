# Feed comments

- [Send comment](#send-comment)
- [Get all comments](#get-all-comments)
- [Get a single comment](#get-a-single-comment)
- [Delete comment](#delete-comment)

## Send comment

```
POST /feeds/:feed/comments
```

#### Input

| Name | Type | Description
|:----:|:----:|----|
| body | String | Comment body. |
| reply_user | Integer | Reply comment to users. |

##### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "操作成功"
    ],
    "comment": {
        "user_id": 1,
        "reply_user": 0,
        "target_user": 1,
        "body": "我是第三条评论",
        "commentable_type": "feeds",
        "commentable_id": 1,
        "updated_at": "2017-07-20 08:53:24",
        "created_at": "2017-07-20 08:53:24",
        "id": 3
    }
}
```

## Get all comments

```
GET /feeds/:feed/comments
```

#### Parameters

| Name | Type | Description |
|:----:|:----:|----|
| limit | Integer | List comments limit, By default `20`. |
| after | Integer | The integer ID of the last Comment that you've seen. |

##### Response

```
Status: 200 OK
```
```json
{
    "pinneds": [
        {
            "id": 2,
            "user_id": 1,
            "target_user": 1,
            "reply_user": 0,
            "body": "我是第一条评论",
            "commentable_id": 1,
            "commentable_type": "feeds",
            "created_at": "2017-07-20 08:35:18",
            "updated_at": "2017-07-20 08:35:18"
        }
    ],
    "comments": [
        {
            "id": 3,
            "user_id": 1,
            "target_user": 1,
            "reply_user": 0,
            "body": "我是第三条评论",
            "commentable_id": 1,
            "commentable_type": "feeds",
            "created_at": "2017-07-20 08:53:24",
            "updated_at": "2017-07-20 08:53:24"
        }
    ]
}
```

| Name | Description |
|:----:|----|
| pinneds | Pinned comments list. |
| comment | Comments list. |
| *.id | The `ID` of the comment. |
| *.user_id | Commentator. |
| *.target_user | Own dynamic publisher. |
| *.reply_user | Reverted to the user. |
| *.body | The `body` of the comment. |
| *.commentable_id | Feeds id. |
| *.commentable_type | Commentable type. |
| *.created_at | Comment release time. |
| *.updated_at | Comment update time. |

## Get a single comment

```
GET /feeds/:feed/comments/:comment
```

##### Response

```
Status: 200 OK
```
```json
{
    "id": 1,
    "user_id": 1,
    "target_user": 1,
    "reply_user": 0,
    "body": "我是第一条评论",
    "commentable_id": 1,
    "commentable_type": "feeds",
    "created_at": "2017-07-20 08:34:41",
    "updated_at": "2017-07-20 08:34:41"
}
```

| Name | Description |
|:----:|----|
| id | The `ID` of the comment. |
| user_id | Commentator. |
| target_user | Own dynamic publisher. |
| reply_user | Reverted to the user. |
| body | The `body` of the comment. |
| commentable_id | Feeds id. |
| commentable_type | Commentable type. |
| created_at | Comment release time. |
| updated_at | Comment update time. |

## Delete comment

```
DELETE /feeds/:feed/comments/:comment
```

##### Response

```
Status: 204 No Content
```
