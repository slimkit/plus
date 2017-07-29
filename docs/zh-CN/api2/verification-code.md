# 验证码

> 发送验证码支持 `email` 和 `phone`

非注册用户发送：

```
POST /verifycodes/register
```

注册用户发送：

```
POST /verifycodes
```

### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| phone | String | **如果 `email` 不存在则必须**，以 `sms` 模式给手机发送验证码。 |
| email | String | **如果 `phone` 不存在则必须**，以 `mail` 模式给邮箱发送验证码。 |

#### 响应

```
Status: 202 Accepted
```
```json
{
    "message": [
        "获取成功"
    ]
}
```