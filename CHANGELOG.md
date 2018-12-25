## [2.1.4](https://github.com/slimkit/plus/compare/2.0.8...2.1.4) (2018-12-25)


### Bug Fixes

* 修复错误的控制器命名 ([7ce5496](https://github.com/slimkit/plus/commit/7ce5496))
* 修复功能错误提交远端，导致开源库功能丢失问题 ([cf83719](https://github.com/slimkit/plus/commit/cf83719))
* **核心:** 修复导出打赏清单存在圈子帖子打赏导致到处错误 fix [#497](https://github.com/slimkit/plus/issues/497) ([097e6fd](https://github.com/slimkit/plus/commit/097e6fd))
* **资讯:** 修复打赏清单列表来源为“资讯”出现错别字 fix [#498](https://github.com/slimkit/plus/issues/498) ([a8a4a00](https://github.com/slimkit/plus/commit/a8a4a00))
* **资讯:** 修复资讯后台筛选指定所属类别无效问题 fix[#495](https://github.com/slimkit/plus/issues/495) ([9e3078b](https://github.com/slimkit/plus/commit/9e3078b))



## [2.0.8](https://github.com/slimkit/plus/compare/2.1.2...2.0.8) (2018-12-03)


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



