# 第三方社交账号登录支持

- [第三方报错消息格式](第三方报错消息格式)
- [变量类型](#变量类型)
- [获取已绑定的第三方](#获取已绑定的第三方)
- [检查绑定并获取用户授权](#检查绑定并获取用户授权)
- [检查注册信息或者注册用户](#检查注册信息或者注册用户)
- [绑定账号](#绑定账号)
- [取消绑定](#取消绑定)

## 第三方报错消息格式

第三方报错的消息格式使用统一格式返回，方便开发人员确认问题所在：

```
Message (# ID)
```

## 变量类型

在下述调用接口中将会出现 `:provider` 变量，其中可选：

| 值 | 描述 |
|:----:|----|
| qq | 腾讯 QQ 。 |
| weibo | 新浪 Weibo 。 |
| wechat | 腾讯微信 。 |

## 获取已绑定的第三方

```
GET /user/socialite
```

#### 响应

```
Status: 200 OK
```
```json
[
    "qq"
]
```

> 请求成功后，将返回用户已绑定第三方的 provider 名称，不存在列表中的代表用户并为绑定。

## 检查绑定并获取用户授权

```
POST /socialite/:provider
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| access_token | String | 获取的 Provider Access Token。 |

#### 响应

```
Status: 201 Created
```
> 返回的数据参考 「用户／授权」接口，如果返回 `404` 则表示没有改账号没有注册，进入第三方登录注册流程。

> 额外参数：`initial_password` ,是否已初始化密码，true 为已设置密码，false 时为未设置密码，此时可以调用「用户／修改密码」接口来设置密码。

## 检查注册信息或者注册用户

```
PATCH /socialite/:provider
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| access_token | String | 获取的 Provider Access Token。 |
| name | String | 用户名。 |
| check | Any | 如果是 `null` 、 `false` 或 `0` 则不会进入检查，如果 存在任何转为 `bool` 为 `真` 的值，则表示检查注册信息。 |

#### 响应

```
Status: 201 Created
```

> 注册成功返回信息如同 「[检查绑定并获取用户授权](#检查绑定并获取用户授权)」一致。

如果是检查注册信息，检查通过则返回 `204` 。


## 绑定账号

- [已登录账号绑定](#已登录账号绑定)
- [输入账号密码绑定](#输入账号密码绑定)

### 已登录账号绑定

```
PATCH /user/socialite/:provider
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| access_token | String | 获取的 Provider Access Token。 |

#### 响应

```
Status: 204 No Content
```

### 输入账号密码绑定

```
PUT /socialite/:provider
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| access_token | String | 获取的 Provider Access Token。 |
| login | String | 用户登录名，手机，邮箱。 |
| password | String | 用户密码。 |

#### 响应

```
Sttaus: 201 Created
```

> 注册成功返回信息如同 「[检查绑定并获取用户授权](#检查绑定并获取用户授权)」一致。

## 取消绑定

```
DELETE /user/socialite/:provider
```

#### 响应

```
Sttaus: 204 No Content
```
