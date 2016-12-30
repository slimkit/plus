# 获取验证手机号验证码 - ThinkSNS plus.
获取手机号验证码接口.

## request
```shell
curl -X POST -H "Content-Type: application/json" -d '{
    "phone": "187xxxxx582",
    "type": "register"
}' "/api/v1/auth/phone/send-code"
```
| name     | type     | must     | description |
|----------|:--------:|:--------:|:--------:|
| phone    | string   | yes      | 需要被发送验证码的手机号 |
| type     | string   | yes      | 发送验证码的类型，固定三个值(register、login、change) <br /> register: 注册 <br /> login: 登录 <br /> change: 修改 |

## response
无返回的数据信息.