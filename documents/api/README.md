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
|name      |type      |default   |description|
|----------|:--------:|:--------:|:---------:|
|status    |bool      |false     |标示请求处理的处理类型，true: 成功处理，false: 错误处理|
|code      |int       |0         |消息码，可能涉及到友好的用户界面提示消息代码|
|message   | string   | ""       | 消息，多数用于不协商code消息码场景，后端直接给出消息，前端展示消息等场景 |
| data     | any      | null     | 消息数据体，any类型，代表任意类型数据，数据类型根据API的不同决定。|

⚠️：
- 所有的response最外层都将是上述结构体，所以注意下data字段的any类型解析。
- API请求实例cURL中的地址为相对地址，参数全部为test数据，并不保证真实有效，请自动行补全。


# APIs.
1. [Overview](overview.md)
2. [Application theme.](app_theme.md)
3. [Message codes](message_codes.md)
