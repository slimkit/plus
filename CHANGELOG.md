<a name="2.1.0"></a>
# [2.1.0](https://github.com/slimkit/plus/compare/2.0.4...2.1.0) (2018-10-22)


### Bug Fixes

* 修复核心加载中间件错误 ([55ad69e](https://github.com/slimkit/plus/commit/55ad69e))
* **feeds:** 减少列表查询时的事务处理，避免数据库死锁问题 ([f8cd28e](https://github.com/slimkit/plus/commit/f8cd28e)), closes [slimkit/plus#394](https://github.com/slimkit/plus/issues/394)
* **file-storage:** 增加兼容性，避免 headers 出现中文 ([3d6acbc](https://github.com/slimkit/plus/commit/3d6acbc))
* **news:** 修复投稿时输入错误密码依然能够投稿成功！ ([c64e400](https://github.com/slimkit/plus/commit/c64e400)), closes [zhiyicx/thinksns-plus-android#2396](https://github.com/zhiyicx/thinksns-plus-android/issues/2396) [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **news:** 修复撤销投稿 SQL 报错 ([312139c](https://github.com/slimkit/plus/commit/312139c)), closes [thinksns-plus-android/issues#2402](https://github.com/thinksns-plus-android/issues/issues/2402)
* **news:** 开发人员错误提交表迁移导致通过后无法删除资讯 ([5fa170e](https://github.com/slimkit/plus/commit/5fa170e)), closes [zhiyicx/thinksns-plus-android#2402](https://github.com/zhiyicx/thinksns-plus-android/issues/2402)


### Features

* 启动接口返回是否需要用户输入支付时密码标识 ([058c852](https://github.com/slimkit/plus/commit/058c852)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* 增加支付节点验证用户密码 ([a50d0a4](https://github.com/slimkit/plus/commit/a50d0a4)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* 新增打赏用户需要验证用户密码 ([e873876](https://github.com/slimkit/plus/commit/e873876)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* 验证用户密码中间件允许后台开关 ([7ba0df1](https://github.com/slimkit/plus/commit/7ba0df1))
* **admin:** 增加验证用户支付时密码开关功能 ([02d8ec0](https://github.com/slimkit/plus/commit/02d8ec0)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **core:** 核心 FileWith 允许批量复制属性 ([ad2ff46](https://github.com/slimkit/plus/commit/ad2ff46))
* **feeds:** 增加动态和动态评论置顶申请验证用户密码 ([0a219db](https://github.com/slimkit/plus/commit/0a219db))
* **feeds:** 增加打赏动态验证用户密码 ([b82139c](https://github.com/slimkit/plus/commit/b82139c)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **news:** 增加资讯和资讯评论申请置顶验证用户密码 ([8b89b39](https://github.com/slimkit/plus/commit/8b89b39)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **news:** 增加资讯打赏验证密码 ([885dbf1](https://github.com/slimkit/plus/commit/885dbf1))
* **news:** 增加资讯投稿验证用户密码 ([f7b3f9b](https://github.com/slimkit/plus/commit/f7b3f9b)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)



<a name="2.0.4"></a>
## [2.0.4](https://github.com/slimkit/plus/compare/2.0.3...2.0.4) (2018-09-29)


### Bug Fixes

* 修复客户端屏蔽原始图像名称无法获取文件后缀情况下报错 ([40dc20a](https://github.com/slimkit/plus/commit/40dc20a))


### Features

* 开启支付需要输入用户密码 ([2f71ad5](https://github.com/slimkit/plus/commit/2f71ad5))
* **file-storage:** 文件系统新增文件名称格式限制，避免错误上传 ([4a5364a](https://github.com/slimkit/plus/commit/4a5364a))



