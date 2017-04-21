# 刷新认证 TOKEN

## 接口地址

```text
/api/v1/auth
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
| refresh_token | string | yes   | 用于刷新token的token |
| device_code | string | yes     | 设备号 |

## 返回体

刷新后自动调用[auth](用户登录.md)接口，请查看login接口response.