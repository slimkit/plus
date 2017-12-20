# around-amap

> around-ampa是TS+基于高德地图的云图服务开发的 附近的人功能扩展包
> 欢迎提交pr,issue 你们的支持是我们前行的动力

## 导语
1. 使用本扩展包需要在[高德地图](http://lbs.apam.com)创建服务平台为WEB服务的key，
2. 推荐权限使用 数字签名
3. 需要自定义地图， 从[数据管理台工具](http://yuntu.amap.com/datamanager/)进入后,在开发选项中获得必须的tableid
4. 初始数据默认为空， 所以需要使用手动标注的形式，添加一行数据，然后再需要添加数据列(也就是一个字段)user_id, 类型是整数，必须创建此数据列才能准确获取用户信息; 请看图例
5. 如果有疑问，请issue

## 接口说明

1. [创建/更新](#创建/更新)
2. [删除](#删除数据)
3. [查询附近](#根据经纬度查询周围最多50KM内的TS+用户)

## 创建/更新

#### 请求方法 
POST 

#### 接口地址

api/v2/around-amap

| params | must | desc |
| ---- | ---- | ---- |
| longitude | yes | 经度 |
| latitude | yes | 纬度 |

#### HTTP Status Code

201
#### 返回体

```json5
{
    "message": "位置创建成功",
    "_id": "14"
}
```

```json5
{
    "message": "位置更新成功"
}
```


## 删除数据
> 用于清除用户定位信息，数据将会从服务端以及高德地图中清除

#### 请求方法
DELETE

#### 接口地址

api/v2/around-amap

#### HTTP Status Code

204 No Content

## 根据经纬度查询周围最多50KM内的TS+用户
> 根据当前传递的坐标信息查找高德地图中附近的TS+用户

#### 请求方法
GET

#### 接口地址

api/v2/around-amap

#### 参数说明

| params | must | desc | 
| ---- | ---- | ---- |
|latitude | yes | 当前用户所在位置的纬度 | 
| longitude | yes | 当前用户所在位置的经度 |
| radius | no | 搜索范围，米为单位  [0 - 50000], 默认3000 |
| limit | no | 默认20， 最大100 |
| page | no | 分页参数， 默认1，当返回数据小于limit， page达到最大值 |

#### 请求示例

> http://192.168.2.199/api/v2/around-amap?latitude=30.566673&longitude=104.062056&radius=50000

#### HTTP Status Code

200 OK

#### 返回体

```json5
[
    {
        "_id": "18", // 高德数据id
        "_location": "104.062056,30.566673", // 经纬度坐标
        "_name": "成都智艺创想科技有限公司", // name
        "_address": "成都市武侯区环球中心S2区7-1-731成都智艺创想科技有限公司", // 地址(暂时可忽略)
        "user_id": 4, // ts+用户id 
        "_createtime": "2017-08-16 23:26:53", // 数据创建时间
        "_updatetime": "2017-08-16 23:27:13", // 数据更新时间
        "_province": "四川省", // 省
        "_city": "成都市", // 市
        "_district": "武侯区", // 区
        "_distance": "0", // 到中心坐标的距离
        "_image": [] // 可忽略
    }
]
```
