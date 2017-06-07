# 启动信息

启动信息是在调用接口前的需求，可以在应用启动的时候一次性获取全部通用配置信息。

## 启动信息的移动端处理方式

移动端在版本发布支出将部分配置信息的默认配置打包写入本地.应用使用过程中更新启动信息,优先使用服务器提供的最新的信息.

## 列出所有启动者配置

```
GET /bootstrappers
```

### Response

Headers

```
Status: 200 OK
```

Bdoy

```json5
{
    "im:serve": "127.0.0.1:9900" // IM 服务器地址
    "im:helper": [ // IM 聊天助手用户信息
        {
            "uid": 1, // 用户ID
            "url": "https://plus.io/users/1" // 主页地址 URL
        }
        // ...
    ],
    "wallet:ratio": 200, // 转换显示余额的比例，百分比。（200 就表示 200%）
    "wallet:recharge-type": [ // 此配置支持全平台的支付方式，不同平台更具自身平台对应值判断是非支持该支付方式。
        "alipay", // "支付宝 APP 支付",
        "alipay_wap", // "支付宝手机网页支付",
        "alipay_pc_direct", // "支付宝电脑网站支付",
        "alipay_qr", // "支付宝扫码支付",
        "wx", // "微信 APP 支付",
        "wx_wap", // "微信 WAP 支付",
        "applepay_upacp" // "Apple Pay"
    ],
    "ad":[
        {
            "id":1,
            "title":"广告1",
            "type":"image",
            "data":{
                "image":"https://avatars0.githubusercontent.com/u/5564821?v=3&s=460",
                "link":"https://github.com/zhiyicx/thinksns-plus"
            }
        },
            {
            "id":2,
            "title":"广告2",
            "type":"markdown",
            "data":"# 广告2\n我是广告2"
        },
        {
            "id":3,
            "title":"广告3",
            "type":"html",
            "data":"<h1>广告3</h1><p>我不管我不管</p><script>alert('我是广告3')</script>"
        },
        {
            "id":4,
            "title":"广告4",
            "type":"user:id",
            "data":"1"
        }
    ]
}
```
