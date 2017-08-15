# 位置

- [搜索位置](#搜索位置)
- [热门城市](#热门城市)

## 搜索位置

```
GET /locations/search
```

#### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| name | 字符串 | 位置关键词。 |

##### 响应

```
Status: 200 OK
```
```json
[
    {
        "items": [
            {
                "id": 2508,
                "name": "成都市",
                "pid": 2507,
                "extends": "",
                "created_at": "2017-06-02 08:44:10",
                "updated_at": "2017-06-02 08:44:10"
            }
        ],
        "tree": {
            "id": 2507,
            "name": "四川省",
            "pid": 1,
            "extends": "",
            "created_at": "2017-06-02 08:44:10",
            "updated_at": "2017-06-02 08:44:10",
            "parent": {
                "id": 1,
                "name": "中国",
                "pid": 0,
                "extends": "3",
                "created_at": "2017-06-02 08:43:54",
                "updated_at": "2017-06-02 08:43:54",
                "parent": null
            }
        }
    }
]
```

| 字段 | 描述 |
|:----:|----|
| items | 搜索的选中地址的下一级所有地区列表。 |
| tree | 搜索的选中地区树。 |
| tree.parent | 父级地区数据。 |
| *.name | 地区名称 |

> 如果搜索的地区树达到三级，则 `items` 为 `null`。


## 热门城市

```
GET /locations/hots
```