# Auth, Forgot password. - ThinkSNS plus
找回密码

## request
```shell
curl -X POST -H "Content-Type: application/json" -d '{
    "phone": "18781993582",
    "code": "1234",
    "password": "123456"
}' "/api/auth/forgot"
```
| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| phone    | string   | yes      | 手机号码 |
| code     | int      | yes      | 手机号码验证码 |
| password | string   | yes      | 重置密码的新密码 |

## response
```json
{
    "status": true,
    "code": 0,
    "message": "重置密码成功",
    "data": null
}
```
重置密码无返回数据结构～但是存在message
