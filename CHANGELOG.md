<a name="2.0.1"></a>
## [2.0.1](https://github.com/slimkit/plus/compare/2.0.0...2.0.1) (2018-09-20)


### Bug Fixes

* **file-storage:** 修复正是环境下始终返回 Null ([856cad4](https://github.com/slimkit/plus/commit/856cad4))



<a name="2.0.0"></a>
# [2.0.0](https://github.com/slimkit/plus/compare/1.9.5...2.0.0) (2018-09-20)


### Bug Fixes

* **core:** 修复 `app:version` 命令被异常移除 ([1b84c09](https://github.com/slimkit/plus/commit/1b84c09))
* **core:** 修复默认欢迎页面读取用户头像错误 ([3af526c](https://github.com/slimkit/plus/commit/3af526c))
* **core:** 修复支付付费节点引入命名空间错误 ([0cc9fe4](https://github.com/slimkit/plus/commit/0cc9fe4))
* **core:** Fixed unique array, Associative to index ([d29919b](https://github.com/slimkit/plus/commit/d29919b))
* **feed:** 修复 At 内容不存在的情况下类型校验错误 ([28ea574](https://github.com/slimkit/plus/commit/28ea574))
* **feed:** 修复动态话题 logo 验证错误和读取错误 ([9e8260d](https://github.com/slimkit/plus/commit/9e8260d))
* **feed:** 修复今日动态排行时间错误，修改为零点为起点 ([ced9535](https://github.com/slimkit/plus/commit/ced9535)), closes [zhiyicx/thinksns-plus-android#2282](https://github.com/zhiyicx/thinksns-plus-android/issues/2282)
* **feeds:** 修复动态排行榜没有返回用户头像 ([33eda40](https://github.com/slimkit/plus/commit/33eda40))
* **feeds:** 修复获取审核动态列表返回动态资源错误 ([0008dd4](https://github.com/slimkit/plus/commit/0008dd4)), closes [zhiyicx/thinksns-plus-android#2365](https://github.com/zhiyicx/thinksns-plus-android/issues/2365)
* **file-storage:** 修复 阿里云 oss 命名空间大小写问题 ([cce922b](https://github.com/slimkit/plus/commit/cce922b))
* **file-storage:** 修复 FileMeta 不支持 json 序列化接口 ([566fffc](https://github.com/slimkit/plus/commit/566fffc)), closes [zhiyicx/thinksns-plus-android#2377](https://github.com/zhiyicx/thinksns-plus-android/issues/2377)
* **news:** 修复资讯排行榜无用户头像返回 ([2629ce3](https://github.com/slimkit/plus/commit/2629ce3)), closes [zhiyicx/thinksns-plus-android#2377](https://github.com/zhiyicx/thinksns-plus-android/issues/2377)
* **news:** 修复自己置顶自己的资讯会直接成功！ ([4870318](https://github.com/slimkit/plus/commit/4870318))
* **user:** 修复 At 我的获取最新预览消息用户名错为自身 ([33cefe3](https://github.com/slimkit/plus/commit/33cefe3)), closes [zhiyicx/thinksns-plus-android#2304](https://github.com/zhiyicx/thinksns-plus-android/issues/2304)


### Features

* 完成阿里云驱动 ([85de9c1](https://github.com/slimkit/plus/commit/85de9c1))
* 音乐 & 资讯评论增加 at 人功能 ([ae08fe4](https://github.com/slimkit/plus/commit/ae08fe4)), closes [#337](https://github.com/slimkit/plus/issues/337)
* **admin:** 添加存储设置文件 MIME 管理 ([7ddfc8a](https://github.com/slimkit/plus/commit/7ddfc8a))
* **admin:** 完成公开频道设置面板 ([70ad60e](https://github.com/slimkit/plus/commit/70ad60e))
* **admin:** 增加阿里云 OSS 文件系统设置面板 ([f06b755](https://github.com/slimkit/plus/commit/f06b755))
* **admin:** 增加本地文件系统设置面板 ([a1301ad](https://github.com/slimkit/plus/commit/a1301ad))
* **admin:** 增加存储设置，图片储存设置 ([4044ff3](https://github.com/slimkit/plus/commit/4044ff3))
* **admin:** 增加默认文件系统设置面板 ([cf9d2d5](https://github.com/slimkit/plus/commit/cf9d2d5))
* **admin:** 增加文件系统上传大小限制设置 ([1aa18bb](https://github.com/slimkit/plus/commit/1aa18bb))
* **core:** 模型类型标记类增加静态重载功能 ([1bd41e5](https://github.com/slimkit/plus/commit/1bd41e5))
* **core:** 完成本地直传方案本地驱动开发 ([69410ec](https://github.com/slimkit/plus/commit/69410ec))
* **core:** 未读消息列表返回三条语言消息( [#337](https://github.com/slimkit/plus/issues/337) ) ([aa020ec](https://github.com/slimkit/plus/commit/aa020ec))
* **core:** 新增一个一主双备缓存工具 ([c6a03ca](https://github.com/slimkit/plus/commit/c6a03ca)), closes [#351](https://github.com/slimkit/plus/issues/351)
* **core:** 增加 At 我的接口列表( [#337](https://github.com/slimkit/plus/issues/337) ) ([0b53c78](https://github.com/slimkit/plus/commit/0b53c78))
* **core:** 增加 At 消息基本代码 ([6056ba7](https://github.com/slimkit/plus/commit/6056ba7))
* **core:** 增加文件本地驱动，增加用户测试代码 ([42bf3c6](https://github.com/slimkit/plus/commit/42bf3c6))
* **core:** 增加新的文件直传文件系统，完成 local 驱动创建任务和上传文件 ([9c7eae8](https://github.com/slimkit/plus/commit/9c7eae8)), closes [#362](https://github.com/slimkit/plus/issues/362)
* **core:** Add list all comments API ([f4ecd94](https://github.com/slimkit/plus/commit/f4ecd94)), closes [#337](https://github.com/slimkit/plus/issues/337)
* **docs:** 增加文档 GH-Pages 发布脚本 ([cb03e07](https://github.com/slimkit/plus/commit/cb03e07))
* **feed:** ( [#343](https://github.com/slimkit/plus/issues/343) ) 动态支持转发功能 ([49362e3](https://github.com/slimkit/plus/commit/49362e3))
* **feed:** ( isses [#337](https://github.com/slimkit/plus/issues/337) ) 动态评论增加 at 人功能 ([dbb2034](https://github.com/slimkit/plus/commit/dbb2034))
* **feed:** 动态列表支持按照 ID 返回动态数据 ([1970bc7](https://github.com/slimkit/plus/commit/1970bc7))
* **feed:** 增加发布动态 At 好友功能 ([41df582](https://github.com/slimkit/plus/commit/41df582))
* **file-storage:** 阿里云 OSS 进行内外隔离，提高 50% 数据获取速度 ([ec47bc5](https://github.com/slimkit/plus/commit/ec47bc5))
* **file-storage:** 储存增加上传后的 node 验证规则 ([3fe06d7](https://github.com/slimkit/plus/commit/3fe06d7))
* **news:** ( [#337](https://github.com/slimkit/plus/issues/337) ) 资讯增加按照 ID 获取资讯列表 ([af990d5](https://github.com/slimkit/plus/commit/af990d5))
* **pay:** 支付节点需要进行密码输入 ([348f1b9](https://github.com/slimkit/plus/commit/348f1b9))
* **user:** 用户统计返回 at 的消息未读数 ([241fa40](https://github.com/slimkit/plus/commit/241fa40))
* **user:** 增加根据用户名获取用户资料 ([c00fe83](https://github.com/slimkit/plus/commit/c00fe83))



