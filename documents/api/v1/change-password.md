# 用户修改密码

## 接口地址

```text
/api/v1/users/password
```

## 请求方式

```text
PATCH
```

### HTTP Status Code

201

## 请求体

| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| password | string   | yes      | 用户原密码 |
| new_password | string | yes    | 用户新密码 |

## 返回体

无返回体