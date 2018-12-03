## [2.1.3](https://github.com/slimkit/plus/compare/2.1.2...2.1.3) (2018-12-03)


### Bug Fixes

* 修复全局充值转账相关刷积分问题 ([5c390dd](https://github.com/slimkit/plus/commit/5c390dd)), closes [#448](https://github.com/slimkit/plus/issues/448)
* **动态:** 修复动态编辑接口报错 [#445](https://github.com/slimkit/plus/issues/445) [#433](https://github.com/slimkit/plus/issues/433) ([16f3fd9](https://github.com/slimkit/plus/commit/16f3fd9))
* **核心:** 修复 TS+ 本地文件存储失败后抛出异常参数错误 ([f4531c9](https://github.com/slimkit/plus/commit/f4531c9))
* **核心:** 修复 TS+ 获取未读数据接口报错 ([4cba2b3](https://github.com/slimkit/plus/commit/4cba2b3)), closes [#449](https://github.com/slimkit/plus/issues/449)
* **核心:** 修复动态话题与用户关联迁移错误导致无法重置数据库 ([f024c88](https://github.com/slimkit/plus/commit/f024c88))
* **核心:** 修复读取未读信息报错 ([aba55a6](https://github.com/slimkit/plus/commit/aba55a6)), closes [#457](https://github.com/slimkit/plus/issues/457)
* **核心:** 修复缓存缩略图在 Windows 环境下生成错误路径问题 ([c32ee17](https://github.com/slimkit/plus/commit/c32ee17)), closes [#452](https://github.com/slimkit/plus/issues/452)
* **核心:** 修复置顶通知消息处理错误 [#430](https://github.com/slimkit/plus/issues/430) ([ada0329](https://github.com/slimkit/plus/commit/ada0329))
* **后台:** 修复钱包管理提现设置获取错误 [#422](https://github.com/slimkit/plus/issues/422) ([15f9aae](https://github.com/slimkit/plus/commit/15f9aae))
* **后台:** 修复使用表前缀进行数据库迁移后查询认证管理报错 ([397d25c](https://github.com/slimkit/plus/commit/397d25c)), closes [#451](https://github.com/slimkit/plus/issues/451)
* **资讯:** 修复资讯后台弹窗被编辑器遮挡问题 ([597889a](https://github.com/slimkit/plus/commit/597889a)), closes [#453](https://github.com/slimkit/plus/issues/453)
* **资讯:** 修复资讯拒绝置顶申请服务器报 500 错误 ([9d5be5f](https://github.com/slimkit/plus/commit/9d5be5f)), closes [slimkit/plus#438](https://github.com/slimkit/plus/issues/438)
* 修复钱包相关接口全部无法认证问题 ([f2a91eb](https://github.com/slimkit/plus/commit/f2a91eb))
* **admin:** 管理员禁用某用户后，认证管理获取不到用户信息报错 ([5b4cb7a](https://github.com/slimkit/plus/commit/5b4cb7a))
* **Core:** 修复积分和钱包充值回掉地址错误 ([26bdd93](https://github.com/slimkit/plus/commit/26bdd93))
* **Docker:** 修复 TS+ Docker 入口文件判读路径错误导致永远都在执行块代码 ([f4523b2](https://github.com/slimkit/plus/commit/f4523b2)), closes [#447](https://github.com/slimkit/plus/issues/447)



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



