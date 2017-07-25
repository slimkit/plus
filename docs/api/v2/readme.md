# ThinkSNS+ APIs v2

V2 版本的 APIs 是符合 REST full 的接口实现，所以在使用前请自行了解 REST ful 接口规范。

## APIs 目录

- [Overview](overview.md)
- [启动信息](bootstrappers.md)
- [Send verification code](verification-code.md)
- [Locations](locations.md)
- [User](user/show.md)
    - [Authorization](user/authorization.md)
    - [Register](user/register.md)
    - [List all comments of the authenticated user](user/comments.md)
    - [收到的赞](user/likes.md)
    - [Followers](user/followers.md)
- 钱包
    - [概述](wallet/readme.md)
    - [获取钱包信息](wallet/show.md)
    - [提现申请](wallet/cashes.md)
    - [余额充值](wallet/recharge.md)
    - [凭据](wallet/charge.md)

- 文件
    - [上传检查](file/uploaded.md)
    - [上传文件](file/upload.md)
    - [文件获取](file/show.md)

- 付费
    - [查询节点 & 付费状态](purchase/show.md)
    - [节点支付](purchase/pay.md)

- [消息通知（系统消息）](notifications.md)
- [User certification](certification.md)
