<a name="2.0.6"></a>
## [2.0.6](https://github.com/slimkit/plus/compare/2.0.5...2.0.6) (2018-10-29)


### Bug Fixes

* **动态:** 修复话题后台编辑有 logo 的话题时的判断逻辑错误 [#412](https://github.com/slimkit/plus/issues/412) ([c08f1cb](https://github.com/slimkit/plus/commit/c08f1cb))


### Features

* **file-storage:** 处理失败数据，则返回原始数据！ ([23a0c50](https://github.com/slimkit/plus/commit/23a0c50))



<a name="2.0.5"></a>
## [2.0.5](https://github.com/slimkit/plus/compare/2.0.4...2.0.5) (2018-10-22)


### Bug Fixes

* **feeds:** 减少列表查询时的事务处理，避免数据库死锁问题 ([f8cd28e](https://github.com/slimkit/plus/commit/f8cd28e)), closes [slimkit/plus#394](https://github.com/slimkit/plus/issues/394)
* **file-storage:** 增加兼容性，避免 headers 出现中文 ([3d6acbc](https://github.com/slimkit/plus/commit/3d6acbc))
* **news:** 修复撤销投稿 SQL 报错 ([312139c](https://github.com/slimkit/plus/commit/312139c)), closes [thinksns-plus-android/issues#2402](https://github.com/thinksns-plus-android/issues/issues/2402)
* **news:** 开发人员错误提交表迁移导致通过后无法删除资讯 ([5fa170e](https://github.com/slimkit/plus/commit/5fa170e)), closes [zhiyicx/thinksns-plus-android#2402](https://github.com/zhiyicx/thinksns-plus-android/issues/2402)



