# 获取单个用户信息

获取单一用户的基本信息。

```
GET /users/{user}
```

-----------

### Input

| 名称 | 类型 | 描述 |
|------|:----:|------|
| user | int  | 用户id |

``/users/1``

### Response

##### Headers

```
Status: 200 OK
```

##### Body

```json5
{
  "id": 1,
  "name": "创始人",
  "email": null,
  "phone": "admin",
  "created_at": "2017-04-28 07:49:48",
  "updated_at": "2017-04-28 07:49:48",
  "deleted_at": null,
  "is_followed": 0,
  "is_following": 0,
  "datas": [
    {
      "id": 1,
      "profile": "sex",
      "profile_name": "性别",
      "type": "radio",
      "default_options": "1:男|2:女|3:其他",
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 1,
        "user_profile_setting_data": "1",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    },
    {
      "id": 2,
      "profile": "location",
      "profile_name": "地区",
      "type": "multiselect",
      "default_options": null,
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 2,
        "user_profile_setting_data": "北京市 市辖区 东城区",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    },
    {
      "id": 3,
      "profile": "intro",
      "profile_name": "简介",
      "type": "textarea",
      "default_options": null,
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 3,
        "user_profile_setting_data": "我是大管理员",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    },
    {
      "id": 4,
      "profile": "province",
      "profile_name": "省",
      "type": "input",
      "default_options": null,
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 4,
        "user_profile_setting_data": "110000",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    },
    {
      "id": 5,
      "profile": "city",
      "profile_name": "市",
      "type": "input",
      "default_options": null,
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 5,
        "user_profile_setting_data": "110100",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    },
    {
      "id": 6,
      "profile": "area",
      "profile_name": "区",
      "type": "input",
      "default_options": null,
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 6,
        "user_profile_setting_data": "110101",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    },
    {
      "id": 7,
      "profile": "education",
      "profile_name": "学历",
      "type": "checkbox",
      "default_options": "1:高中|2:大专|3:本科",
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 7,
        "user_profile_setting_data": "3",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    },
    {
      "id": 8,
      "profile": "name",
      "profile_name": "昵称",
      "type": "input",
      "default_options": null,
      "pivot": {
        "user_id": 1,
        "user_profile_setting_id": 8,
        "user_profile_setting_data": "管理员",
        "created_at": "2017-04-28 07:49:48",
        "updated_at": "2017-04-28 07:49:48"
      }
    }
  ],
  "counts": []
}
```

