## [2.0.8](https://github.com/slimkit/plus/compare/2.0.7...2.0.8) (2018-12-03)


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
* **Core:** 修复积分和钱包充值回掉地址错误 ([26bdd93](https://github.com/slimkit/plus/commit/26bdd93))
* **Docker:** 修复 TS+ Docker 入口文件判读路径错误导致永远都在执行块代码 ([f4523b2](https://github.com/slimkit/plus/commit/f4523b2)), closes [#447](https://github.com/slimkit/plus/issues/447)



## [2.0.7](https://github.com/slimkit/plus/compare/2.0.6...2.0.7) (2018-11-07)


### Bug Fixes

* 修复附近的人后台配置面板无法提交配置问题 ([c98a46f](https://github.com/slimkit/plus/commit/c98a46f)), closes [zhiyicx/plus-component-pc#1081](https://github.com/zhiyicx/plus-component-pc/issues/1081) [zhiyicx/plus-jiajialin#1](https://github.com/zhiyicx/plus-jiajialin/issues/1)
* **file-storage:** 本地驱动不支持视频在线播放问题 ([76ae3a8](https://github.com/slimkit/plus/commit/76ae3a8))
* **file-storage:** 修复本地存储系统不支持 GIF 图片 ([99e4e8a](https://github.com/slimkit/plus/commit/99e4e8a))
* **file-storage:** 增加存储系统兼容性，错误配置将不会导致全站崩溃 ([af821ff](https://github.com/slimkit/plus/commit/af821ff))
* **news:** 修复资讯前台投稿未做 Markdown 转换处理 ([0009250](https://github.com/slimkit/plus/commit/0009250))



