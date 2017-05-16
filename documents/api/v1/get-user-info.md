# 获取用户信息

## 接口地址

`/api/v1/users/`

## 请求方法

```POST ```

## 接口变量

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| user_ids | array    | yes      | 用户id   |

### HTTP Status Code

201

## 返回体

```json5
{
  "status": true,
  "code": 0,
  "message": "获取成功",
  "data": [
    {
      "id": 1,
      "name": "管理员",
      "email": null,
      "phone": "18781993582",
      "created_at": "2017-03-02 08:12:46",
      "updated_at": "2017-03-02 08:12:46",
      "deleted_at": null,
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
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
            "created_at": "2017-03-02 08:14:13",
            "updated_at": "2017-03-02 08:14:13"
          }
        }
      ],
      "counts": [
        {
          "id": 1,
          "user_id": 1,
          "key": "diggs_count",
          "value": "0",
          "created_at": "2017-03-06 16:03:04",
          "updated_at": "2017-03-06 16:03:04"
        }
      ]
    }
  ]
}
```

## 返回字段

| name      | type     | must     | description |
|-----------|:--------:|:--------:|:--------:|
|datas      | array    | yes      | 用户资料字段|
|sex        | string   | no       | 1-男 2-女 3-其他 |
|location   | string   | no       | 地区 |
|intro      | string   | no       | 简介|
|province   | string   | no       | 省|
|city       | string   | no       | 市|
|area       | string   | no       | 区|
|education  | string   | no       | 学历|
|name       | string   | no       | 昵称|
|avatar     | string   | no       | 头像|
|cover      | string   | no       | 用户个人主页背景图|
|counts     | array    | yes      | 用户统计字段|
|key        | string   | no       | 统计字段键值 : diggs_count 用户收到的赞数统计 feeds_count 用户发送的动态数量 |
|value      | string   | no       | 对应键的值 |
返回内容没有固定的内容，字段来源为 配置接口中用户配置字段

code请参见[消息对照表](消息对照表.md)
