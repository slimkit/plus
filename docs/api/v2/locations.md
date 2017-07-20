# Locations

- [Search Locations](#search-locations)

## Search Locations

```
GET /locations/search
```

#### Parameters

| Name | Type | Description |
|:----:|:----:|----|
| name | String | Location Keyword. |

##### Response

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

| Name | Description |
|:----:|----|
| items | The current location of the next level of all areas. |
| tree | Location tree. |
| tree.parent | Parent location tree. |
| *.name | Location name. |

> If the tree level reaches three levels, then `items` are null.