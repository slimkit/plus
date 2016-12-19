# thinksns-plus
ThinkSNS+

# 服务器response外层结构体
```json
{
    "status": (bool) true, // 标示请求处理的处理类型，true: 成功处理，false: 错误处理
    "code": (int) 1, // 消息码，可能涉及到友好的用户界面提示消息代码
    "message": (string) “登录成功”, // 消息，多数用于不协商code消息码场景，后端直接给出消息，前端展示消息等场景
    "data": (any) ... // 消息数据体，any类型，代表任意类型数据，数据类型根据API的不同决定。
}
```