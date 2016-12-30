# APIs request documents for ThinkSNS plus.

# 前置信息

## response外层结构体
```json
{
    "status": false,
    "code": 0,
    "message": "",
    "data": null
}
```
| name     | type     | default  |description|
|----------|:--------:|:--------:|:---------:|
| status   | bool     | false    | 标示请求处理的处理类型，true: 成功处理，false: 错误处理|
| code     | int      | 0        | 消息码，可能涉及到友好的用户界面提示消息代码|
| message  | string   | ""       | 消息，多数用于不协商code消息码场景，后端直接给出消息，前端展示消息等场景 |
| data     | any      | null     | 消息数据体，any类型，代表任意类型数据，数据类型根据API的不同决定。|

⚠️：
- 所有的response最外层都将是上述结构体，所以注意下data字段的any类型解析。
- API请求实例cURL中的地址为相对地址，参数全部为test数据，并不保证真实有效，请自动行补全。

## 字段详解
* status: 接口请求，正确处理是 true, 错误处理是 false, 如果返回false,一定是错误信息输出.
* code: 当status是true的时候不会存在code信息，所以是默认值“0”，当false的时候会存在code码，当然，也存在特殊情况，例如后台需要强制输出提示信息的时候，message字段会存在信息，这个时候参照码不再是code，而是依据status状态输出message信息.
* message: 消息，字段不和任何字段挂钩，但是优先级高于code，行为单一，code用于标示错误类型，客户端依据code作出友好的提示文案，message信息则更强势一些，如果存在message信息，则强制输入message信息。
* data: 当接口处理成功的返回数据存放字段，一般情况下只有status为true的时候会存在信息，但是不排除debug模式中便于接口调试信息（客户端无须处理）。部分接口即是处理成功也不需要返回信息 ，例如获取验证码接口。只需要标示status位true即可。


# APIs.
1. [Overview](overview.md)
2. [Application theme](app_theme.md)
3. [Message codes](message_codes.md)
4. [Get phone verify code](auth-send-phone-code.md)
5. User login or register
    - [Overview](auth-overview.md)
    - [Forgot password](auth-forgot-password.md)
    - [User register](auth-register.md)

