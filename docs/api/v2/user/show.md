# 获取用户信息
    
- [获取当前用户](#获取当前用户)
- [获取指定用户](#获取指定用户)
- [批量获取指定用户](#批量获取指定用户)

### 获取当前用户

```
GET /user
```

##### Headers

```
Status: 200 OK
```

##### Body

```json5
{
    "id": 1,
    "phone": "187xxxxxxxx",
    "email": null,
    "created_at": "2017-05-22 06:34:19",
    "updated_at":"2017-05-22 06:35:01",
    "deleted_at":null,
    "wallet": {
        "id": 1,
        "user_id": 1,
        "balance": 0,
        "created_at":"2017-05-22 00:00:00",
        "updated_at":"2017-05-22 00:00:00",
        "deleted_at":null
    },
    "datas": [
        {
            "id":1,
            "profile":"sex",
            "profile_name":"性别",
            "type":"radio",
            "default_options":"1:男|2:女|3:其他",
            "pivot":{
                "user_id":1,
                "user_profile_setting_id":1,
                "user_profile_setting_data":"1",
                "created_at":"2017-05-22 06:34:19",
                "updated_at":"2017-05-22 06:34:19"
            }
        }
    ]
}
```

### 获取指定用户

```
GET /users/1?following={user}&follower={user}
```

其中 `following`、`follower` 是可选参数，验证用户我是否关注以及是否关注我的用户 id ，默认为当前登陆用户。

##### Headers

```
Status: 200 OK
```

##### Body

```json5
{
    "id": 1,
    "phone": "187xxxxxxxx",
    "email": null,
    "created_at": "2017-05-22 06:34:19",
    "updated_at":"2017-05-22 06:35:01",
    "deleted_at":null,
    "following":false, // 获取用户是否关注了指定用户
    "follower":false, // 指定用户是否关注获取用户
    "datas": [
        {
            "id":1,
            "profile":"sex",
            "profile_name":"性别",
            "type":"radio",
            "default_options":"1:男|2:女|3:其他",
            "pivot":{
                "user_id":1,
                "user_profile_setting_id":1,
                "user_profile_setting_data":"1",
                "created_at":"2017-05-22 06:34:19",
                "updated_at":"2017-05-22 06:34:19"
            }
        }
    ]
}
```

### 批量获取指定用户

```
GET /users?user=1,2
```

> user 可以是一个值，或者多个值，多个值的时候用英文半角 `,` 分割。

##### Headers

```
Status: 200 OK
```

##### Body

```json5
[
    {
        "id": 1,
        "phone": "187xxxxxxxx",
        "email": null,
        "created_at": "2017-05-22 06:34:19",
        "updated_at":"2017-05-22 06:35:01",
        "deleted_at":null,
        "following":false, // 获取用户是否关注了认证用户
        "follower":false, // 认证用户是否关注了获取用户
        "datas": [
            {
                "id":1,
                "profile":"sex",
                "profile_name":"性别",
                "type":"radio",
                "default_options":"1:男|2:女|3:其他",
                "pivot":{
                    "user_id":1,
                    "user_profile_setting_id":1,
                    "user_profile_setting_data":"1",
                    "created_at":"2017-05-22 06:34:19",
                    "updated_at":"2017-05-22 06:34:19"
                }
            }
        ]
    }
]
```

----------

## 数据根信息

| 名称 | 类型 | 描述 |
|----|:----:|:----:|
| id | int | 用户ID |
| phone | string,null | 用户手机号码 |
| email | string,null | 用户电子邮箱 |
| created_at | string | 用户注册时间 |
| updated_at | string | 用户最后更新时间 |
| wallet | object,null | 用户钱包信息 |
| datas | array | 用户拓展信息 |

### 用户钱包信息

| 名称 | 类型 | 描述 |
|----|:----:|:----:|
| balance | int | 钱包余额，余额单位为「分」 |
| updated_at | string | 最后交易时间 |

### 用户拓展信息

| 名称 | 类型 | 描述 |
|----|:----:|:----:|
| profile | steing | 字段名称 |
| profile_name| string | 显示名称 |
| type | string | 字段类型 |
| default_options | string | 选项 |
| pivot.user_profile_setting_data | any | 表单值 |

