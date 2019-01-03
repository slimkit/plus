# 开发者手册

## 版本发布

### 版本迭代

每次有更新需要推送到客户时，在根目录下执行 `npm version [patch|minor|major|prerelease]` 等（ version 更迭不支持 yarn 命令），有关 npm version，[参考这里](https://docs.npmjs.com/cli/version)

例如，即将从 `v4.0.0-rc.6` 更新到 `v4.0.0-rc.7`，这是一个 prerelease 版本迭代，则执行 `npm version prerelease` 后 push 到远端即可，该命令会自动 release tag 并推进客户端版本号。

本项目与 npm 版本号迭代规则略有不同，基本遵循以下特点

- 有问题修复，UI变更，功能优化，文案变更等内容，则发布 patch 版本
- 有新功能点、接口变更等，则发布 minor 版本
- 全面迭代，则发布 major 版本
- major 版本迭代后宣布稳定前，则发布 prerelease 版本

### commit message 规范

为了高效率生成 CHANGELOG (版本更新内容一览)，则 commit message 需要根据一定规范进行编写。

本项目使用 angular 规范 (可以参考阮一峰的 [Commit message 和 Change log 编写指南](http://www.ruanyifeng.com/blog/2016/01/commit_message_change_log.html))

每次执行 `npm version **` 时会自动生成截至上一版发布期间的所有 feature、fix、break change 内容。

## 开发规范

考虑到本项目的后续维护者参与维护，在参与开发前，请尽量阅读 Vue 官方编写的 [风格指南](https://cn.vuejs.org/v2/style-guide/)。  
为了快速入手项目的二次开发，这里列出一些必要的规范，请**务必阅读以下规范后再进行编码工作**。

### 组件开发规范 (experimental)

#### 组件命名

按照 vue 官方推荐的组件命名方法，文件名命名方式为大驼峰式 (CamelCase)，组件的 `name` 属性值要与文件名一致，便于使用 vue devtools 进行调试和快速定位文件。

#### 组件目录结构

可跨模块使用的组件存放在 `@/components` 目录中，按功能归类存放于子文件夹下。

在模块内使用的组件存放在 `@/page/[模块名]/components` 目录下。

> example
> 以动态模块为例，动态模块的视图存放在 `@/page/feed` 下，  
> 其中动态列表视图在 `@/page/feed/FeedList.vue`，动态详情视图在 `@/page/feed/FeedDetail.vue`；  
> 动态列表视图中含有普通的动态卡片组件和广告卡片组件，  
> 动态卡片组件在 `@/page/feed/components/FeedCard.vue`，广告卡片组件在 `@/page/feed/components/AdCard.vue`。

# API

## 钩子

全局钩子挂载于 `./src/bus.js` 下的事件总线上。

### ActionSheet

呼出底部按钮弹框

#### 调用方法
``` js
/**
 * @typedef {Object} ActionButton
 * @property {string} text - 按钮的文字
 * @property {Function} method - 按钮的回调方法
 * @property {Object} [style] - 按钮的样式
 */
const actions = [
  { text: this.$t('confirm'), method: () => { console.log('button clicked!') }, style: { color: 'green' } }
]
/**
 * Call actionSheet
 * @author jsonleex <jsonlseex@163.com>
 * @param {ActionButton[]} btnLists - [{ text: "确定", method: () => {} }, ...]
 * @param {string} [cancelTxt] - "取消"
 * @param {string} [tips] - 提示文字
 */
this.$bus.$emit('actionSheet', actions, '取消', '你确定要这么做吗？');
```

### 打赏 (reward)

呼出打赏窗口

#### 调用方法
以打赏动态为例
``` js
// ...
const api = axios.post(url, payload, {validateStatus});
const cb = amount => { console.log(amount); }
/**
 * 弹出打赏窗口 (hooks -> reward)
 * @author mutoe <mutoe@foxmail.com>
 * @param {Object} options
 * @param {string} options.type 打赏的类型
 * @param {AxiosPromise} options.api 打赏的 api，接受 axios promise 对象
 * @param {string|Object} options.payload api 的第一个参数，取决于 api
 * @param {requestCallback} [options.callback = noop] 打赏成功后的回调方法, 接受一个参数 amount 打赏金额
 */
this.$bus.$emit("reward", {
  type: "feed",
  api,
  payload: { feedId },
  callback：cb
});
```

### 申请置顶

#### 调用方法
``` js
// ...
const api = axios.post(url, payload, {validateStatus});
const cb = () => { console.log("success"); }
/**
 * 弹出申请置顶窗口 (hooks -> applyTop)
 * @author mutoe <mutoe@foxmail.com>
 * @param {Object} options
 * @param {string} options.type 申请置顶类型
 * @param {AxiosPromise} options.api 申请置顶的 api，需要返回 axios promise 对象
 * @param {string|Object} options.payload 申请置顶 api 的第一个参数，取决于 api 中的设定
 * @param {boolean} [options.isOwner = false] 是否是文章的所有者, 文章的所有者申请置顶时文案略有不同
 * @param {requestCallback} [options.callback = noop] 申请置顶成功后执行的回调方法
 */
this.$bus.$emit("applyTop", {
  type: "",
  api,
  payload: { feedId },
  isOwner: true,
  callback: cb
})
```

## 组件

### 通用头部 CommonHeader

`@/components/common/CommonHeader.vue`

用于各页面顶部导航栏. 通用组件默认注入到全局，你可以不用在页面内单独注册通用组件。

``` vue
<template>
  <div>
    <common-header class="header">
      钱包
      <template slot="right">
        <router-link :to="{ path: 'detail' }"> 明细 </router-link>
      </template>
    </common-header>
    <main>...<main>
  </div>
</template>
<script>
export default {}
</script>
```

#### `Props`

##### `back` {Function}

点击后退按钮的行为, 如果要执行除后退以外的其他操作, 给这个 prop 传入一个方法即可. 默认值为全局注入的 `goBack` 方法.

#### `Slot`

含有一个匿名slot和两个具名slot。

##### anonymous
显示为标题，居中显示，最大宽度支持 12em。

##### left **该插槽含有默认值**
显示在导航栏左边，最大宽度支持 4em

如果导航栏的左边没有其他特殊需要，可以不声明该插槽，默认生成一个含有 goBack 事件的返回按钮。

如果左边不想要任何东西，可以声明一个空模版。像这样
``` html
<common-header>
  左边没有按钮
  <template slot="left" />
</common-header>
```

##### right
显示在导航栏右边，最大宽度支持 4em

#### 为什么要使用它

你在新建页面时可以不必去其他页面复制过来这么一大坨代码，也可以让其他人维护这个页面的时候很清除的知道这个页面有哪些东西而不是如何实现的。  
只要你知道这个组件的名字就可以很方便的添加头部了。

``` html
<!-- before -->
<header class="m-box m-justify-bet m-aln-center m-bb1 m-head-top m-main m-lim-width">
  <div class="m-box m-flex-grow1 m-aln-center m-flex-base0">
    <svg class="m-style-svg m-svg-def" @click="goBack">
      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-back"/>
    </svg>
  </div>
  <div class="m-box-model m-flex-grow1 m-aln-center m-flex-base0 m-head-top-title">
    <span>提现</span>
  </div>
  <div class="m-box m-flex-grow1 m-aln-center m-flex-base0 m-justify-end"/>
</header>

<!-- after -->
<common-header>提现</common-header>
```

### 标签页导航栏 NavTab

`@/components/common/NavTab.vue`

``` vue
<template>
  <div class="p-group-search">
    <nav-tab :nav="nav" :route="{name: 'searchGroup', query: 'type'}" />

    <!-- ... -->

  </div>
</template>

<script>
import NavTab from '@/components/common/NavTab.vue';
export default {
  components: { NavTab },
  data: () => {
    nav: {
      groups: '圈子',
      posts: '帖子'
    }
  },
  created() {
    if (!this.$route.query.type)
      this.$router.replace(
        Object.assign({}, this.$route, { query: { type: "groups" } })
      );
  },
}
</script>
```

### 通用搜索栏 SearchBar

`@/components/common/SearchBar.vue`

``` vue
<template>
  <div class="p-search-post">
    <search-bar v-model="keyword" />

    <!-- ... -->

  </div>
</template>

<script>
import SearchBar from '@/components/common/SearchBar.vue';
export default {
  components: { SearchBar },
  data: () => {keyword: ''},
}
</script>
```

#### `Props`

##### `back` {Function}

点击后退按钮的行为, 如果要执行除后退以外的其他操作, 给这个 prop 传入一个方法即可. 默认值为全局注入的 `goBack` 方法.

##### `value` {string}

输入搜索内容, 双向绑定特性(使用 `v-model` 进行绑定)

##### `placeholder` {string}

提示内容, 默认值为 "搜索"

### 提示信息弹框 PopupDialog

`@/components/PopupDialog.vue`

用于各种临时信息展示，如规则展示，协议弹框展示，首次进入某功能弹框提示信息。

``` vue
<template>
  <div>
    <button @click="showDialog">show dialog</button>
    <popup-dialog ref="dialog" title="充值提现规则" @confirm="onConfirm">
      {{ 今晚打老虎今晚打老虎今晚打老虎今晚打老虎今晚打老虎... }}
    </popup-dialog>
  <div>
</template>

<script>
export default {
  methods: {
    showDialog() { this.$refs.dialog.show() },
    onConfirm() { console.log('confirm clicked!') }
  }
}
</script>
```

#### Slots

含有一个匿名 slot，为 dialog 主要内容

#### Props

##### title

可选，弹框标题

##### confirmText

可选，弹框的确定按钮文案。默认值为“知道了”

#### Events

##### confirm

当按下确定按钮时的回调事件

#### Methods

##### show

显示 dialog，缓慢上移淡入的动画。

#### hide

隐藏 dialog，缓慢下移淡出的动画

### banner 轮播广告位 BannerAd

`@/components/advertisement/BannerAd.vue`

> 该组件使用了轮播图插件, 依赖 `c-swipe` [pspgbhu/vue-swipe-mobile](https://github.com/pspgbhu/vue-swipe-mobile), gzip 10KB

用于各详情页广告位插槽，用法非常简单

``` vue
<template>
  <div>
    <banner-ad type="feed:hot"/>
  </div>
</template>
<script>
import BannerAd from "@/components/advertisiment/BannerAd.vue";
export default {
  components: { BannerAd }
}
</script>
```

#### `Props`

##### `type` {string} required

显示的广告位类型, 支持的值有
- `feed:hot` 热门动态列表页
- `news` 资讯列表页

用于获取对应页面广告具体数据

##### `loopTime` {number}

轮播图循环时间，单位 ms

### 详情页广告位 DetailAd

`@/components/advertisement/DetailAd.vue`

用于各详情页广告位插槽，用法非常简单

``` vue
<template>
  <div>
    <detail-ad type="feed"/>
  </div>
</template>
<script>
import DetailAd from "@/components/advertisement/DetailAd.vue";
export default {
  components: { DetailAd }
}
</script>
```

#### `Props`

##### `type` {string} required

显示的广告位类型, 支持的值有 
- `feed` 动态详情页 
- `news` 资讯详情页 
- `group:home` 圈子首页 
- `group:post` 圈子帖子详情页

用于获取对应页面广告具体数据


### 文件上传 ImageUploader

`@/components/common/ImageUploader.vue`

用于新版本、老版本兼容的文件上传组件，参考话题封面上传

#### `Props`

##### `type` {string} [id]

- `id`
- `storage`
- `blob`
- `url`

##### `value` {*}

该属性的指取决于 `type` 的值
- 为 `id` 时是老版本返回的文件 id
- 为 `storage` 时是新版本返回的 file node 节点
- 为 `blob` 时是临时的blob对象
- 为 `url` 时是临时的 url 地址

#### `Events`

##### `@update:src`

该属性返回的是一个图片临时地址，用于前端展示

`@update:src="src = $event"`

## 表单组件 FormItem

表单组件用于快速构建样式和交互方式统一的表单项

表单组件包含以下组件

- `FormInputItem` 文本输入框
- `FormLocationItem` 位置选择框
- `FormAvatarItem` 头像选择栏
- `FormTagsItem` 标签选择栏
- `FormSelectItem` 通用选择框
- `FormSwitchItem` 切换开关栏
- `TextareaInput` 多行输入文本框（不含label）

### 使用方法

``` vue
<template>
  <form>
    <form-avatar-item v-model="avatar" label="请上传圈子头像" />
    <form-input-item v-model="name" placeholder="请输入圈子名称, 20字以内" maxlength="20" />
    <form-input-item v-model="summary" type="textarea" placeholder="请输入圈子简介, 255字以内" maxlength="255" warnlength="200" />
    <form-tags-item v-model="tags" placeholder="请选择圈子标签"/>
    <form-select-item v-model="modeMap[mode] || ''" placeholder="请选择圈子类别" @click="mode = 'private'" />
    <form-location-item v-model="location" placeholder="设置圈子的地理位置" />
    <form-switch-item v-model="allowFeed" label="是否将帖子同步至动态" />
  </form>
  
  <textarea-input v-model="summary" maxlength="50" warnlength="30" placeholder="这是一个占满宽度的文本输入框" />
</template>

<script>
export default {
  data: () => {
    name: '',
    location: '',
    avatar: '', // base64 string
    tags: [], // tag array
    mode: 'public',
    allowFeed: false,
    modeMap: {
      public: "公开圈子",
      private: "私有圈子",
      paid: "付费圈子"
    }
  }
}
</script>
```

### 通用部分

通用适用于所有 FormItem 子组件

#### `Props`

##### label {string}

输入框标签 label

##### `placeholder` {string} `""(empty string)`

占位符

##### `readonly` {boolean} `false`

只读

### `FormInputItem` 文本输入框

该组件支持普通单行文本和多行文本

#### `Props`

##### `type` {string} `input`

输入框的类型, 支持 input(单行文本框) 和 textarea(多行文本框)

##### `maxlength` {number|string}

支持的最大字符数

##### `warnlength` {number|string}

**仅当 type="textarea" 时有效** 当字符数超过多少时显示字数提示
