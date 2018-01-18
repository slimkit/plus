# 获取资讯

- [获取资讯列表](#获取资讯列表)
- [获取置顶资讯列表](#获取置顶资讯列表)
- [获取资讯详情](#获取资讯详情)
- [获取一条资讯的相关资讯](#获取一条资讯的相关资讯)

## 获取资讯列表

```
GET /news
```

### 传入参数

| 名称 | 说明 |
|:----:|------|
| limit | 数据返回条数 默认为20 |
| after | 数据翻页标识 |
| key | 搜索关键字 |
| cate_id | 分类id |
| recommend | 推荐筛选 =1为筛选推荐资讯列表 |

#### Response

```
Status: 200 OK
```
```json
[
    {
        "id": 2,
        "title": "asdasdasdad",
        "subject": "阿拉拉啊拉",
        "created_at": "2017-08-02 03:28:38",
        "updated_at": "2017-08-02 09:08:51",
        "from": "原创",
        "author": "root",
        "user_id": 1,
        "hits": 10,
        "has_collect": false,
        "has_like": false,
        "category": {
            "id": 1,
            "name": "1121",
            "rank": 0
        },
        "image": {
            "id": 2,
            "size": "370x370"
        }
    },
    {
        "id": 1,
        "title": "123",
        "subject": "0",
        "created_at": "2017-07-31 11:56:32",
        "updated_at": "2017-07-31 06:31:31",
        "from": "原创",
        "author": "root",
        "user_id": 1,
        "hits": 5,
        "has_collect": false,
        "has_like": false,
        "category": {
            "id": 1,
            "name": "1121",
            "rank": 0
        },
        "image": {
            "id": 1,
            "size": "100x100"
        }
    }
]
```

### 返回参数

| 名称 | 类型 | 说明 |
|:----:|:----:|------|
| id   | int  | 数据id |
| title | string | 资讯标题 |
| subject | string | 副标题 |
| from | string | 来源 |
| author | string | 作者 |
| user_id | int | 发布者id |
| hits | int  | 点击数 |
| has_collect | bool | 当前用户是否已收藏 |
| has_like | bool | 当前用户是否已点赞 |
| category | array | 所属分类信息 |
| category.id | int | 所属分类id |
| category.name | string | 所属分类名称 |
| category.rank | int | 所属分类排序 |
| image | array/null | 资讯封面信息 为null时表示该资讯无缩略图|
| image.id | int | 资讯封面附件id |
| image.size | string | 资讯封面尺寸 |

## 获取置顶资讯列表

```
GET /news/categories/pinneds
```

### 传入参数

| 名称 | 说明 |
|:----:|------|
| cate_id | 分类id |

### Response

```
Status: 200 OK
```
> 返回数据体 同资讯列表

## 获取资讯详情

```
GET /news/{news}
```

### Response

```
Status: 200 OK
```
```json5
{
    "id": 2,
    "created_at": "2017-08-02 03:28:38",
    "updated_at": "2017-08-02 09:08:51",
    "title": "asdasdasdad",
    "content": "@![辣鸡啊](3) adasd image @![辣鸡啊](4)",
    "digg_count": 0,
    "comment_count": 0,
    "hits": 4,
    "from": "原创",
    "is_recommend": 0,
    "subject": "阿拉拉啊拉",
    "author": "root",
    "audit_status": 0,
    "audit_count": 0,
    "user_id": 1,
    "contribute_amount": 100,
    "has_collect": false,
    "has_like": false,
    "is_pinned": false,
    "category": {
        "id": 1,
        "name": "1121",
        "rank": 0
    },
    "image": {
        "id": 2,
        "size": "370x370"
    },
    "tags": [
        {
            "id": 2,
            "name": "标签2",
            "tag_category_id": 1
        },
        {
            "id": 1,
            "name": "标签1",
            "tag_category_id": 1
        }
    ]
}
```

### 返回参数
| 名称 | 类型 | 说明 |
|:----:|:----|------|
| id   | int  | 资讯id |
| title | string | 标题 |
| content | text | 资讯内容 markdown格式 含自定义标签() |
| digg_count | int | 点赞数 |
| comment_count | int | 评论数 |
| hits | int  | 点击量 |
| from | string | 来源 |
| is_recommend | int | 是否为推荐 |
| subject | string | 副标题 |
| author | string | 作者 |
| audit_status | int | 审核状态 </br> 0-正常 </br> 1-待审核 </br> 2-草稿 </br> 3-驳回 </br> 4-删除 </br> 5-退款中|
| audit_count | int | 审核驳回次数 |
| user_id | int | 发布者id |
| contribute_amount | int | 投稿金额 |
| has_collect | bool | 当前用户是否已收藏 |
| has_like | bool | 当前用户是否已点赞 |
| is_pinned | bool | 是否已置顶 1-已置顶 |
| category | array | 所属分类信息 |
| category.id | int | 所属分类id |
| category.name | string | 所属分类名称 |
| category.rank | int | 所属分类排序 |
| image | array | 资讯封面信息 |
| image.id | int | 资讯封面附件id |
| image.size | string | 资讯封面尺寸 |
| tags | array | 标签列表 |
| tags.id | int | 标签id |
| tags.name | string | 标签名称 |
| tags.tag_category_id | int | 标签分类id |

## 获取一条资讯的相关资讯

```
GET /news/{news}/correlations
```

### 可选参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 返回关联数据条数 |

### Response

```
Status: 200 OK
```
```json5
[
    {
        "id": 4,
        "title": "标题",
        "subject": "llaa",
        "created_at": "2017-08-07 09:31:00",
        "updated_at": "2017-08-08 06:41:51",
        "from": "原创",
        "author": "root",
        "user_id": 1,
        "hits": 34,
        "has_collect": false,
        "has_like": false,
        "category": {
            "id": 1,
            "name": "1121",
            "rank": 0
        },
        "image": {
            "id": 2,
            "size": "370x370"
        }
    },
    {
        "id": 1,
        "title": "123",
        "subject": "0",
        "created_at": "2017-07-31 11:56:32",
        "updated_at": "2017-08-08 01:58:07",
        "from": "222",
        "author": "111",
        "user_id": 1,
        "hits": 3,
        "has_collect": false,
        "has_like": false,
        "category": {
            "id": 1,
            "name": "1121",
            "rank": 0
        },
        "image": {
            "id": 1,
            "size": "100x100"
        }
    },
    {
        "id": 2,
        "title": "asdasdasdad",
        "subject": "阿拉拉啊拉",
        "created_at": "2017-08-02 03:28:38",
        "updated_at": "2017-08-03 07:53:34",
        "from": "原创",
        "author": "root",
        "user_id": 1,
        "hits": 5,
        "has_collect": false,
        "has_like": false,
        "category": {
            "id": 1,
            "name": "1121",
            "rank": 0
        },
        "image": {
            "id": 2,
            "size": "370x370"
        }
    }
]
```

### 返回参数

> 同获取资讯列表
