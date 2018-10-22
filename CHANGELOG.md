<a name="2.0.5"></a>
## [2.0.5](https://github.com/slimkit/plus/compare/2.0.4...2.0.5) (2018-10-22)


### Bug Fixes

* **feeds:** 减少列表查询时的事务处理，避免数据库死锁问题 ([f8cd28e](https://github.com/slimkit/plus/commit/f8cd28e)), closes [slimkit/plus#394](https://github.com/slimkit/plus/issues/394)
* **file-storage:** 增加兼容性，避免 headers 出现中文 ([3d6acbc](https://github.com/slimkit/plus/commit/3d6acbc))
* **news:** 修复撤销投稿 SQL 报错 ([312139c](https://github.com/slimkit/plus/commit/312139c)), closes [thinksns-plus-android/issues#2402](https://github.com/thinksns-plus-android/issues/issues/2402)
* **news:** 开发人员错误提交表迁移导致通过后无法删除资讯 ([5fa170e](https://github.com/slimkit/plus/commit/5fa170e)), closes [zhiyicx/thinksns-plus-android#2402](https://github.com/zhiyicx/thinksns-plus-android/issues/2402)



<a name="2.0.4"></a>
## [2.0.4](https://github.com/slimkit/plus/compare/2.0.3...2.0.4) (2018-09-29)


### Bug Fixes

* 修复客户端屏蔽原始图像名称无法获取文件后缀情况下报错 ([40dc20a](https://github.com/slimkit/plus/commit/40dc20a))


### Features

* 开启支付需要输入用户密码 ([2f71ad5](https://github.com/slimkit/plus/commit/2f71ad5))
* **file-storage:** 文件系统新增文件名称格式限制，避免错误上传 ([4a5364a](https://github.com/slimkit/plus/commit/4a5364a))



