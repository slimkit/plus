# User Register - ThinkSNS plus
用户注册

## request
```shell
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -d 'phone=18781993582&name=shiwei&code=2993&password=123456' "http://plus.io/api/auth/register"
```
| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| phone    | string   | yes      | 注册的手机号码 |
| name     | string   | yes      | 用户名 |
| code     | int      | yes      | 手机验证码 |
| password | string   | yes      | 用户密码 |

## response
登录成功后后台自动调用[auth/login]()接口，请查看login接口response.
