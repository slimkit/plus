# User certification

- [Apply for certification](#apply-for-certification)
- [Update certification](#update-certification)
- [Get certification of the authenticated user](#get-certification-of-the-authenticated-user)

## Apply for certification

```
POST /user/certification
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| type | String | **Required**, Certification type, In `org` or `user`. |
| file | Integer | **Required**, Certification file. |
| name | String | **Required**, If `type` is `org`, it is the name of the person in charge. If it is `user`, it indicates the user name. |
| phone | String | **Required**, If `type` is `org`, it is the person in charge. If it is `user` that the user phone. |
| number | String | **Required**, If `type` is `org` business license registration number, if it is `user`, then the user identity card number. |
| desc | String | Description. |
| org_name | String | if `type` is `org` **Required**, Organization name. |
| org_address | String | if `type` is `org` **Required**, Organization adress. |

##### Response

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

## Update certification

```
PATCH /user/certification
```

#### Input

| Name | Type | Description |
|:----:|:----:|----|
| type | String | Certification type, In `org` or `user`. |
| file | Integer | Certification file. |
| name | String | If `type` is `org`, it is the name of the person in charge. If it is `user`, it indicates the user name. |
| phone | String | If `type` is `org`, it is the person in charge. If it is `user` that the user phone. |
| number | String | If `type` is `org` business license registration number, if it is `user`, then the user identity card number. |
| desc | String | Description. |
| org_name | String | if `type` is `org`, Organization name. |
| org_address | String | if `type` is `org`, Organization adress. |

##### Response

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

## Get certification of the authenticated user

```
GET /user/certification
```

#### Response

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
        "file": 12,
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
| Name | Description |
|:----:|----|
| certification_name | Certification type. |
| data | Certification data. |
| examiner | Examiner. |
| status | 0 - Pending review, 1 - Passed, 2 - Failed. |
| created_at | Create time. |
| updated_at | Update time. |
| category | Certification category. |
| icon | Certification icon. |
