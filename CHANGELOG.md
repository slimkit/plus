## [2.1.5](https://github.com/slimkit/plus/compare/2.1.4...2.1.5) (2019-01-29)


### Bug Fixes

* **动态:** 修复动态话题未关注状态下发布动态后无法关注该话题 ([86f5aaf](https://github.com/slimkit/plus/commit/86f5aaf)), closes [#579](https://github.com/slimkit/plus/issues/579)
* **CDN:** 修复阿里云获取原图 GIF 并没有返回原图问题 ([c13db22](https://github.com/slimkit/plus/commit/c13db22))



## [2.1.4](https://github.com/slimkit/plus/compare/2.0.8...2.1.4) (2018-12-25)


### Bug Fixes

* 修复错误的控制器命名 ([7ce5496](https://github.com/slimkit/plus/commit/7ce5496))
* 修复功能错误提交远端，导致开源库功能丢失问题 ([cf83719](https://github.com/slimkit/plus/commit/cf83719))
* **核心:** 修复导出打赏清单存在圈子帖子打赏导致到处错误 fix [#497](https://github.com/slimkit/plus/issues/497) ([097e6fd](https://github.com/slimkit/plus/commit/097e6fd))
* **资讯:** 修复打赏清单列表来源为“资讯”出现错别字 fix [#498](https://github.com/slimkit/plus/issues/498) ([a8a4a00](https://github.com/slimkit/plus/commit/a8a4a00))
* **资讯:** 修复资讯后台筛选指定所属类别无效问题 fix[#495](https://github.com/slimkit/plus/issues/495) ([9e3078b](https://github.com/slimkit/plus/commit/9e3078b))
* **admin:** 管理员禁用某用户后，认证管理获取不到用户信息报错 ([5b4cb7a](https://github.com/slimkit/plus/commit/5b4cb7a))



## [2.1.2](https://github.com/slimkit/plus/compare/2.0.7...2.1.2) (2018-11-07)



# [2.1.0](https://github.com/slimkit/plus/compare/2.0.4...2.1.0) (2018-10-22)


### Bug Fixes

* 修复核心加载中间件错误 ([55ad69e](https://github.com/slimkit/plus/commit/55ad69e))
* **news:** 修复投稿时输入错误密码依然能够投稿成功！ ([c64e400](https://github.com/slimkit/plus/commit/c64e400)), closes [zhiyicx/thinksns-plus-android#2396](https://github.com/zhiyicx/thinksns-plus-android/issues/2396) [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)


### Features

* 启动接口返回是否需要用户输入支付时密码标识 ([058c852](https://github.com/slimkit/plus/commit/058c852)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* 新增打赏用户需要验证用户密码 ([e873876](https://github.com/slimkit/plus/commit/e873876)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* 验证用户密码中间件允许后台开关 ([7ba0df1](https://github.com/slimkit/plus/commit/7ba0df1))
* 增加支付节点验证用户密码 ([a50d0a4](https://github.com/slimkit/plus/commit/a50d0a4)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **admin:** 增加验证用户支付时密码开关功能 ([02d8ec0](https://github.com/slimkit/plus/commit/02d8ec0)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **core:** 核心 FileWith 允许批量复制属性 ([ad2ff46](https://github.com/slimkit/plus/commit/ad2ff46))
* **feeds:** 增加打赏动态验证用户密码 ([b82139c](https://github.com/slimkit/plus/commit/b82139c)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **feeds:** 增加动态和动态评论置顶申请验证用户密码 ([0a219db](https://github.com/slimkit/plus/commit/0a219db))
* **news:** 增加资讯打赏验证密码 ([885dbf1](https://github.com/slimkit/plus/commit/885dbf1))
* **news:** 增加资讯和资讯评论申请置顶验证用户密码 ([8b89b39](https://github.com/slimkit/plus/commit/8b89b39)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)
* **news:** 增加资讯投稿验证用户密码 ([f7b3f9b](https://github.com/slimkit/plus/commit/f7b3f9b)), closes [zhiyicx/thinksns-plus-android#2390](https://github.com/zhiyicx/thinksns-plus-android/issues/2390)



