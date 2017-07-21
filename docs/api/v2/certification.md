# 用户认证

- [创建认证](#创建认证)
- [查询认证](#查询认证)
- [修改认证](#修改认证)

## 创建认证
```
POST /user/certification
```

### 参数说明
| 参数 | 是否必填 | 说明 |
| :---: | :---: | :---: |
| certification | must | 分类字符串 ['enterprise_certification', 'personal_certification']|
| name | 当certification为1时必填 | 个人认证：姓名 |
| company_name | 当certification为2时必填 | 企业认证：企业名称 |
| contact | must | 联系方式 |
| contact_name | 当certification为2时必填 | 企业认证：联系人姓名 |
| file | must | 证件照片(上传之后获取到的附件信息) |
| desc | must | 认证描述 |
| tips | not must | 认证备注(用户提交额外的认证信息) |
| identify | must | 身份证号或者企业营业执照号 |

### Response 
```
Status 201 
```
```json5
    {
    "id": 1,
    "certification": "enterprise_certification",
    "status": 0,
    "user_id": 2,
    "name": null,
    "contact_name": "王尼美",
    "desc": "垃圾公司",
    "tips": null,
    "company_name": "王尼玛集团",
    "contact": 18908019700,
    "file": 1,
    "identify": '234513'
}
```

## 查询认证
```
GET /user/certification
```

### 返回参数说
## Response 
```
Status 200 
```
```json5
{
    "id": 1, // 修改时路由中的{certification}
    "certification": "enterprise_certification", // 认证类型
    "status": 0, // 认证状态
    "user_id": 2, // 认证用户id
    "name": null, // 姓名
    "contact_name": "王尼美", // 企业联系人姓名
    "desc": "垃圾公司", // 认证说明
    "tips": null, // 认证备注
    "company_name": "王尼玛集团", // 企业名称
    "contact": 18908019700, // 联系人
    "file": 1 // 证件附件
    "identify": "23908472937"
}
```



## 修改认证
```
PATCH /user/certification/{certification}
```

### 参数说明
| 参数 | 是否必填 | 说明 |
| :---: | :---: | :---: |
| certification | must | 分类字符串 ['enterprise_certification', 'personal_certification'] |
| name | 当certification为1时必填 | 个人认证：姓名 |
| company_name | 当certification为2时必填 | 企业认证：企业名称 |
| contact | must | 联系方式 |
| contact_name | 当certification为2时必填 | 企业认证：联系人姓名 |
| file | must | 证件照片(上传之后获取到的附件信息) |
| desc | must | 认证描述 |
| tips | not must | 认证备注(用户提交额外的认证信息) |
| identify | must | 身份证号或者企业营业执照号 |

### Response
```
Status 201
```

- 修改的时候请提交修改的字段