<p align="center">
	<a href="http://www.thinksns.com" rel="noopener" target="_blank"><img src="https://github.com/slimkit/plus-small-screen-client/raw/master/public/plus.png" alt="Plus (ThinkSNS+) Logo"></a>
</p>

<h1 align="center">The Small Screen Client for <a href="https://github.com/slimkit/plus">Plus(ThinkSNS+)</a></h1>

<div align="center">

使用现代 Web 技术 [Vue.js](https://github.com/vuejs/vue) 编写的 [Plus(ThinkSNS+)](https://github.com/slimkit/plus) 浏览器单页客户端。

[![GitHub release](https://img.shields.io/github/release/slimkit/plus-small-screen-client.svg?style=flat-square)](https://github.com/slimkit/plus-small-screen-client/releases)
[![Build Status](https://img.shields.io/travis/com/slimkit/plus-small-screen-client/master.svg?style=flat-square)](https://travis-ci.com/slimkit/plus-small-screen-client)
[![QQ Group](https://img.shields.io/badge/QQ%20Group-651240785-red.svg?longCache=true&style=flat-square)](//shang.qq.com/wpa/qunwpa?idkey=01b61bdf8a7efc2a40ab4caab2d14793f340e5fe5d09aa0c2c17f3115a579678)

</div>

## 简介

这个浏览器单页客户端是项目使用 Apache-2.0 协议开源，基于 [Vue.js](https://github.com/vuejs/vue) 及 ES+ 等现代 Web 技术进行构建！
项目具有以下几个特点：

1. 易于分发，你可以将编译后文件分发到任何地方，不需要动态语言的支持。
2. 完全独立，你无需懂的后端程序，只需要调用文件化的 APIs 接口即可开发。
3. 高 App 还原，项目以 ThinkSNS+ 原生客户端为 UI 基准，进行高度还原。
4. 技术简单，我们使用中国接受度非常高的 [Vue.js](https://github.com/vuejs/vue) 进行开发，你可以快速入手。

## 安装

[Plus (ThinkSNS+) SPA 安装指南](https://slimkit.github.io/plus/guide/installation/install-spa.html)

## 配置

`.env` 文件配置说明 请参考[`.env.example`](https://github.com/slimkit/plus/blob/master/resources/spa/.env.example)

### 路由模式

路由模式支持 `hash` 和 `history` 两种模式，区别如下：

- `hash` 模式：无需后端支持，利用浏览器的「锚」功能进行路由定位。
- `history` 模式：需要后端的路由转发支持，正确设置的情况是所有请求都转发到 `index.html` 文件上

更加详细的描述请看 👉 [API 参考#mode](https://router.vuejs.org/zh/api/#mode)

### 跨域问题

你如果部署这个客户端到全新的地址中，那么你肯定会遇到跨域资源共享禁止问题，导致这个客户端无法正常工作。
在你安装完成 [Plus(ThinkSNS+)](https://github.com/slimkit/plus) 后，你可以进入 `/admin` 后台管理面板，然后你从左侧菜单点击「系统设置」
然后再从顶栏菜单选择 「安全」。

此时，你会看到一个 「跨域设置」设置，这个时候你应该咨询后端了解跨域资源共享的人，默认情况是允许所有跨域的，如果关闭了允许所有，请在 `Access-Control-Allow-Origin` 将程序的 host 添加进去即可！

## 开发部署

在项目中设置了三个有效命令：

- `serve` 用于开发，修改代码后会自动 Hot Reload
- `build` 用于正式部署的打包，执行完成后会多出一个 `dist/` 目录
- `lint` 用于代码检查和风格修复

## 第三方可选功能

### 落地页引导启动/下载 APP

应用使用 [MobLink](http://dashboard.mob.com/#!/link/dashboard) 第三方应用引导启动 APP

**注意：创建应用时请选择 MobLink 经典版，请勿升级至专业版，否则会无法正常使用和回退**

使用时请填写 `.env` 文件中的以下两行

```ini
# MobLink 唤起APP
VUE_APP_MOBLINK_ENABLE=true          # MobLink 引导启动 APP 开关
VUE_APP_MOBLINK_KEY=xxxxxxxxxxxx     # MobLink APP KEY
```

### 在线咨询 QQ

使用时请修改 `.env` 文件中的以下部分以唤起 QQ 在线资讯

```ini
# QQ 在线咨询
VUE_APP_QQ_CONSULT_ENALBE=true       # QQ 咨询开关
VUE_APP_QQ_CONSULT_UIN=10000         # QQ 号码 （需要先开通在线状态）
VUE_APP_QQ_CONSULT_LABEL=在线咨询      # 标签文本
```

## 目录结构

```
.
├── dist 已经编译好的静态资源
│   ├── css
│   ├── img
│   ├── js
│   └── libs
├── public 公共内容
│   └── libs
├── scripts vue编译时需要的命令
├── src 主目录，操作都在这里
│   ├── api api目录
│   ├── components 组件目录
│   │   ├── FeedCard 动态相关组件
│   │   ├── common 页面中使用的公共组件
│   │   ├── form 提交表单的公共组件
│   │   ├── reference
│   │   ├── style
│   │   │   └── pswp
│   │   ├── tabs tab相关的组件
│   │   └── vendor 三方登录组件
│   ├── console 控制台样式
│   ├── constants 常量
│   ├── directives 指令目录
│   ├── easemob 环信相关
│   ├── icons icon相关
│   ├── images 图片相关
│   ├── locales 多语言相关
│   ├── page 页面目录
│   │   ├── article
│   │   │   └── components
│   │   ├── checkin 签到
│   │   ├── common 公共页面
│   │   ├── feed 动态
│   │   ├── find 发现
│   │   ├── group 圈子
│   │   │   └── components
│   │   ├── message 消息
│   │   │   ├── children
│   │   │   │   ├── audits
│   │   │   │   ├── comments
│   │   │   │   └── likes
│   │   │   ├── components
│   │   │   └── list
│   │   ├── news 资讯
│   │   │   └── components
│   │   ├── post 发布页面
│   │   │   └── components
│   │   ├── profile 个人主页
│   │   │   ├── children
│   │   │   ├── collection
│   │   │   └── components
│   │   ├── question 问答
│   │   │   └── components
│   │   ├── rank 排行榜
│   │   │   ├── children
│   │   │   ├── components
│   │   │   └── lists
│   │   ├── sign 登录
│   │   ├── topic 话题
│   │   │   └── components
│   │   └── wechat
│   ├── plugins 插件
│   │   ├── imgCropper
│   │   ├── lstore
│   │   ├── message
│   │   │   └── style
│   │   └── message-box
│   ├── routers 路由目录
│   ├── stores vuex目录
│   │   ├── easemob
│   │   └── module
│   │       ├── easemob
│   │       ├── post
│   │       └── rank
│   ├── style 样式
│   ├── util 工具库
│   ├── utils 工具库
│   └── vendor 三方组件
│       └── easemob
└── tests 测试
    └── unit
        └── components
            └── common

```

## License

Plus 代码采用企业定制许可证发布，请参阅完整的[许可证文本](https://github.com/slimkit/plus/blob/master/LICENSE)

Copyright © 2018 Chengdu [ZhiYiChuangXiang](http://zhiyicx.com) Technology Co., Ltd. All rights reserved.
