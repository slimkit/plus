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



