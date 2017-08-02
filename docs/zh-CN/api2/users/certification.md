# 用户身份认证

- [申请认证](#申请认证)
- [更新认证](#更新认证)
- [获取授权用户的认证信息](#获取授权用户的认证信息)

## 申请认证

```
POST /user/certification
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| type | String | **必须**, 认证类型，必须是 `user` 或者 `org`。 |
| files | Array\|Object | **必须**, 认证材料文件。必须是数组或者对象，value 为 文件ID。 |
| name | String | **必须**, 如果 `type` 是 `org` 那么就是负责人名字，如果 `type` 是 `user` 则为用户真实姓名。 |
| phone | String | **必须**, 如果 `type` 是 `org` 则为负责人联系方式，如果 `type` 是 `user` 则为用户联系方式。 |
| number | String | **必须**, 如果 `type` 是 `org` 则为营业执照注册号，如果 `type` 是 `user` 则为用户身份证号码。 |
| desc | String | **必须**，认证描述。 |
| org_name | String | 如果 `type` 为 `org` 则必须，企业或机构名称。 |
| org_address | String | 如果 `type` 为 `org` 则必须，企业或机构地址。 |

example:
```json
{
    "type": "user",
    "name": "Seven",
    "phone": "xxxxxxxx",
    "number": "aaaaaa",
    "desc": "hahahah",
    "files": [ 14, 15 ]
}
```

##### 响应

```
Status: 201 Created
```
```json
{
    "message": [
        "申请成功，等待审核"
    ]
}
```

## 更新认证

```
PATCH /user/certification
```

#### 输入

| Name | Type | Description |
|:----:|:----:|----|
| type | String | 认证类型，必须是 `user` 或者 `org`。 |
| files | Array\|Object | 认证材料文件。必须是数组或者对象，value 为 文件ID。 |
| name | String | 如果 `type` 是 `org` 那么就是负责人名字，如果 `type` 是 `user` 则为用户真实姓名。 |
| phone | String | 如果 `type` 是 `org` 则为负责人联系方式，如果 `type` 是 `user` 则为用户联系方式。 |
| number | String | 如果 `type` 是 `org` 则为营业执照注册号，如果 `type` 是 `user` 则为用户身份证号码。 |
| desc | String | 备注 |
| org_name | String | 企业或机构名称。 |
| org_address | String | 企业或机构地址。 |

##### 响应

```
Status: 201 Created
```
```json
{
    "message": [
        "修改成功，等待审核"
    ]
}
```

## 获取授权用户的认证信息

```
GET /user/certification
```

#### 响应

```
Status: 200 OK
```
```json
{
    "id": 1,
    "certification_name": "user",
    "user_id": 1,
    "data": {
        "name": "杜伟",
        "phone": "18781993582",
        "number": "xxxxxxxxxx",
        "desc": "hahaha",
        "files": [ 12 ],
        "org_name": "之一程序",
        "org_address": null
    },
    "examiner": 0,
    "status": 0,
    "created_at": "2017-07-22 06:22:49",
    "updated_at": "2017-07-22 06:45:57",
    "icon": null,
    "category": {
        "name": "user",
        "display_name": "个人认证",
        "description": null
    }
}
```
| 名称 | 描述 |
|:----:|----|
| certification_name | 认证类型名称 |
| data | 认证数据。 |
| examiner | 处理的用户 ID。 |
| status | 0 - 待审核, 1 - 通过, 2 - 拒绝。 |
| created_at | 申请时间。 |
| updated_at | 修改时间 |
| category | 认证分类数据。 |
| icon | 认证图标。 |
