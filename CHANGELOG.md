<a name="2.0.0"></a>
# [2.0.0](https://github.com/slimkit/plus/compare/1.9.5...2.0.0) (2018-09-20)


### Bug Fixes

* **feeds:** 修复动态排行榜没有返回用户头像 ([33eda40](https://github.com/slimkit/plus/commit/33eda40))
* **feeds:** 修复获取审核动态列表返回动态资源错误 ([0008dd4](https://github.com/slimkit/plus/commit/0008dd4)), closes [zhiyicx/thinksns-plus-android#2365](https://github.com/zhiyicx/thinksns-plus-android/issues/2365)
* **file-storage:** 修复 FileMeta 不支持 json 序列化接口 ([566fffc](https://github.com/slimkit/plus/commit/566fffc)), closes [zhiyicx/thinksns-plus-android#2377](https://github.com/zhiyicx/thinksns-plus-android/issues/2377)
* **news:** 修复资讯排行榜无用户头像返回 ([2629ce3](https://github.com/slimkit/plus/commit/2629ce3)), closes [zhiyicx/thinksns-plus-android#2377](https://github.com/zhiyicx/thinksns-plus-android/issues/2377)
* **news:** 修复自己置顶自己的资讯会直接成功！ ([4870318](https://github.com/slimkit/plus/commit/4870318))



<a name="1.9.5"></a>
## [1.9.5](https://github.com/slimkit/plus/compare/1.8.6...1.9.5) (2018-09-18)


### Bug Fixes

* **core:** 修复 `app:version` 命令被异常移除 ([1b84c09](https://github.com/slimkit/plus/commit/1b84c09))
* **core:** 修复支付付费节点引入命名空间错误 ([0cc9fe4](https://github.com/slimkit/plus/commit/0cc9fe4))
* **core:** Fixed unique array, Associative to index ([d29919b](https://github.com/slimkit/plus/commit/d29919b))
* **feed:** 修复动态话题 logo 验证错误和读取错误 ([9e8260d](https://github.com/slimkit/plus/commit/9e8260d))


### Features

* **docs:** 增加文档 GH-Pages 发布脚本 ([cb03e07](https://github.com/slimkit/plus/commit/cb03e07))
* **file-storage:** 储存增加上传后的 node 验证规则 ([3fe06d7](https://github.com/slimkit/plus/commit/3fe06d7))



<a name="1.9.4"></a>
## [1.9.4](https://github.com/slimkit/plus/compare/1.8.5...1.9.4) (2018-09-08)


### Bug Fixes

* **core:** 修复默认欢迎页面读取用户头像错误 ([3af526c](https://github.com/slimkit/plus/commit/3af526c))
* **core:** 修复索引超过长度限制的问题 ([f70856c](https://github.com/slimkit/plus/commit/f70856c))
* **feed:** 修复今日动态排行时间错误，修改为零点为起点 ([ced9535](https://github.com/slimkit/plus/commit/ced9535)), closes [zhiyicx/thinksns-plus-android#2282](https://github.com/zhiyicx/thinksns-plus-android/issues/2282)
* **feed:** remove `$feed` param. ([f7a477f](https://github.com/slimkit/plus/commit/f7a477f))
* **file-storage:** 修复 阿里云 oss 命名空间大小写问题 ([cce922b](https://github.com/slimkit/plus/commit/cce922b))
* **user:** 修复 At 我的获取最新预览消息用户名错为自身 ([33cefe3](https://github.com/slimkit/plus/commit/33cefe3)), closes [zhiyicx/thinksns-plus-android#2304](https://github.com/zhiyicx/thinksns-plus-android/issues/2304)


### Features

* 完成阿里云驱动 ([85de9c1](https://github.com/slimkit/plus/commit/85de9c1))
* **admin:** 添加存储设置文件 MIME 管理 ([7ddfc8a](https://github.com/slimkit/plus/commit/7ddfc8a))
* **admin:** 完成公开频道设置面板 ([70ad60e](https://github.com/slimkit/plus/commit/70ad60e))
* **admin:** 增加阿里云 OSS 文件系统设置面板 ([f06b755](https://github.com/slimkit/plus/commit/f06b755))
* **admin:** 增加本地文件系统设置面板 ([a1301ad](https://github.com/slimkit/plus/commit/a1301ad))
* **admin:** 增加存储设置，图片储存设置 ([4044ff3](https://github.com/slimkit/plus/commit/4044ff3))
* **admin:** 增加默认文件系统设置面板 ([cf9d2d5](https://github.com/slimkit/plus/commit/cf9d2d5))
* **admin:** 增加文件系统上传大小限制设置 ([1aa18bb](https://github.com/slimkit/plus/commit/1aa18bb))
* **core:** 完成本地直传方案本地驱动开发 ([69410ec](https://github.com/slimkit/plus/commit/69410ec))
* **core:** 新增一个一主双备缓存工具 ([c6a03ca](https://github.com/slimkit/plus/commit/c6a03ca)), closes [#351](https://github.com/slimkit/plus/issues/351)
* **core:** 增加文件本地驱动，增加用户测试代码 ([42bf3c6](https://github.com/slimkit/plus/commit/42bf3c6))
* **core:** 增加新的文件直传文件系统，完成 local 驱动创建任务和上传文件 ([9c7eae8](https://github.com/slimkit/plus/commit/9c7eae8)), closes [#362](https://github.com/slimkit/plus/issues/362)
* **file-storage:** 阿里云 OSS 进行内外隔离，提高 50% 数据获取速度 ([ec47bc5](https://github.com/slimkit/plus/commit/ec47bc5))
* **pay:** 支付节点需要进行密码输入 ([348f1b9](https://github.com/slimkit/plus/commit/348f1b9))



<a name="1.9.3"></a>
## [1.9.3](https://github.com/slimkit/plus/compare/v1.8.4...1.9.3) (2018-08-22)


### Bug Fixes

* **feed:** 修复 At 内容不存在的情况下类型校验错误 ([28ea574](https://github.com/slimkit/plus/commit/28ea574))
* **feed:** 修复动态数量统计没有移除 ([3604736](https://github.com/slimkit/plus/commit/3604736))
* **feed:** 修复删除话题并没有删除话题关联数据 ([8346283](https://github.com/slimkit/plus/commit/8346283))


### Features

* 音乐 & 资讯评论增加 at 人功能 ([ae08fe4](https://github.com/slimkit/plus/commit/ae08fe4)), closes [#337](https://github.com/slimkit/plus/issues/337)
* **core:** Add list all comments API ([f4ecd94](https://github.com/slimkit/plus/commit/f4ecd94)), closes [#337](https://github.com/slimkit/plus/issues/337)
* **feed:** ( [#343](https://github.com/slimkit/plus/issues/343) ) 动态支持转发功能 ([49362e3](https://github.com/slimkit/plus/commit/49362e3))
* **feed:** ( isses [#337](https://github.com/slimkit/plus/issues/337) ) 动态评论增加 at 人功能 ([dbb2034](https://github.com/slimkit/plus/commit/dbb2034))
* **feed:** 动态列表支持按照 ID 返回动态数据 ([1970bc7](https://github.com/slimkit/plus/commit/1970bc7))
* **news:** ( [#337](https://github.com/slimkit/plus/issues/337) ) 资讯增加按照 ID 获取资讯列表 ([af990d5](https://github.com/slimkit/plus/commit/af990d5))



<a name="1.9.2"></a>
## [1.9.2](https://github.com/slimkit/plus/compare/v1.8.3...v1.9.2) (2018-08-15)


### Bug Fixes

* **feed:** 删除话题移除话题关系( [#338](https://github.com/slimkit/plus/issues/338) ) ([f6b5517](https://github.com/slimkit/plus/commit/f6b5517))


### Features

* **core:** 模型类型标记类增加静态重载功能 ([1bd41e5](https://github.com/slimkit/plus/commit/1bd41e5))
* **core:** 未读消息列表返回三条语言消息( [#337](https://github.com/slimkit/plus/issues/337) ) ([aa020ec](https://github.com/slimkit/plus/commit/aa020ec))
* **core:** 增加 At 我的接口列表( [#337](https://github.com/slimkit/plus/issues/337) ) ([0b53c78](https://github.com/slimkit/plus/commit/0b53c78))
* **core:** 增加 At 消息基本代码 ([6056ba7](https://github.com/slimkit/plus/commit/6056ba7))
* **feed:** 增加发布动态 At 好友功能 ([41df582](https://github.com/slimkit/plus/commit/41df582))
* **user:** 用户统计返回 at 的消息未读数 ([241fa40](https://github.com/slimkit/plus/commit/241fa40))
* **user:** 增加根据用户名获取用户资料 ([c00fe83](https://github.com/slimkit/plus/commit/c00fe83))



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
* **core:** Fixed non-loaded timezone and app env ([e504c3a](https://github.com/slimkit/plus/commit/e504c3a))
* **feat:** 获取用户动态返回话题数据 ([1d8f9ce](https://github.com/slimkit/plus/commit/1d8f9ce))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324) ) 修复二次操作页面按钮，搜索数据丢失 ([8b09341](https://github.com/slimkit/plus/commit/8b09341))
* **feed:** ( isses zhiyicx/thinksns-plus-android[#2201](https://github.com/slimkit/plus/issues/2201) ) Fixed builder participants query SQL not append `topic_id` column ([80c6c63](https://github.com/slimkit/plus/commit/80c6c63))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) 修复更新控制器代码使用不存在的对象 ([f36c6e2](https://github.com/slimkit/plus/commit/f36c6e2))
* **feed:** ( issue zhiyicx/thinksns-plus-android[#2209](https://github.com/slimkit/plus/issues/2209) ) Fixed report a feed topic throw SQL reportable not found ([588b264](https://github.com/slimkit/plus/commit/588b264))
* **feed:** ( issue zhiyicx/thinksns-plus-android[#2224](https://github.com/slimkit/plus/issues/2224) ) Fixed new statement query topic user link is std ([da0dee1](https://github.com/slimkit/plus/commit/da0dee1))
* **feed:** ( issues [#324](https://github.com/slimkit/plus/issues/324)  ) 修复编辑话题无效 ([8e8b84b](https://github.com/slimkit/plus/commit/8e8b84b))
* **feed:** 修复 $desc 为读取到的情况 ([34184ee](https://github.com/slimkit/plus/commit/34184ee))
* **feed:** 修复动态话题资源读取 created_at 位置错误 ([2e0efcd](https://github.com/slimkit/plus/commit/2e0efcd))
* 修复一个七牛链接修改导致的图片bug ([1f18629](https://github.com/slimkit/plus/commit/1f18629))
* **feed:** 修复动态加载关系名称错误 ([e95116b](https://github.com/slimkit/plus/commit/e95116b))
* **feed:** 修复直接访问保护成员进行查询 ([0669c84](https://github.com/slimkit/plus/commit/0669c84))
* **feed:** Fixed feed topic user links table migration classname ([e3cafaa](https://github.com/slimkit/plus/commit/e3cafaa))
* **feed:** Fixed increment column to `feeds_count` ([0e05bd0](https://github.com/slimkit/plus/commit/0e05bd0))
* **feed:** Fixed unknow FeedTopic class ([be4dfb0](https://github.com/slimkit/plus/commit/be4dfb0))


### Features

* **core:** 添加一个模型类型 ([16c0fe0](https://github.com/slimkit/plus/commit/16c0fe0))
* **core:** Add a database settings support ([b7da0a1](https://github.com/slimkit/plus/commit/b7da0a1))
* **core:** Add a DataTime to Zulu time format util ([bfba02d](https://github.com/slimkit/plus/commit/bfba02d))
* **core:** Add a script with bash build assets ([d7d0b9d](https://github.com/slimkit/plus/commit/d7d0b9d))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324)  ) Add ID search and buttom tip ([a246dba](https://github.com/slimkit/plus/commit/a246dba))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324) ) 增加审核开源设置功能及页面 ([7335345](https://github.com/slimkit/plus/commit/7335345))
* **feed:** ( isses [#324](https://github.com/slimkit/plus/issues/324) ) Add table order ([92df88f](https://github.com/slimkit/plus/commit/92df88f))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add a edit an topic API ([2f9ccda](https://github.com/slimkit/plus/commit/2f9ccda))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add a get a single topic API ([565b606](https://github.com/slimkit/plus/commit/565b606))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add list all topics API support 0nly hot ([b399ab6](https://github.com/slimkit/plus/commit/b399ab6))
* **feed:** ( issue [#324](https://github.com/slimkit/plus/issues/324) ) Add list feeds for a topic API ([b090019](https://github.com/slimkit/plus/commit/b090019))
* **feed:** 话题主页返回三个参与者，按照参与时间排序 ([861f453](https://github.com/slimkit/plus/commit/861f453))
* **feed:** 添加一个创建动态话题接口 ([f2eca06](https://github.com/slimkit/plus/commit/f2eca06))
* **feed:** 完成动态话题后台删除功能 ([28f2a07](https://github.com/slimkit/plus/commit/28f2a07))
* **feed:** 增加后台管理编辑话题功能 ([8980100](https://github.com/slimkit/plus/commit/8980100))
* **feed:** 增加一个面包屑组件 ([766dcf8](https://github.com/slimkit/plus/commit/766dcf8))
* **feed:** Add a feed topic list API ([8406dd0](https://github.com/slimkit/plus/commit/8406dd0))
* **feed:** Add a follow a topic API ([2d504e6](https://github.com/slimkit/plus/commit/2d504e6))
* **feed:** Add a unfollow a topic API ([41b74b0](https://github.com/slimkit/plus/commit/41b74b0))
* **feed:** Add list participants for a topic API ([179267a](https://github.com/slimkit/plus/commit/179267a))
* **feed:** Create a topic add review ([eefcbca](https://github.com/slimkit/plus/commit/eefcbca))
* **feed:** Create an feed support topics link ([e8a6b2e](https://github.com/slimkit/plus/commit/e8a6b2e))



<a name="1.9.0-beta.0"></a>
# [1.9.0-beta.0](https://github.com/slimkit/plus/compare/v1.8.0...v1.9.0-beta.0) (2018-07-17)


### Bug Fixes

* 修复新特性导致的错误 ([f042372](https://github.com/slimkit/plus/commit/f042372))
* Fixed use `composer create-project slimkit/plus` hook ([3755a15](https://github.com/slimkit/plus/commit/3755a15))
* **ci:** Fixed Circle configure ([58c03e6](https://github.com/slimkit/plus/commit/58c03e6))
* **core:** 修复当深度合并的是索引数组时候不是替换，而是混合所有的值 ([1417e67](https://github.com/slimkit/plus/commit/1417e67))
* **deps:** Fixed undeps `[@slimkit](https://github.com/slimkit)/plus-editor` package ([886ad30](https://github.com/slimkit/plus/commit/886ad30))
* **docker:** 修复构建入口 ([7d71511](https://github.com/slimkit/plus/commit/7d71511))
* **docker:** Fixed FPM image composer working dir ([62d0545](https://github.com/slimkit/plus/commit/62d0545))
* **docker:** Fixed install composer ([a6945e9](https://github.com/slimkit/plus/commit/a6945e9))
* **Docker:** Fixed docker build context ([6766456](https://github.com/slimkit/plus/commit/6766456))
* **fpm:** Fixed dockerfile 30 line ([b2443b1](https://github.com/slimkit/plus/commit/b2443b1))


### Features

* 支持通过ID、用户名、邮箱、手机号获取单个用户 ([86a6995](https://github.com/slimkit/plus/commit/86a6995))
* 支持无密码注册 ([77ed9a6](https://github.com/slimkit/plus/commit/77ed9a6))
* 支持验证码登录 ([8276e4a](https://github.com/slimkit/plus/commit/8276e4a))
* Add app configure path method ([1f44980](https://github.com/slimkit/plus/commit/1f44980))
* Add FPM docker image ([c47b08d](https://github.com/slimkit/plus/commit/c47b08d))



