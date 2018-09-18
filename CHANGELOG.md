<a name="1.9.5"></a>
## [1.9.5](https://github.com/slimkit/plus/compare/1.8.6...1.9.5) (2018-09-18)



<a name="1.8.6"></a>
## [1.8.6](https://github.com/slimkit/plus/compare/1.7.7...1.8.6) (2018-09-18)



<a name="1.9.4"></a>
## [1.9.4](https://github.com/slimkit/plus/compare/1.8.5...1.9.4) (2018-09-08)



<a name="1.8.5"></a>
## [1.8.5](https://github.com/slimkit/plus/compare/1.9.3...1.8.5) (2018-09-08)


### Bug Fixes

* Remove redirect await page. ([f8d67ca](https://github.com/slimkit/plus/commit/f8d67ca)), closes [zhiyicx/thinksns-plus-android#2276](https://github.com/zhiyicx/thinksns-plus-android/issues/2276)
* **core:** 修复索引超过长度限制的问题 ([f70856c](https://github.com/slimkit/plus/commit/f70856c))
* **feed:** 修复用户置顶自己内容评论自己仍有审核通知数量提醒 ([8ad3aab](https://github.com/slimkit/plus/commit/8ad3aab)), closes [zhiyicx/thinksns-plus-android#2101](https://github.com/zhiyicx/thinksns-plus-android/issues/2101)
* **feed:** remove `$feed` param. ([f7a477f](https://github.com/slimkit/plus/commit/f7a477f))
* **news:** 修复用户在自己发布的资讯下置顶自己评论仍有未读消息数量通知 ([e95953a](https://github.com/slimkit/plus/commit/e95953a)), closes [zhiyicx/thinksns-plus-android#2101](https://github.com/zhiyicx/thinksns-plus-android/issues/2101)



<a name="1.9.3"></a>
## [1.9.3](https://github.com/slimkit/plus/compare/v1.8.4...1.9.3) (2018-08-22)



<a name="1.8.4"></a>
## [1.8.4](https://github.com/slimkit/plus/compare/v1.9.2...v1.8.4) (2018-08-22)


### Bug Fixes

* **admin:** 修复后台支付宝原生设置输入框缺省提示有误 ([e81eb6f](https://github.com/slimkit/plus/commit/e81eb6f)), closes [zhiyicx/thinksns-plus-android#2181](https://github.com/zhiyicx/thinksns-plus-android/issues/2181)
* **core:** 修复全包验证类使用错误的注入 ([a92fa24](https://github.com/slimkit/plus/commit/a92fa24)), closes [zhiyicx/thinksns-plus-android#2174](https://github.com/zhiyicx/thinksns-plus-android/issues/2174)
* **feed:** 修复动态数量统计没有移除 ([3604736](https://github.com/slimkit/plus/commit/3604736))
* **feed:** 修复可重复点赞和取消点赞导致数据错误 ([93fe950](https://github.com/slimkit/plus/commit/93fe950)), closes [zhiyicx/thinksns-plus-android#2117](https://github.com/zhiyicx/thinksns-plus-android/issues/2117)
* **feed:** 修复删除话题并没有删除话题关联数据 ([8346283](https://github.com/slimkit/plus/commit/8346283))
* **news:** 修改资讯后台「删除审核」为「删除申请审核」 ([e3a33bd](https://github.com/slimkit/plus/commit/e3a33bd)), closes [zhiyicx/thinksns-plus-android#2176](https://github.com/zhiyicx/thinksns-plus-android/issues/2176)
* **user:** 修复更新认真资料接口验证描述错误 ([ab7daf7](https://github.com/slimkit/plus/commit/ab7daf7)), closes [zhiyicx/thinksns-plus-android#2118](https://github.com/zhiyicx/thinksns-plus-android/issues/2118)



<a name="1.9.2"></a>
## [1.9.2](https://github.com/slimkit/plus/compare/v1.8.3...v1.9.2) (2018-08-15)



<a name="1.8.3"></a>
## [1.8.3](https://github.com/slimkit/plus/compare/v1.9.1...v1.8.3) (2018-08-15)


### Bug Fixes

* **core:** ( fixed [#341](https://github.com/slimkit/plus/issues/341) ) ⚠️Changed `UserHasBlacklists` to `UserHasBlackList` ([9f1a245](https://github.com/slimkit/plus/commit/9f1a245))
* **feed:** 删除话题移除话题关系( [#338](https://github.com/slimkit/plus/issues/338) ) ([f6b5517](https://github.com/slimkit/plus/commit/f6b5517))



<a name="1.9.1"></a>
## [1.9.1](https://github.com/slimkit/plus/compare/v1.9.0...v1.9.1) (2018-08-09)


### Bug Fixes

* **feed:** 修复后台话题搜索条件或者排序条件变换后页码错误 ( [#324](https://github.com/slimkit/plus/issues/324) ) ([af6ea2c](https://github.com/slimkit/plus/commit/af6ea2c))


### Features

* **feed:** 增加话题后台审核状态切换功能 ([ef4406e](https://github.com/slimkit/plus/commit/ef4406e))



<a name="1.9.0"></a>
# [1.9.0](https://github.com/slimkit/plus/compare/v1.8.2...v1.9.0) (2018-08-08)


### Bug Fixes

* 避免使用即将废弃的函数 [@boxshadow](https://github.com/boxshadow) ([ed67d13](https://github.com/slimkit/plus/commit/ed67d13))
* Fixed test loader un replace `plus.yml` configures ([31c7523](https://github.com/slimkit/plus/commit/31c7523))
* Fixed YAML error ([280370a](https://github.com/slimkit/plus/commit/280370a))
* **feat:** 获取用户动态返回话题数据 ([1d8f9ce](https://github.com/slimkit/plus/commit/1d8f9ce))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324) ) 修复二次操作页面按钮，搜索数据丢失 ([8b09341](https://github.com/slimkit/plus/commit/8b09341))
* **feed:** ( isses zhiyicx/thinksns-plus-android[#2201](https://github.com/slimkit/plus/issues/2201) ) Fixed builder participants query SQL not append `topic_id` column ([80c6c63](https://github.com/slimkit/plus/commit/80c6c63))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) 修复更新控制器代码使用不存在的对象 ([f36c6e2](https://github.com/slimkit/plus/commit/f36c6e2))
* **feed:** ( issue zhiyicx/thinksns-plus-android[#2209](https://github.com/slimkit/plus/issues/2209) ) Fixed report a feed topic throw SQL reportable not found ([588b264](https://github.com/slimkit/plus/commit/588b264))
* **feed:** ( issue zhiyicx/thinksns-plus-android[#2224](https://github.com/slimkit/plus/issues/2224) ) Fixed new statement query topic user link is std ([da0dee1](https://github.com/slimkit/plus/commit/da0dee1))
* **feed:** ( issues [#324](https://github.com/slimkit/plus/issues/324)  ) 修复编辑话题无效 ([8e8b84b](https://github.com/slimkit/plus/commit/8e8b84b))
* **feed:** 修复 $desc 为读取到的情况 ([34184ee](https://github.com/slimkit/plus/commit/34184ee))
* 修复一个七牛链接修改导致的图片bug ([1f18629](https://github.com/slimkit/plus/commit/1f18629))
* **feed:** 修复动态加载关系名称错误 ([e95116b](https://github.com/slimkit/plus/commit/e95116b))
* **feed:** 修复直接访问保护成员进行查询 ([0669c84](https://github.com/slimkit/plus/commit/0669c84))
* **feed:** Fixed feed topic user links table migration classname ([e3cafaa](https://github.com/slimkit/plus/commit/e3cafaa))


### Features

* **core:** Add a database settings support ([b7da0a1](https://github.com/slimkit/plus/commit/b7da0a1))
* **core:** Add a script with bash build assets ([d7d0b9d](https://github.com/slimkit/plus/commit/d7d0b9d))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324)  ) Add ID search and buttom tip ([a246dba](https://github.com/slimkit/plus/commit/a246dba))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324) ) 增加审核开源设置功能及页面 ([7335345](https://github.com/slimkit/plus/commit/7335345))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324) ) Add table order ([92df88f](https://github.com/slimkit/plus/commit/92df88f))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add a edit an topic API ([2f9ccda](https://github.com/slimkit/plus/commit/2f9ccda))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add a get a single topic API ([565b606](https://github.com/slimkit/plus/commit/565b606))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add list all topics API support 0nly hot ([b399ab6](https://github.com/slimkit/plus/commit/b399ab6))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add list feeds for a topic API ([b090019](https://github.com/slimkit/plus/commit/b090019))
* **feed:** 话题主页返回三个参与者，按照参与时间排序 ([861f453](https://github.com/slimkit/plus/commit/861f453))
* **feed:** 完成动态话题后台删除功能 ([28f2a07](https://github.com/slimkit/plus/commit/28f2a07))
* **feed:** 增加后台管理编辑话题功能 ([8980100](https://github.com/slimkit/plus/commit/8980100))
* **feed:** 增加一个面包屑组件 ([766dcf8](https://github.com/slimkit/plus/commit/766dcf8))
* **feed:** Add list participants for a topic API ([179267a](https://github.com/slimkit/plus/commit/179267a))
* **feed:** Create a topic add review ([eefcbca](https://github.com/slimkit/plus/commit/eefcbca))



<a name="1.8.2"></a>
## [1.8.2](https://github.com/slimkit/plus/compare/v1.8.1...v1.8.2) (2018-07-25)


### Bug Fixes

* 修复 RAW sql 字段标记错误 ([be05d52](https://github.com/slimkit/plus/commit/be05d52))



<a name="1.8.1"></a>
## [1.8.1](https://github.com/slimkit/plus/compare/v1.9.0-beta.0...v1.8.1) (2018-07-25)


### Bug Fixes

* **core:** Fixed non-loaded timezone and app env ([e504c3a](https://github.com/slimkit/plus/commit/e504c3a))
* **feed:** 修复动态话题资源读取 created_at 位置错误 ([2e0efcd](https://github.com/slimkit/plus/commit/2e0efcd))
* **feed:** Fixed increment column to `feeds_count` ([0e05bd0](https://github.com/slimkit/plus/commit/0e05bd0))
* **feed:** Fixed unknow FeedTopic class ([be4dfb0](https://github.com/slimkit/plus/commit/be4dfb0))


### Features

* **core:** 添加一个模型类型 ([16c0fe0](https://github.com/slimkit/plus/commit/16c0fe0))
* **core:** Add a DataTime to Zulu time format util ([bfba02d](https://github.com/slimkit/plus/commit/bfba02d))
* **feed:** 添加一个创建动态话题接口 ([f2eca06](https://github.com/slimkit/plus/commit/f2eca06))
* **feed:** Add a feed topic list API ([8406dd0](https://github.com/slimkit/plus/commit/8406dd0))
* **feed:** Add a follow a topic API ([2d504e6](https://github.com/slimkit/plus/commit/2d504e6))
* **feed:** Add a unfollow a topic API ([41b74b0](https://github.com/slimkit/plus/commit/41b74b0))
* **feed:** Create an feed support topics link ([e8a6b2e](https://github.com/slimkit/plus/commit/e8a6b2e))



<a name="1.9.0-beta.0"></a>
# [1.9.0-beta.0](https://github.com/slimkit/plus/compare/v1.8.0...v1.9.0-beta.0) (2018-07-17)


### Bug Fixes

* 改个小错 ([bd8cdfe](https://github.com/slimkit/plus/commit/bd8cdfe))
* 修复动态管理中，查看动态详情是没有显示点赞数量的bug ([da79b2c](https://github.com/slimkit/plus/commit/da79b2c))
* 修复多余的导航按钮以及增加markdown编辑器依赖 ([1b7045b](https://github.com/slimkit/plus/commit/1b7045b))
* 修改commit中的一个错误 ([e08bb56](https://github.com/slimkit/plus/commit/e08bb56))
* Fixed use `composer create-project slimkit/plus` hook ([3755a15](https://github.com/slimkit/plus/commit/3755a15))
* Fixed run `composer --no-dev` Not found faker ([c8b9540](https://github.com/slimkit/plus/commit/c8b9540))
* **ci:** Fixed Circle configure ([58c03e6](https://github.com/slimkit/plus/commit/58c03e6))
* **core:** 修复当深度合并的是索引数组时候不是替换，而是混合所有的值 ([1417e67](https://github.com/slimkit/plus/commit/1417e67))
* **deps:** Fixed undeps `[@slimkit](https://github.com/slimkit)/plus-editor` package ([886ad30](https://github.com/slimkit/plus/commit/886ad30))
* **docker:** 修复构建入口 ([7d71511](https://github.com/slimkit/plus/commit/7d71511))
* **docker:** Fixed FPM image composer working dir ([62d0545](https://github.com/slimkit/plus/commit/62d0545))
* **docker:** Fixed install composer ([a6945e9](https://github.com/slimkit/plus/commit/a6945e9))
* **Docker:** Fixed docker build context ([6766456](https://github.com/slimkit/plus/commit/6766456))
* **fpm:** Fixed dockerfile 30 line ([b2443b1](https://github.com/slimkit/plus/commit/b2443b1))


### Features

* Add app configure path method ([1f44980](https://github.com/slimkit/plus/commit/1f44980))
* Add FPM docker image ([c47b08d](https://github.com/slimkit/plus/commit/c47b08d))



<a name="1.8.0"></a>
# [1.8.0](https://github.com/slimkit/plus/compare/v1.7.6...v1.8.0) (2018-06-27)


### Bug Fixes

* (issue slimkit/thinksns-plus[#309](https://github.com/slimkit/plus/issues/309)) ([2fc787c](https://github.com/slimkit/plus/commit/2fc787c))
* 解决支付宝回调授权域名只能填写一个的问题 ([6ca1025](https://github.com/slimkit/plus/commit/6ca1025))
* 解决支付宝回调验证时抛出签名异常的bug ([3e12ef7](https://github.com/slimkit/plus/commit/3e12ef7))
* 解决use问题 ([4c15b3a](https://github.com/slimkit/plus/commit/4c15b3a))
* 判断错误 ([39f1a66](https://github.com/slimkit/plus/commit/39f1a66))
* 完善支付/回调 ([d474bef](https://github.com/slimkit/plus/commit/d474bef))
* 修复 CORS 设置中 credentials 单词拼写错误导致组件升级不正常 ([583af3a](https://github.com/slimkit/plus/commit/583af3a))
* 修复获取订阅的资讯500 ([ef550e4](https://github.com/slimkit/plus/commit/ef550e4))
* 修复积分充值成功，但是不到账的bug ([829cf20](https://github.com/slimkit/plus/commit/829cf20))
* 修复积分充值中的错误通知路由 ([fe68876](https://github.com/slimkit/plus/commit/fe68876))
* 修复模型的 user scope 语法错误 ([421f072](https://github.com/slimkit/plus/commit/421f072))
* 修复上次提交的错误冲突解决 ([12d334d](https://github.com/slimkit/plus/commit/12d334d))
* 修复通知接口和回调接口验证错误 ([174b47c](https://github.com/slimkit/plus/commit/174b47c))
* 修复新特性导致的错误 ([f042372](https://github.com/slimkit/plus/commit/f042372))
* 修复用户被打赏之后没有未读数量提示的问题 ([1da0549](https://github.com/slimkit/plus/commit/1da0549))
* 修复自己订阅的分类为空时500的错误 ([c3f5e13](https://github.com/slimkit/plus/commit/c3f5e13))
* 修改错误路由 ([5fbfc65](https://github.com/slimkit/plus/commit/5fbfc65))
* 资讯分类订阅逻辑修改 ([5223885](https://github.com/slimkit/plus/commit/5223885))
* 资讯置顶金额为0时报错 ([110b96e](https://github.com/slimkit/plus/commit/110b96e))
* iOS IAP产品ID输入类型修改 ([a386341](https://github.com/slimkit/plus/commit/a386341))


### Features

* 增加互亿无线短信网关 ([8f01769](https://github.com/slimkit/plus/commit/8f01769))
* 增加原生三方积分充值 ([9df8c13](https://github.com/slimkit/plus/commit/9df8c13))
* 增加支付第三方包依赖关系 ([b19afcc](https://github.com/slimkit/plus/commit/b19afcc))
* 支持通过ID、用户名、邮箱、手机号获取单个用户 ([86a6995](https://github.com/slimkit/plus/commit/86a6995))
* 支持无密码注册 ([77ed9a6](https://github.com/slimkit/plus/commit/77ed9a6))
* 支持验证码登录 ([8276e4a](https://github.com/slimkit/plus/commit/8276e4a))
* 注册协议，iphone上线使用 ([fd698be](https://github.com/slimkit/plus/commit/fd698be))
* 做一些路由修改, 以及后台的一个配置 ([c6ada86](https://github.com/slimkit/plus/commit/c6ada86))



<a name="1.8.0-rc.0"></a>
# [1.8.0-rc.0](https://github.com/slimkit/plus/compare/v1.8.0-alpha.6...v1.8.0-rc.0) (2018-05-15)


### Bug Fixes

* ( fixed zhiyicx/plus-component-h5[#348](https://github.com/slimkit/plus/issues/348) ) Fixed `files/{file}` API not opened `cors-should` ([ffc0683](https://github.com/slimkit/plus/commit/ffc0683))
* 修复后台操作动态置顶时的错误以及修复付费选项在后台显示错误 ([d2699d1](https://github.com/slimkit/plus/commit/d2699d1))
* 修改环信获取群组时的判断条件 ([183a20a](https://github.com/slimkit/plus/commit/183a20a))
* Fixed user avatar not opened cors ([d03fcd0](https://github.com/slimkit/plus/commit/d03fcd0))


### Features

* 初始化原生支付接口后台 ([d416911](https://github.com/slimkit/plus/commit/d416911))
* 新增环信获取群聊列表接口, 只返回群聊头像 ([bc3432b](https://github.com/slimkit/plus/commit/bc3432b))
* 音乐评论增加新版评论未读提醒 ([91abb33](https://github.com/slimkit/plus/commit/91abb33))
* 原生支付后台配置 ([57bd8d0](https://github.com/slimkit/plus/commit/57bd8d0))
* 增加接口 ([d44dbe9](https://github.com/slimkit/plus/commit/d44dbe9))
* 增加模型文件 ([853c833](https://github.com/slimkit/plus/commit/853c833))
* 增加新支付[alipay]的通知内容 ([f5e2587](https://github.com/slimkit/plus/commit/f5e2587))
* 增加用户背景图的最后修改时间戳 ([07fea49](https://github.com/slimkit/plus/commit/07fea49))



<a name="1.8.0-alpha.6"></a>
# [1.8.0-alpha.6](https://github.com/slimkit/plus/compare/v1.7.5...v1.8.0-alpha.6) (2018-05-08)


### Bug Fixes

* 补加用户黑名单模型 ([28a5744](https://github.com/slimkit/plus/commit/28a5744))
* 返回纯粹的数据 ([2df199c](https://github.com/slimkit/plus/commit/2df199c))
* 更新测试用力 ([a3d3c15](https://github.com/slimkit/plus/commit/a3d3c15))
* 解决老用户没有新版钱包记录时, 积分提现引起的bug ([126a21b](https://github.com/slimkit/plus/commit/126a21b))
* 去掉错误抛出并返回正确的内容 ([d918fa5](https://github.com/slimkit/plus/commit/d918fa5))
* 剔除gif图片的旋转 ([2186baf](https://github.com/slimkit/plus/commit/2186baf))
* 完善表单验证 ([b0e9103](https://github.com/slimkit/plus/commit/b0e9103))
* 修复阿里云设置未提交 ([da6a234](https://github.com/slimkit/plus/commit/da6a234))
* 修复错误 ([c9c6cbd](https://github.com/slimkit/plus/commit/c9c6cbd))
* 修复错误的键, 修改路由所需登录权限 ([873da6d](https://github.com/slimkit/plus/commit/873da6d))
* 修复第三方注册验证规则错误！！！ ([cf9e05f](https://github.com/slimkit/plus/commit/cf9e05f))
* 修复动态评论页面加载错误 ([4984641](https://github.com/slimkit/plus/commit/4984641))
* 修复负数bug ([708d514](https://github.com/slimkit/plus/commit/708d514))
* 修复黑名单列表加载重复 ([248800e](https://github.com/slimkit/plus/commit/248800e))
* 修复解析高德数据失败的错误 ([6fe8d15](https://github.com/slimkit/plus/commit/6fe8d15))
* 修复可对自己进行黑名单操作的bug ([812627f](https://github.com/slimkit/plus/commit/812627f))
* 修复路由中包含闭包, 导致不能缓存路由的情况 ([4425ecb](https://github.com/slimkit/plus/commit/4425ecb))
* 修复模型未引用等错误 ([a5b1d2a](https://github.com/slimkit/plus/commit/a5b1d2a))
* 修复签到排行榜加载重复用户的问题 ([9f60252](https://github.com/slimkit/plus/commit/9f60252))
* 修复申请评论置顶时的重复系统通知和审核通知 ([f7e2141](https://github.com/slimkit/plus/commit/f7e2141))
* 修复为登录时查询黑名单关系的错误, 以及缓存的错误键 ([b45ca2c](https://github.com/slimkit/plus/commit/b45ca2c))
* 修复未保存数据的bug ([81b6f32](https://github.com/slimkit/plus/commit/81b6f32))
* 修复小助手中的错误注入 ([393db69](https://github.com/slimkit/plus/commit/393db69))
* 修复写入新版用户钱包的错误 ([d7ec6e0](https://github.com/slimkit/plus/commit/d7ec6e0))
* 修复用户没有提交过认证的情况 ([fedb10f](https://github.com/slimkit/plus/commit/fedb10f))
* 修复语法错误 ([09d86fa](https://github.com/slimkit/plus/commit/09d86fa))
* 修改置顶的bug ([f277a0b](https://github.com/slimkit/plus/commit/f277a0b))
* 修正大小写 ([4de7149](https://github.com/slimkit/plus/commit/4de7149))
* 修正大小写问题 ([f0b74a7](https://github.com/slimkit/plus/commit/f0b74a7))
* 修正后台添加广告时尺寸不明确的问题 ([b8ff9c4](https://github.com/slimkit/plus/commit/b8ff9c4))
* 修正缓存带来的错误黑名单状态, 并增加在拉黑/移除时大的黑名单缓存 ([ff8c8cc](https://github.com/slimkit/plus/commit/ff8c8cc))
* 修正未读消息数量增加的对象用户 ([e9ef74c](https://github.com/slimkit/plus/commit/e9ef74c))
* 引入未use的变量 ([46b3f13](https://github.com/slimkit/plus/commit/46b3f13))
* 增加系统消息未读数, 兼容老版本 ([b511bbb](https://github.com/slimkit/plus/commit/b511bbb))


### Features

* **admin:** 增加用户停用管理 ([0b73f30](https://github.com/slimkit/plus/commit/0b73f30))
* **Admin:** 增加 CORS 管理接口 ([e5fbd26](https://github.com/slimkit/plus/commit/e5fbd26))
* **Admin:** 增加 CORS 配置面板 ([0fa1982](https://github.com/slimkit/plus/commit/0fa1982))
* **API:** Apped system type message to user unread count API ([117a940](https://github.com/slimkit/plus/commit/117a940))
* **API2:** Added a `user/counts` API, display user message counts ([14c98eb](https://github.com/slimkit/plus/commit/14c98eb))
* **Core:** 新增一个 Markdown 工具 ([e71aca8](https://github.com/slimkit/plus/commit/e71aca8))
* **Cors:** 增加小助手功能 ([559a4f0](https://github.com/slimkit/plus/commit/559a4f0))
* 增加动态/动态评论/资讯/资讯评论置顶所需积分的平均数 ([2dd6716](https://github.com/slimkit/plus/commit/2dd6716))



<a name="1.8.0-alpha.3"></a>
# [1.8.0-alpha.3](https://github.com/slimkit/plus/compare/v1.7.2...v1.8.0-alpha.3) (2018-03-24)



<a name="1.8.0-alpha.2"></a>
# [1.8.0-alpha.2](https://github.com/slimkit/plus/compare/v1.8.0-alpha.1...v1.8.0-alpha.2) (2018-03-16)



<a name="1.8.0-alpha.1"></a>
# [1.8.0-alpha.1](https://github.com/slimkit/plus/compare/v1.7.1...v1.8.0-alpha.1) (2018-03-14)



