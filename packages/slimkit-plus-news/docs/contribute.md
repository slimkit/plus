# 投稿

- [提交投稿](#提交投稿)
- [修改投稿](#修改投稿)
- [删除投稿](#删除投稿)
- [申请退款](#申请退款)
- [获取用户投稿列表](#获取用户投稿列表)

## 提交投稿

```
POST /news/categories/:category/news
```

#### Input

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| title | String | **必须**，标题，最长 20 个字。 |
| subject | String | **必须**，主题，副标题，概述，最长 200 个字。 |
| content | String | **必须**，内容。 |
| image | Integer | 缩略图。 |
| tags | string,array | **必须** 标签id，多个id以逗号隔开或传入数组形式 |
| from | String | 资讯来源。 |
| author | String | 作者 |

##### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "投稿成功"
    ]
}
```

## 修改投稿

```
PATCH /news/categories/:category/news/:news
```

#### Input

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| title | String | 标题，最长 20 个字。 |
| subject | String | 主题，副标题，概述，最长 200 个字。 |
| content | String | 内容。 |
| tags | string,array | 标签id，多个id以逗号隔开或传入数组形式 |
| image | Integer | 缩略图。 |
| from | String | 资讯来源。 |
| author | String | 作者 |

##### Response

```
Status: 204 No Content
```

## 删除投稿

```
DELETE /news/categories/:category/news/:news
```

#### Response

```
Status: 204 No Content
```

## 申请退款

```
PUT /news/categories/:category/news/:news
```

#### Response

```
Status: 201 Created
```
```json
{
    "message": [
        "申请成功"
    ]
}
```

## 获取用户投稿列表

```
GET /user/news/contributes
```

#### 请求参数

| 名称 | 类型 | 描述 |
|:-----:|:----:|-----|
| limit | Integer | 获取条数，默认 20 |
| after | Integer | 上次获取列表最小的 ID。默认 0 |
| type  | Integer | 筛选类型 0-已发布 1-待审核 3-被驳回 默认为全部 |
| user  | Integer | 查询用户id，只能查看他人已发布的资讯 |

##### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "created_at": "2017-07-24 07:33:36",
        "updated_at": "2017-07-24 07:33:36",
        "title": "haha",
        "content": "### Title @！[image](1) \r\n @[image](xx)",
        "digg_count": 0,
        "comment_count": 0,
        "hits": 0,
        "from": "原创",
        "is_recommend": 0,
        "subject": "我是测试的概述",
        "author": "Seven",
        "audit_status": 1,
        "audit_count": 0,
        "user_id": 1,
        "contribute_amount": 0,
        "has_collect": false,
        "has_like": false,
        "category": {
            "id": 1,
            "name": "c1",
            "rank": 0
        },
        "image": {
            "id": 13,
            "size": "1932x1932"
        },
        "tags": []
    }
]
```

| 名称 | 描述 |
|:----:|----|
| id | 投稿，资讯ID |
| created_at | 创建时间 |
| updated_at | 更新时间 |
| title | 标题 |
| subject | 副标题，主题，概述 |
| content | 内容 |
| digg_count | 点赞，喜欢数量统计 |
| comment_count | 评论数 |
| hits | 阅读数 |
| from | 来源 |
| is_recommend | 是否被推荐 |
| author | 作者 |
| audit_status | 状态，0 正常，1 代审核， 3 驳回，5 退款中 |
| audit_count | 审核次数 |
| contribute_amount | 投稿支付了多少钱 |
| has_collect | 是否已收藏 |
| has_like | 是否已点赞 |
| category | 分类信息 |
| image | 缩略图信息 |
| category.id | 分类 ID |
| category.name | 分类名称 |
| image.id | file with ID |
| image.size | 图像尺寸，数据异常或者该附件为非图像，则为 null |
| tags | 标签列表 |
