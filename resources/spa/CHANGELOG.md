<a name="4.1.2"></a>
## [4.1.2](https://github.com/slimkit/plus-small-screen-client/compare/v4.1.1...v4.1.2) (2018-11-09)


### Bug Fixes

* ([#598](https://github.com/slimkit/plus-small-screen-client/issues/598)) 发送验证码时不弹出顶部弹框 ([57ccb77](https://github.com/slimkit/plus-small-screen-client/commit/57ccb77))
* 修复时间解析不正确的问题 ([7839d6a](https://github.com/slimkit/plus-small-screen-client/commit/7839d6a))
* 兼容 safari 不支持解析标准的祖鲁时间格式 ([95cbad2](https://github.com/slimkit/plus-small-screen-client/commit/95cbad2))
* **auth:** 退出登录后不提示请登录 ([1fca3a4](https://github.com/slimkit/plus-small-screen-client/commit/1fca3a4))
* **comment:** 解决在iOS下评论输入框异常的问题 ([f92ebec](https://github.com/slimkit/plus-small-screen-client/commit/f92ebec))
* **location:** 修复直辖市不能正常显示定位的问题 ([c606e8e](https://github.com/slimkit/plus-small-screen-client/commit/c606e8e))
* **post:** ([#607](https://github.com/slimkit/plus-small-screen-client/issues/607)) 发布动态后跳转到最新动态列表并刷新 ([f846a74](https://github.com/slimkit/plus-small-screen-client/commit/f846a74))
* **post:** ([#607](https://github.com/slimkit/plus-small-screen-client/issues/607)) 发布动态成功后跳转到动态详情页 ([094705b](https://github.com/slimkit/plus-small-screen-client/commit/094705b))
* **post:** 修复发布动态时没有权限的报错信息 ([1ebe50b](https://github.com/slimkit/plus-small-screen-client/commit/1ebe50b))
* **question:** ([#598](https://github.com/slimkit/plus-small-screen-client/issues/598)) 自动补全问题末尾的问号 ([23b6d15](https://github.com/slimkit/plus-small-screen-client/commit/23b6d15))
* **question:** 问题时间显示为创建时间而非更新时间 ([29930e2](https://github.com/slimkit/plus-small-screen-client/commit/29930e2))
* **signin:** ([#605](https://github.com/slimkit/plus-small-screen-client/issues/605)) 后台配置只有手机注册时前端不生效的问题 ([001144b](https://github.com/slimkit/plus-small-screen-client/commit/001144b))
* **signin:** 修复微信登陆不能的问题 ([3c5f696](https://github.com/slimkit/plus-small-screen-client/commit/3c5f696))
* **signin:** 修复微信登陆不能的问题 ([58733ad](https://github.com/slimkit/plus-small-screen-client/commit/58733ad))
* **signin:** 验证码登录点击报错的问题 ([81267fb](https://github.com/slimkit/plus-small-screen-client/commit/81267fb))
* **user:** 修改个人资料只更换头像时提交按钮依然不可用 ([55cc62c](https://github.com/slimkit/plus-small-screen-client/commit/55cc62c))
* **wallet:** 修复充值成功后回掉地址跳到错误页面的问题 ([e21e1d1](https://github.com/slimkit/plus-small-screen-client/commit/e21e1d1))



<a name="4.1.1"></a>
## [4.1.1](https://github.com/slimkit/plus-small-screen-client/compare/v4.1.0...v4.1.1) (2018-10-25)


### Bug Fixes

* ([#545](https://github.com/slimkit/plus-small-screen-client/issues/545)) 从每日签到进入个人主页后再返回，签到弹框消失的问题 ([a635af6](https://github.com/slimkit/plus-small-screen-client/commit/a635af6))
* ([#560](https://github.com/slimkit/plus-small-screen-client/issues/560)) 修复 github-markdown-css 的默认字体样式在生产环境下覆盖了自定义样式的问题 ([db6f063](https://github.com/slimkit/plus-small-screen-client/commit/db6f063))
* **comment:** ([#556](https://github.com/slimkit/plus-small-screen-client/issues/556)) 评论列表当评论很长时不显示更多按钮 ([ef2eeef](https://github.com/slimkit/plus-small-screen-client/commit/ef2eeef))
* **comment:** ([#557](https://github.com/slimkit/plus-small-screen-client/issues/557)) 全局评论超过4行时只显示4行 ([07cc242](https://github.com/slimkit/plus-small-screen-client/commit/07cc242))
* **feed:** ([#537](https://github.com/slimkit/plus-small-screen-client/issues/537)) 动态详情没有评论时显示图片占位符 ([536797a](https://github.com/slimkit/plus-small-screen-client/commit/536797a))
* **find:** ([#576](https://github.com/slimkit/plus-small-screen-client/issues/576)) 推荐列表没有获取与用户相关标签的推荐 ([bf4827d](https://github.com/slimkit/plus-small-screen-client/commit/bf4827d))
* **find:** ([#577](https://github.com/slimkit/plus-small-screen-client/issues/577)) 定位搜索市级区域时显示到市级区域 ([29ed9cb](https://github.com/slimkit/plus-small-screen-client/commit/29ed9cb))
* **question:** ([#565](https://github.com/slimkit/plus-small-screen-client/issues/565)) 提问时不能选择重复的话题 ([8fcd742](https://github.com/slimkit/plus-small-screen-client/commit/8fcd742))
* **share:** ([#579](https://github.com/slimkit/plus-small-screen-client/issues/579)) 在iOS的内置微信浏览器中分享出去的url地址不正确的问题 ([dd40151](https://github.com/slimkit/plus-small-screen-client/commit/dd40151))
* **tag:** ([#534](https://github.com/slimkit/plus-small-screen-client/issues/534)) 选择标签时追加已存在标签则不取消选择 ([2e17187](https://github.com/slimkit/plus-small-screen-client/commit/2e17187))
* **user:** ([#548](https://github.com/slimkit/plus-small-screen-client/issues/548)) 如果个人信息未变更，则完成按钮不可点 ([98f3113](https://github.com/slimkit/plus-small-screen-client/commit/98f3113))



<a name="4.1.0"></a>
# [4.1.0](https://github.com/slimkit/plus-small-screen-client/compare/v4.0.2...v4.1.0) (2018-10-19)


### Bug Fixes

* ([#523](https://github.com/slimkit/plus-small-screen-client/issues/523)) 全局 markdown 格式文章样式不正确渲染的问题 ([b4f6fdd](https://github.com/slimkit/plus-small-screen-client/commit/b4f6fdd))
* ([#555](https://github.com/slimkit/plus-small-screen-client/issues/555)) 全局评论如果输入空格还是能提交的问题 ([1439dfb](https://github.com/slimkit/plus-small-screen-client/commit/1439dfb))
* ([#559](https://github.com/slimkit/plus-small-screen-client/issues/559)) 又双叒叕调整全局时间显示，与app同步显示 ([4484657](https://github.com/slimkit/plus-small-screen-client/commit/4484657))
* 修复 file 接口下图片宽高大于 4096 会报错的问题 ([0684f8d](https://github.com/slimkit/plus-small-screen-client/commit/0684f8d))
* 入口页面修改为动态列表页面 ([0043461](https://github.com/slimkit/plus-small-screen-client/commit/0043461))
* **certification:** ([#552](https://github.com/slimkit/plus-small-screen-client/issues/552)) 企业认证被驳回后重新认证进入了个人认证的页面的问题 ([b775110](https://github.com/slimkit/plus-small-screen-client/commit/b775110))
* **certification:** ([#554](https://github.com/slimkit/plus-small-screen-client/issues/554)) 认证页面字数限制不符的问题 ([dbd426b](https://github.com/slimkit/plus-small-screen-client/commit/dbd426b))
* **currency:** ([#586](https://github.com/slimkit/plus-small-screen-client/issues/586)) 积分提现时若积分不足则前往充值 ([ff44e94](https://github.com/slimkit/plus-small-screen-client/commit/ff44e94))
* **group:** ([#578](https://github.com/slimkit/plus-small-screen-client/issues/578)) 用户若在审核中，在资讯列表页点击投稿会跳到认证表单页面的问题 ([3c6fdb4](https://github.com/slimkit/plus-small-screen-client/commit/3c6fdb4))
* **group:** ([#581](https://github.com/slimkit/plus-small-screen-client/issues/581)) 管理员置顶帖子时仍有密码确认框弹出的问题 ([2c62828](https://github.com/slimkit/plus-small-screen-client/commit/2c62828))
* **news:** ([#573](https://github.com/slimkit/plus-small-screen-client/issues/573)) 投稿时摘要仍可超过200字 ([f69348d](https://github.com/slimkit/plus-small-screen-client/commit/f69348d))
* **pay:** ([#582](https://github.com/slimkit/plus-small-screen-client/issues/582)) 在圈子列表上加入需要付费的圈子没有弹出密码输入框的问题 ([b08543c](https://github.com/slimkit/plus-small-screen-client/commit/b08543c))
* **pay:** ([#583](https://github.com/slimkit/plus-small-screen-client/issues/583)) 支付积分前先检查积分余额是否充足 ([53c95aa](https://github.com/slimkit/plus-small-screen-client/commit/53c95aa))
* **pay:** ([#585](https://github.com/slimkit/plus-small-screen-client/issues/585)) 点击忘记密码没有跳转到找回密码页面的问题 ([88f978d](https://github.com/slimkit/plus-small-screen-client/commit/88f978d))
* **pay:** ([#591](https://github.com/slimkit/plus-small-screen-client/issues/591)) 购买付费动态输入正确的密码也提示密码错误的问题 ([9c3c0ba](https://github.com/slimkit/plus-small-screen-client/commit/9c3c0ba))
* **pay:** ([#593](https://github.com/slimkit/plus-small-screen-client/issues/593)) 点击无需付费的动态却显示了付费信息的问题 ([214ba05](https://github.com/slimkit/plus-small-screen-client/commit/214ba05))
* **post:** ([#527](https://github.com/slimkit/plus-small-screen-client/issues/527), [#543](https://github.com/slimkit/plus-small-screen-client/issues/543)) 关闭发布动态付费开关后发布图片仍然有付费开关的问题 ([9090730](https://github.com/slimkit/plus-small-screen-client/commit/9090730))
* **question:** ([#562](https://github.com/slimkit/plus-small-screen-client/issues/562)) 搜索问题时排序方法不对的问题 ([89cdb47](https://github.com/slimkit/plus-small-screen-client/commit/89cdb47))
* **question:** ([#564](https://github.com/slimkit/plus-small-screen-client/issues/564)) 发布问题标题字数限制为51字（包含问号） ([cbec5be](https://github.com/slimkit/plus-small-screen-client/commit/cbec5be))
* **question:** ([#568](https://github.com/slimkit/plus-small-screen-client/issues/568)) 点击分享提示错误文案的问题 ([80da4f9](https://github.com/slimkit/plus-small-screen-client/commit/80da4f9))
* **question:** ([#569](https://github.com/slimkit/plus-small-screen-client/issues/569)) 无法进入点赞列表的问题 ([8a4ba1d](https://github.com/slimkit/plus-small-screen-client/commit/8a4ba1d))
* **question:** ([#571](https://github.com/slimkit/plus-small-screen-client/issues/571)) 问题详情页没有显示邀请回答和已采纳的回答 ([9cc6039](https://github.com/slimkit/plus-small-screen-client/commit/9cc6039))
* **question:** ([#572](https://github.com/slimkit/plus-small-screen-client/issues/572)) 已邀请悬赏的问题在问题详情页显示错误的问题 ([cba0cbe](https://github.com/slimkit/plus-small-screen-client/commit/cba0cbe))
* **question:** 当回答没有人点赞时回答时间左移的问题 ([229be62](https://github.com/slimkit/plus-small-screen-client/commit/229be62))
* **question:** 精品问题在精选标签页下隐藏小图标 ([585840d](https://github.com/slimkit/plus-small-screen-client/commit/585840d))
* **signin:** ([#529](https://github.com/slimkit/plus-small-screen-client/issues/529)) 注册时验证码弹出错误信息 ([b85f963](https://github.com/slimkit/plus-small-screen-client/commit/b85f963))
* **signin:** ([#580](https://github.com/slimkit/plus-small-screen-client/issues/580)) 登陆验证码接口修正 ([8c3e430](https://github.com/slimkit/plus-small-screen-client/commit/8c3e430))
* **user:** ([#558](https://github.com/slimkit/plus-small-screen-client/issues/558)) 游客模式不可在资讯列表选择资讯过滤器 ([1cca415](https://github.com/slimkit/plus-small-screen-client/commit/1cca415))
* **user:** ([#558](https://github.com/slimkit/plus-small-screen-client/issues/558)) 游客模式可进入找人模块,不可进入他人个人主页、动态详情、资讯详情 ([7973952](https://github.com/slimkit/plus-small-screen-client/commit/7973952))
* **wallet:** 修复没有设置钱包推荐充值金额时进入充值页面报错的问题 ([b2d4e7b](https://github.com/slimkit/plus-small-screen-client/commit/b2d4e7b))


### Features

* **component:** 密码二次确认组件 ([7e6f936](https://github.com/slimkit/plus-small-screen-client/commit/7e6f936)), closes [#540](https://github.com/slimkit/plus-small-screen-client/issues/540)
* **currency:** ([#590](https://github.com/slimkit/plus-small-screen-client/issues/590)) 积分名称替换为服务器配置的积分名称 ([3550b6d](https://github.com/slimkit/plus-small-screen-client/commit/3550b6d))
* **group:** ([#540](https://github.com/slimkit/plus-small-screen-client/issues/540)) 加入付费圈子密码确认 ([75d6a35](https://github.com/slimkit/plus-small-screen-client/commit/75d6a35))
* **news:** ([#540](https://github.com/slimkit/plus-small-screen-client/issues/540)) 资讯投稿付费密码确认 ([3f510b3](https://github.com/slimkit/plus-small-screen-client/commit/3f510b3))
* **pay:** ([#540](https://github.com/slimkit/plus-small-screen-client/issues/540)) 全局打赏密码确认 ([028bbf7](https://github.com/slimkit/plus-small-screen-client/commit/028bbf7))
* **pay:** ([#540](https://github.com/slimkit/plus-small-screen-client/issues/540)) 全局申请置顶密码确认 ([16a8f15](https://github.com/slimkit/plus-small-screen-client/commit/16a8f15))
* **pay:** ([#540](https://github.com/slimkit/plus-small-screen-client/issues/540)) 关闭支付验证开关时不显示密码确认弹框 ([f4d02f8](https://github.com/slimkit/plus-small-screen-client/commit/f4d02f8))
* **question:** ([#561](https://github.com/slimkit/plus-small-screen-client/issues/561)) 问答列表右下角增加提问入口 ([63a60e0](https://github.com/slimkit/plus-small-screen-client/commit/63a60e0))
* **question:** ([#568](https://github.com/slimkit/plus-small-screen-client/issues/568)) 收藏回答功能 ([59fc3e3](https://github.com/slimkit/plus-small-screen-client/commit/59fc3e3))
* **signin:** ([#580](https://github.com/slimkit/plus-small-screen-client/issues/580)) 手机验证码登陆 ([79a47dc](https://github.com/slimkit/plus-small-screen-client/commit/79a47dc))
* **signin:** 未注册过的用户也可以一键登录了 ([a2cc9ce](https://github.com/slimkit/plus-small-screen-client/commit/a2cc9ce))



<a name="4.0.2"></a>
## [4.0.2](https://github.com/slimkit/plus-small-screen-client/compare/v4.0.1...v4.0.2) (2018-10-12)


### Features

* **group:** ([#506](https://github.com/slimkit/plus-small-screen-client/issues/506)) 现在圈子管理员可以删除圈子的帖子了 ([297be76](https://github.com/slimkit/plus-small-screen-client/commit/297be76))
* **group:** ([#525](https://github.com/slimkit/plus-small-screen-client/issues/525)) 圈子管理员可以将成员加入或移出黑名单了 ([9c3f736](https://github.com/slimkit/plus-small-screen-client/commit/9c3f736))
* **group:** ([#525](https://github.com/slimkit/plus-small-screen-client/issues/525)) 圈子黑名单列表以及入口 ([6823a68](https://github.com/slimkit/plus-small-screen-client/commit/6823a68))


### Bug Fixes

* ([#527](https://github.com/slimkit/plus-small-screen-client/issues/527)) 后台关闭付费动态选项后发表图片动态仍显示付费开关的问题 ([087fc80](https://github.com/slimkit/plus-small-screen-client/commit/087fc80))
* 修复上传文件第三方请求携带脏参数的问题 😫 ([2073b85](https://github.com/slimkit/plus-small-screen-client/commit/2073b85))
* 修正 api limit 包引用地址 ([ff798d6](https://github.com/slimkit/plus-small-screen-client/commit/ff798d6))
* 非标准祖鲁时间修正 ([8682ecf](https://github.com/slimkit/plus-small-screen-client/commit/8682ecf))
* **certificate:** 身份证号码正则覆盖不全的问题 ([d473144](https://github.com/slimkit/plus-small-screen-client/commit/d473144))
* **certification:** ([#550](https://github.com/slimkit/plus-small-screen-client/issues/550)) 认证被驳回后显示的title应该为相应的认证类型 ([c6c3003](https://github.com/slimkit/plus-small-screen-client/commit/c6c3003))
* **component:** 修复自适应文本框高度问题 ([658be4d](https://github.com/slimkit/plus-small-screen-client/commit/658be4d))
* **feed:** ([#528](https://github.com/slimkit/plus-small-screen-client/issues/528)) 取消动态详情页缓存 ([d0419c7](https://github.com/slimkit/plus-small-screen-client/commit/d0419c7))
* **find:** 当附近没有用户时服务器返回错误的问题 ([45cf5fd](https://github.com/slimkit/plus-small-screen-client/commit/45cf5fd))
* **group:** ([#526](https://github.com/slimkit/plus-small-screen-client/issues/526)) 圈外搜索时如果没有帖子则引导去发帖 ([29043b3](https://github.com/slimkit/plus-small-screen-client/commit/29043b3))
* **message:** 头像资源修正 x4 ([8d1d55c](https://github.com/slimkit/plus-small-screen-client/commit/8d1d55c))
* **news:** 资讯列表切换时增加加载动画 ([7145c1e](https://github.com/slimkit/plus-small-screen-client/commit/7145c1e))
* **password:** ([#531](https://github.com/slimkit/plus-small-screen-client/issues/531)) 修改密码时如果密码长度为16位无法修改成功的问题 ([7befebe](https://github.com/slimkit/plus-small-screen-client/commit/7befebe))
* **post:** 修复发布文字动态不能的问题 ([7960ea5](https://github.com/slimkit/plus-small-screen-client/commit/7960ea5))
* **register:** ([#532](https://github.com/slimkit/plus-small-screen-client/issues/532)) 修复注册时如果输入了手机号然后切换到邮箱注册，未输入邮箱也能点击注册的问题 ([370ee61](https://github.com/slimkit/plus-small-screen-client/commit/370ee61))
* **setting:** 修复 about 页面根据不同的信息响应不同的行为 ([f7363aa](https://github.com/slimkit/plus-small-screen-client/commit/f7363aa))


### BREAKING CHANGES

* 移除冗余的api封装，只保留一个 ([2503560](https://github.com/slimkit/plus-small-screen-client/commit/2503560))



<a name="4.0.1"></a>
## [4.0.1](https://github.com/slimkit/plus-small-screen-client/compare/v4.0.0...v4.0.1) (2018-09-27)


### Bug Fixes

* **avatar:** 头像资源修正 ([e1e10e4](https://github.com/slimkit/plus-small-screen-client/commit/e1e10e4))
* **component:** 修复输入组件多行文本框嵌套v-model报错的问题 ([6ac9698](https://github.com/slimkit/plus-small-screen-client/commit/6ac9698))
* **group:** ([#516](https://github.com/slimkit/plus-small-screen-client/issues/516), [#517](https://github.com/slimkit/plus-small-screen-client/issues/517)) 管理员可以置顶帖子和撤销置顶帖子了 ([e2c70d8](https://github.com/slimkit/plus-small-screen-client/commit/e2c70d8))
* **group:** ([#516](https://github.com/slimkit/plus-small-screen-client/issues/516)) 已经置顶的帖子在详情页不再显示申请置顶了 ([f8c5ffc](https://github.com/slimkit/plus-small-screen-client/commit/f8c5ffc))
* **group:** ([#518](https://github.com/slimkit/plus-small-screen-client/issues/518)) 修复圈子外发帖时报错的问题 ([06adedc](https://github.com/slimkit/plus-small-screen-client/commit/06adedc))
* **question:** ([#518](https://github.com/slimkit/plus-small-screen-client/issues/518)) 搜索问题时结果不全的问题 ([cb6886e](https://github.com/slimkit/plus-small-screen-client/commit/cb6886e))
* **question:** 发布问题时增加重复提交拦截 ([385f722](https://github.com/slimkit/plus-small-screen-client/commit/385f722))
* **question:** 回答详情页面报错的问题 ([fc01ecf](https://github.com/slimkit/plus-small-screen-client/commit/fc01ecf))



<a name="4.0.0"></a>
# [4.0.0](https://github.com/slimkit/plus-small-screen-client/compare/v4.0.0-rc.9...v4.0.0) (2018-09-20)


### Features

* **group:** 圈子相关功能
* **question:** 问答相关功能
* **news:** 收藏资讯 ([6ff3269](https://github.com/slimkit/plus-small-screen-client/commit/6ff3269))
* **collect:** ([#511](https://github.com/slimkit/plus-small-screen-client/issues/511)) 增加我的收藏-资讯列表 ([14aa4b2](https://github.com/slimkit/plus-small-screen-client/commit/14aa4b2))


### Bug Fixes

* 修复 safari 下不支持 Date 构造非常规时间字符串的问题 ([36c640a](https://github.com/slimkit/plus-small-screen-client/commit/36c640a))
* 修复帖子发帖时间显示有时差的问题 ([4c13a20](https://github.com/slimkit/plus-small-screen-client/commit/4c13a20))
* 新头像资源更新 ([bbd5a87](https://github.com/slimkit/plus-small-screen-client/commit/bbd5a87))
* 新版本文件上传头像路径适应调整 ([f61fe74](https://github.com/slimkit/plus-small-screen-client/commit/f61fe74))
* **certificate:** ([#513](https://github.com/slimkit/plus-small-screen-client/issues/513)) 未认证用户点击认证时页面报错 ([e85720a](https://github.com/slimkit/plus-small-screen-client/commit/e85720a))
* **chat:** 修复聊天窗口头像没有对齐的问题 ([3f9d0a3](https://github.com/slimkit/plus-small-screen-client/commit/3f9d0a3))
* **chat:** 聊天时间显示NaN的问题 ([79ed76a](https://github.com/slimkit/plus-small-screen-client/commit/79ed76a))
* **collection:** 解决进入收藏详情页再返回收藏列表时页面报错的问题 ([b75f3f0](https://github.com/slimkit/plus-small-screen-client/commit/b75f3f0))
* **feed:** ([#463](https://github.com/slimkit/plus-small-screen-client/issues/463)) 修复(hack)在某些情况下动态列表接口被异常重复加载的问题 ([fe1d4fc](https://github.com/slimkit/plus-small-screen-client/commit/fe1d4fc))
* **feed:** ([#463](https://github.com/slimkit/plus-small-screen-client/issues/463)) 修复动态列表接口加载异常的问题 ([92cc844](https://github.com/slimkit/plus-small-screen-client/commit/92cc844))
* **feed:** 在动态详情页下的关注按钮失效的问题 ([7702f77](https://github.com/slimkit/plus-small-screen-client/commit/7702f77))
* **feed:** 热门动态加载更多接口修正 ([35038ff](https://github.com/slimkit/plus-small-screen-client/commit/35038ff))
* **location:** 定位精度显示到城市 ([e590ba1](https://github.com/slimkit/plus-small-screen-client/commit/e590ba1))
* **news:** 修复资讯列表加载卡片广告时偶尔复现的重复加载问题 ([7f03897](https://github.com/slimkit/plus-small-screen-client/commit/7f03897))
* **news:** 编辑文章页面 title 更正 ([0c4d004](https://github.com/slimkit/plus-small-screen-client/commit/0c4d004))
* **profile:** 修复个人主页报错的问题 ([23d1f9a](https://github.com/slimkit/plus-small-screen-client/commit/23d1f9a))
* **profile:** 修复动态评论置顶样式问题 ([76ef462](https://github.com/slimkit/plus-small-screen-client/commit/76ef462))
* **share:** H5 分享提示进行浏览器分享 ([7e0ba83](https://github.com/slimkit/plus-small-screen-client/commit/7e0ba83))
* **user:** ([#509](https://github.com/slimkit/plus-small-screen-client/issues/509)) 在别人的个人主页也能唤起更换 banner 的问题 ([c8823b4](https://github.com/slimkit/plus-small-screen-client/commit/c8823b4))
* **wallet, recharge:** 充值成功后可以正常的跳转回应用内了 :tada: ([d803af2](https://github.com/slimkit/plus-small-screen-client/commit/d803af2))

> 查看更多提交历史请[点击这里](https://github.com/slimkit/plus-small-screen-client/compare/v4.0.0-rc.9...v4.0.0)


<a name="4.0.0-rc.9"></a>
# [4.0.0-rc.9](https://github.com/slimkit/plus-small-screen-client/compare/v4.0.0-rc.8...v4.0.0-rc.9) (2018-08-03)


### Bug Fixes

* **certificate:** 发布帖子时如果用户处于正在审核的状态时不再跳转到认证页面了 ([c3a1193](https://github.com/slimkit/plus-small-screen-client/commit/c3a1193))
* **currency:** 充值成功后刷新当前积分 ([da32637](https://github.com/slimkit/plus-small-screen-client/commit/da32637))
* **currency:** 积分明细和充值提现详情显示的列表项目内容不同 ([711a231](https://github.com/slimkit/plus-small-screen-client/commit/711a231))
* **currency:** 积分明细详情超出范围后显示横向滚动条的问题 ([5a740fd](https://github.com/slimkit/plus-small-screen-client/commit/5a740fd))
* **filters:** 时间差过滤时无需再次计算时区 ([0469da3](https://github.com/slimkit/plus-small-screen-client/commit/0469da3))
* **filters:** 服务器返回的值为祖鲁时间，需要进行时间差转化 ([5df49ce](https://github.com/slimkit/plus-small-screen-client/commit/5df49ce))
* **group:** 获取圈子分类数据时机调整 ([902b6f5](https://github.com/slimkit/plus-small-screen-client/commit/902b6f5)), closes [#459](https://github.com/slimkit/plus-small-screen-client/issues/459)
* **question:** (close [#260](https://github.com/slimkit/plus-small-screen-client/issues/260)) 修复点击列表下的回答未进入回答详情 ([e0d205d](https://github.com/slimkit/plus-small-screen-client/commit/e0d205d))
* **question:** 修复问题详情页回答列表的答案空格被移除的问题 ([f71845c](https://github.com/slimkit/plus-small-screen-client/commit/f71845c), [8959c99](https://github.com/slimkit/plus-small-screen-client/commit/8959c99))
* **question:** 暂时移除问题详情页下方的工具栏 ([7feee93](https://github.com/slimkit/plus-small-screen-client/commit/7feee93))
* **register:** 修复注册页面在后台没有配置注册设置时打不开的问题 ([89b52e2](https://github.com/slimkit/plus-small-screen-client/commit/89b52e2), [016e00c](https://github.com/slimkit/plus-small-screen-client/commit/016e00c)), closes [#458](https://github.com/slimkit/plus-small-screen-client/issues/458)
* **signin:** 用户可以在登录页面使用回车键登录了 ([4336dc2](https://github.com/slimkit/plus-small-screen-client/commit/4336dc2))
* **topic:** (close [#154](https://github.com/slimkit/plus-small-screen-client/issues/154)) 专题列表未关注和已关注按钮宽度不一致的问题 ([e5e983f](https://github.com/slimkit/plus-small-screen-client/commit/e5e983f))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 充值金额单位换算的问题 ([10b9f61](https://github.com/slimkit/plus-small-screen-client/commit/10b9f61))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 发起提现请求api修正 ([2d5fee6](https://github.com/slimkit/plus-small-screen-client/commit/2d5fee6))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 提现明细内容修正 ([3edb55b](https://github.com/slimkit/plus-small-screen-client/commit/3edb55b))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 钱包流水页面不能正常显示提现的问题 ([3092cee](https://github.com/slimkit/plus-small-screen-client/commit/3092cee))


### Features

* **components:** 新增提示信息弹框组件 PopupDialog ([a20bc4a](https://github.com/slimkit/plus-small-screen-client/commit/a20bc4a))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 用户充值协议 ([c8b3485](https://github.com/slimkit/plus-small-screen-client/commit/c8b3485))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 积分充值功能 ([f6e8ad8](https://github.com/slimkit/plus-small-screen-client/commit/f6e8ad8))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 积分入口页 ([9ddbb5f](https://github.com/slimkit/plus-small-screen-client/commit/9ddbb5f))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 积分入口页广告位 ([35e59fc](https://github.com/slimkit/plus-small-screen-client/commit/35e59fc))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 积分发起充值请求 ([d82e924](https://github.com/slimkit/plus-small-screen-client/commit/d82e924))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 积分发起提现请求 ([b430695](https://github.com/slimkit/plus-small-screen-client/commit/b430695))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 积分提取规则 ([dbb1fca](https://github.com/slimkit/plus-small-screen-client/commit/dbb1fca))
* **currency:** [#457](https://github.com/slimkit/plus-small-screen-client/issues/457) 积分流水明细和积分充值提现记录 ([5b638bd](https://github.com/slimkit/plus-small-screen-client/commit/5b638bd))
* **question:** ([#461](https://github.com/slimkit/plus-small-screen-client/issues/461)) 添加回答功能 ([9b3f5cb](https://github.com/slimkit/plus-small-screen-client/commit/9b3f5cb))
* **question:** 问题详情页加入跳转到我的回答页面入口 ([39f4a33](https://github.com/slimkit/plus-small-screen-client/commit/39f4a33))
* **topic:** 话题 -> 专题 ([0efb730](https://github.com/slimkit/plus-small-screen-client/commit/0efb730))
* **wallet:** ([#456](https://github.com/slimkit/plus-small-screen-client/issues/456)) 增加账单详情和流水页面 ([dff2b5a](https://github.com/slimkit/plus-small-screen-client/commit/dff2b5a))
* **wallet:** ([#456](https://github.com/slimkit/plus-small-screen-client/issues/456)) 钱包页面入口 ([01525aa](https://github.com/slimkit/plus-small-screen-client/commit/01525aa))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 提现明细 ([4459180](https://github.com/slimkit/plus-small-screen-client/commit/4459180))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 跳转到第三方支付支付地址 ([b984e4c](https://github.com/slimkit/plus-small-screen-client/commit/b984e4c))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 钱包增加积分充值入口 ([d0c6c89](https://github.com/slimkit/plus-small-screen-client/commit/d0c6c89))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 钱包提现接口对接 ([c4962c9](https://github.com/slimkit/plus-small-screen-client/commit/c4962c9))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 钱包明细详情 ([4871d54](https://github.com/slimkit/plus-small-screen-client/commit/4871d54))
* **wallet:** [#456](https://github.com/slimkit/plus-small-screen-client/issues/456) 钱包账单详情 ([3816faf](https://github.com/slimkit/plus-small-screen-client/commit/3816faf))
* **wallet:** 充值页面 ([24ee3ea](https://github.com/slimkit/plus-small-screen-client/commit/24ee3ea))
* **wallet:** 钱包充值功能 ([efe7f28](https://github.com/slimkit/plus-small-screen-client/commit/efe7f28))
* **wallet:** 钱包明细页面 ([78d9bef](https://github.com/slimkit/plus-small-screen-client/commit/78d9bef))



<a name="4.0.0-rc.8"></a>
# [4.0.0-rc.8](https://github.com/zhiyicx/plus-component-h5/compare/v4.0.0-rc.7...v4.0.0-rc.8) (2018-07-16)


### Bug Fixes

* Fixed app keep alive invalid ([fb53892](https://github.com/zhiyicx/plus-component-h5/commit/fb53892))
* **components:** 修复下拉刷新字样位置 ([7e5e751](https://github.com/zhiyicx/plus-component-h5/commit/7e5e751))
* **components:** 修复单向数据绑定问题 ([fb42d42](https://github.com/zhiyicx/plus-component-h5/commit/fb42d42))
* **filters:** formatDate 方法支持字符串格式的时间了 ([d7aa648](https://github.com/zhiyicx/plus-component-h5/commit/d7aa648))
* **圈子:** 修复圈子帖子阅读数不正确的问题 ([4db33e2](https://github.com/zhiyicx/plus-component-h5/commit/4db33e2))
* **广告:** mutation 传参补充 ([27cb12f](https://github.com/zhiyicx/plus-component-h5/commit/27cb12f))
* ([#453](https://github.com/zhiyicx/plus-component-h5/issues/453)) 修复图片地址多处写死导致编译后无法正确查询的问题 ([a820663](https://github.com/zhiyicx/plus-component-h5/commit/a820663))
* **广告:** 伪修复 用户在首次进入应用时报错的问题 ([1c01e94](https://github.com/zhiyicx/plus-component-h5/commit/1c01e94))
* **广告:** 修复资讯列表加载更多时不能加载广告的问题 ([d51245c](https://github.com/zhiyicx/plus-component-h5/commit/d51245c))
* **广告:** 动态详情页顶部 banner 广告排序问题 ([fc88253](https://github.com/zhiyicx/plus-component-h5/commit/fc88253))
* **广告:** 简化广告位 store 内容，不缓存广告列表 ([140a927](https://github.com/zhiyicx/plus-component-h5/commit/140a927))
* **认证:** 优化重填表单的认证体验(图像加载部分) ([78812cf](https://github.com/zhiyicx/plus-component-h5/commit/78812cf))
* **认证:** 修复新用户认证不能的问题 ([d766767](https://github.com/zhiyicx/plus-component-h5/commit/d766767))
* **认证:** 修复认证状态偶尔获取不到的问题 ([6957eea](https://github.com/zhiyicx/plus-component-h5/commit/6957eea))
* **认证:** 创建投稿时如果需要认证则跳转至认证页面 ([e0a13b8](https://github.com/zhiyicx/plus-component-h5/commit/e0a13b8))
* **资讯:** 详情页底部移除多余的“加载更多”字样 ([597aabb](https://github.com/zhiyicx/plus-component-h5/commit/597aabb))


### Features

* Add .editorconfig ([ff9d0d9](https://github.com/zhiyicx/plus-component-h5/commit/ff9d0d9))
* **components:** 详情页广告位组件 ([44c1f69](https://github.com/zhiyicx/plus-component-h5/commit/44c1f69))
* **spa:** Support service worker ([7b8d123](https://github.com/zhiyicx/plus-component-h5/commit/7b8d123))
* **广告:** ([#454](https://github.com/zhiyicx/plus-component-h5/issues/454)) 动态列表随机插入模拟动态卡片广告 ([59be45e](https://github.com/zhiyicx/plus-component-h5/commit/59be45e))
* **广告:** ([#454](https://github.com/zhiyicx/plus-component-h5/issues/454)) 圈子帖子详情页广告位 ([5802b59](https://github.com/zhiyicx/plus-component-h5/commit/5802b59))
* **广告:** ([#454](https://github.com/zhiyicx/plus-component-h5/issues/454)) 圈子首页顶部广告位 ([5550515](https://github.com/zhiyicx/plus-component-h5/commit/5550515))
* **广告:** ([#454](https://github.com/zhiyicx/plus-component-h5/issues/454)) 资讯列表随机插入模拟卡片广告 ([b2a6f7b](https://github.com/zhiyicx/plus-component-h5/commit/b2a6f7b))
* **广告:** ([#454](https://github.com/zhiyicx/plus-component-h5/issues/454)) 资讯列表顶部 banner 广告 ([dfc93a2](https://github.com/zhiyicx/plus-component-h5/commit/dfc93a2))
* **广告:** ([#454](https://github.com/zhiyicx/plus-component-h5/issues/454)) 资讯详情页广告位 ([6ead189](https://github.com/zhiyicx/plus-component-h5/commit/6ead189))
* **广告:** 增加动态详情页广告位 ([a8a9fe0](https://github.com/zhiyicx/plus-component-h5/commit/a8a9fe0))
* **广告:** 增加热门动态顶部 banner 广告位 ([82a4920](https://github.com/zhiyicx/plus-component-h5/commit/82a4920))
* **广告:** 获取广告位信息 ([ae86b2e](https://github.com/zhiyicx/plus-component-h5/commit/ae86b2e))
* **认证:** 个人资料页认证状态更新 ([a2fe46d](https://github.com/zhiyicx/plus-component-h5/commit/a2fe46d))
* **认证:** 现在头像统一能显示小图标了 ([123596f](https://github.com/zhiyicx/plus-component-h5/commit/123596f))
* **认证:** 现在投稿前需要进行验证投稿权限才可继续操作 ([21ad733](https://github.com/zhiyicx/plus-component-h5/commit/21ad733))
* **认证:** 管理用户认证数据 ([886e245](https://github.com/zhiyicx/plus-component-h5/commit/886e245))
* **认证:** 被驳回的认证可继续填写 ([8e585be](https://github.com/zhiyicx/plus-component-h5/commit/8e585be))
* **认证:** 认证信息页面 ([dbd6001](https://github.com/zhiyicx/plus-component-h5/commit/dbd6001))
* **认证:** 认证表单页面 ([285cf1d](https://github.com/zhiyicx/plus-component-h5/commit/285cf1d))



<a name="4.0.0-rc.7"></a>
# [4.0.0-rc.7](https://github.com/zhiyicx/plus-component-h5/compare/v4.0.0-rc.6...v4.0.0-rc.7) (2018-07-06)


### Bug Fixes

* ([#295](https://github.com/zhiyicx/plus-component-h5/issues/295)) 圈子动态列表增加自己文章的删除和置顶功能 ([4525d94](https://github.com/zhiyicx/plus-component-h5/commit/4525d94))
* api 调用出错 ([3e9a586](https://github.com/zhiyicx/plus-component-h5/commit/3e9a586))
* 优化发布问题时检索列表的展示体验 ([b9b9e93](https://github.com/zhiyicx/plus-component-h5/commit/b9b9e93))
* 修复话题列表页文本过长导致的显示问题 ([2bfa575](https://github.com/zhiyicx/plus-component-h5/commit/2bfa575))
* 解决版本更新后 js 被缓存的问题 ([c4c5039](https://github.com/zhiyicx/plus-component-h5/commit/c4c5039))
* 话题详情页文本过长导致的排版问题 ([b20427b](https://github.com/zhiyicx/plus-component-h5/commit/b20427b))
* 问答话题不能收藏的问题 ([2f607ea](https://github.com/zhiyicx/plus-component-h5/commit/2f607ea))



<a name="4.0.0-rc.6"></a>
# [4.0.0-rc.6](https://github.com/zhiyicx/plus-component-h5/compare/v4.0.0-rc.5...v4.0.0-rc.6) (2018-07-05)

### Bug Fixes

* ([#235](https://github.com/zhiyicx/plus-component-h5/issues/235)) 发布问题字数限制 ([763c7ff](https://github.com/zhiyicx/plus-component-h5/commit/763c7ff))
* ([#235](https://github.com/zhiyicx/plus-component-h5/issues/235)) 发布问题超过50字提示 ([ba1a241](https://github.com/zhiyicx/plus-component-h5/commit/ba1a241))
* ([#270](https://github.com/zhiyicx/plus-component-h5/issues/270)) 问题详情页点击回答者头像进入回答者主页 ([06dd662](https://github.com/zhiyicx/plus-component-h5/commit/06dd662))
* ([#274](https://github.com/zhiyicx/plus-component-h5/issues/274)) 资讯详情 评论后刷新评论列表 ([855ce76](https://github.com/zhiyicx/plus-component-h5/commit/855ce76))
* ([#277](https://github.com/zhiyicx/plus-component-h5/issues/277)) 修复资讯评论无法弹出交互组件的问题 ([7fe8514](https://github.com/zhiyicx/plus-component-h5/commit/7fe8514))
* ([#279](https://github.com/zhiyicx/plus-component-h5/issues/279)) 修复动态详情页、圈子帖子顶部用户名没有居中的问题 ([9cc31f4](https://github.com/zhiyicx/plus-component-h5/commit/9cc31f4))
* ([#279](https://github.com/zhiyicx/plus-component-h5/issues/279)) 修复资讯列表页图片显示不正常的问题 ([d9702a4](https://github.com/zhiyicx/plus-component-h5/commit/d9702a4))
* ([#279](https://github.com/zhiyicx/plus-component-h5/issues/279)) 修复进入话题首屏无数据的问题 ([835c6c8](https://github.com/zhiyicx/plus-component-h5/commit/835c6c8))
* ([#279](https://github.com/zhiyicx/plus-component-h5/issues/279)) 标签栏非高亮标签颜色改为灰色 ([4218e8f](https://github.com/zhiyicx/plus-component-h5/commit/4218e8f))
* ([#279](https://github.com/zhiyicx/plus-component-h5/issues/279)) 标签栏非高亮标签颜色改为灰色 ([b9fc26a](https://github.com/zhiyicx/plus-component-h5/commit/b9fc26a))
* ([#279](https://github.com/zhiyicx/plus-component-h5/issues/279)) 话题没有图片时显示占位图 ([473f6f6](https://github.com/zhiyicx/plus-component-h5/commit/473f6f6))
* ([#291](https://github.com/zhiyicx/plus-component-h5/issues/291), [#292](https://github.com/zhiyicx/plus-component-h5/issues/292)) 动态和圈子下的评论删除后可以自动刷新了 ([09b004a](https://github.com/zhiyicx/plus-component-h5/commit/09b004a))
* ([#296](https://github.com/zhiyicx/plus-component-h5/issues/296)) 帖子收藏api地址修正 ([49e9aba](https://github.com/zhiyicx/plus-component-h5/commit/49e9aba))
* ([#440](https://github.com/zhiyicx/plus-component-h5/issues/440)) 增加注册协议显示开关 ([7cabd7c](https://github.com/zhiyicx/plus-component-h5/commit/7cabd7c))
* ([#449](https://github.com/zhiyicx/plus-component-h5/issues/449)) 圈子详情帖子列表显示摘要 ([0fdb9a3](https://github.com/zhiyicx/plus-component-h5/commit/0fdb9a3))
* ([#450](https://github.com/zhiyicx/plus-component-h5/issues/450)) 修复动态详情返回列表时滚动状态丢失的问题 ([e272172](https://github.com/zhiyicx/plus-component-h5/commit/e272172))
* ([#451](https://github.com/zhiyicx/plus-component-h5/issues/451)) 修复动态、帖子、资讯详情第一页的置顶评论重复显示的问题 ([050bb8b](https://github.com/zhiyicx/plus-component-h5/commit/050bb8b))
* ([#451](https://github.com/zhiyicx/plus-component-h5/issues/451)) 所有人都以 0 积分申请评论置顶了 ([a819ca6](https://github.com/zhiyicx/plus-component-h5/commit/a819ca6))
* ([#451](https://github.com/zhiyicx/plus-component-h5/issues/451)) 自己发布的帖子评论置顶文案替换 ([16bd910](https://github.com/zhiyicx/plus-component-h5/commit/16bd910))
* [#403](https://github.com/zhiyicx/plus-component-h5/issues/403) 动态详情点击需要付费的图片弹出付费窗口 ([aa53b6d](https://github.com/zhiyicx/plus-component-h5/commit/aa53b6d))
* easemob debug 配置失效的问题 ([00ee69c](https://github.com/zhiyicx/plus-component-h5/commit/00ee69c))
* 优化资讯搜索交互方式 ([b3eb5de](https://github.com/zhiyicx/plus-component-h5/commit/b3eb5de))
* 修复 axios 自定义状态验证属性拼写错误的问题 ([1cdf9e9](https://github.com/zhiyicx/plus-component-h5/commit/1cdf9e9))
* 修复 IOS 下不支持 Promise.finally 语法的问题 ([58c25dc](https://github.com/zhiyicx/plus-component-h5/commit/58c25dc))
* 修复动态详情页不能申请动态置顶的问题 ([e48fec1](https://github.com/zhiyicx/plus-component-h5/commit/e48fec1))
* 修复圈子帖子下拉刷新完毕后还显示“刷新中”的问题 ([5350a81](https://github.com/zhiyicx/plus-component-h5/commit/5350a81))
* 修复圈子帖子删除和置顶的api丢失的问题 ([d8a6118](https://github.com/zhiyicx/plus-component-h5/commit/d8a6118))
* 修复圈子帖子收藏失败的问题 ([b4aed3e](https://github.com/zhiyicx/plus-component-h5/commit/b4aed3e))
* 修复安卓某些版本不支持 Date 对象的 toLocaleString 方法 (hack) ([53fcc1e](https://github.com/zhiyicx/plus-component-h5/commit/53fcc1e))
* 修复收到的评论列表页资讯卡片头像传参不正确的问题 ([79a55b6](https://github.com/zhiyicx/plus-component-h5/commit/79a55b6))
* 修复标签选择组件作用域过大的问题 ([c9ea7ed](https://github.com/zhiyicx/plus-component-h5/commit/c9ea7ed))
* 修复资讯详情删除最后一条评论时列表没有刷新的问题 ([38e2883](https://github.com/zhiyicx/plus-component-h5/commit/38e2883))
* 发布资讯接口地址变更 ([7e5ecb4](https://github.com/zhiyicx/plus-component-h5/commit/7e5ecb4))
* 打赏页面余额不足时提示文案更改 ([7d3a662](https://github.com/zhiyicx/plus-component-h5/commit/7d3a662))
* 搜索结果页没有数据时显示占位图 ([d8099f2](https://github.com/zhiyicx/plus-component-h5/commit/d8099f2))
* 移除资讯搜索顶部左侧返回按钮 ([33e9beb](https://github.com/zhiyicx/plus-component-h5/commit/33e9beb))
* 移除问答模块页脚 ([218328a](https://github.com/zhiyicx/plus-component-h5/commit/218328a))
* 返回 422 错误时根据返回结果提示错误信息 ([1e593fd](https://github.com/zhiyicx/plus-component-h5/commit/1e593fd))
* 问答模块首页增加后退按钮 ([6d56b7e](https://github.com/zhiyicx/plus-component-h5/commit/6d56b7e))
* 问答页面导航栏高度不一致的问题 ([439e81d](https://github.com/zhiyicx/plus-component-h5/commit/439e81d))
* 问题列表页返回首页再进入时重新拉取数据 ([f447464](https://github.com/zhiyicx/plus-component-h5/commit/f447464))


### Features

* ([#137](https://github.com/zhiyicx/plus-component-h5/issues/137)) 资讯详情页可以下拉刷新了 ([096bf91](https://github.com/zhiyicx/plus-component-h5/commit/096bf91))
* ([#246](https://github.com/zhiyicx/plus-component-h5/issues/246)) 动态评论可以申请置顶了 ([5f11ee0](https://github.com/zhiyicx/plus-component-h5/commit/5f11ee0))
* ([#246](https://github.com/zhiyicx/plus-component-h5/issues/246)) 圈子帖子评论可以申请置顶了 ([93830c8](https://github.com/zhiyicx/plus-component-h5/commit/93830c8))
* ([#273](https://github.com/zhiyicx/plus-component-h5/issues/273)) 圈子帖子打赏页面路由 ([b7ab111](https://github.com/zhiyicx/plus-component-h5/commit/b7ab111))
* ([#273](https://github.com/zhiyicx/plus-component-h5/issues/273)) 资讯打赏 ([3316c53](https://github.com/zhiyicx/plus-component-h5/commit/3316c53))
* ([#273](https://github.com/zhiyicx/plus-component-h5/issues/273)) 问题回答打赏 ([e2fa57b](https://github.com/zhiyicx/plus-component-h5/commit/e2fa57b))
* ([#274](https://github.com/zhiyicx/plus-component-h5/issues/274)) 动态详情下可以删除自己发布的评论了 ([a908abb](https://github.com/zhiyicx/plus-component-h5/commit/a908abb))
* ([#274](https://github.com/zhiyicx/plus-component-h5/issues/274)) 圈子帖子下可以删除自己发布的评论了 ([730da50](https://github.com/zhiyicx/plus-component-h5/commit/730da50))
* ([#274](https://github.com/zhiyicx/plus-component-h5/issues/274)) 资讯详情下可以删除自己发布的评论了 ([c87cce6](https://github.com/zhiyicx/plus-component-h5/commit/c87cce6))
* ([#277](https://github.com/zhiyicx/plus-component-h5/issues/277)) 圈子帖子可以删除和申请置顶了 ([e76dffb](https://github.com/zhiyicx/plus-component-h5/commit/e76dffb))
* ([#277](https://github.com/zhiyicx/plus-component-h5/issues/277)) 资讯可以申请置顶了 ([e8f638f](https://github.com/zhiyicx/plus-component-h5/commit/e8f638f))
* ([#277](https://github.com/zhiyicx/plus-component-h5/issues/277)) 资讯评论可以申请置顶了 ([d1b9a0f](https://github.com/zhiyicx/plus-component-h5/commit/d1b9a0f))
* ([#279](https://github.com/zhiyicx/plus-component-h5/issues/279)) 问题搜索功能 ([4fa56fe](https://github.com/zhiyicx/plus-component-h5/commit/4fa56fe))
* ([#440](https://github.com/zhiyicx/plus-component-h5/issues/440)) 注册协议 ([d3a265e](https://github.com/zhiyicx/plus-component-h5/commit/d3a265e))



<a name="4.0.0-rc.5"></a>
# [4.0.0-rc.5](https://github.com/zhiyicx/plus-component-h5/compare/v4.0.0-rc.4...v4.0.0-rc.5) (2018-06-22)


### Bug Fixes

* rollback easemob-websdk ([fa14bd5](https://github.com/zhiyicx/plus-component-h5/commit/fa14bd5))



<a name="4.0.0-rc.4"></a>
# [4.0.0-rc.4](https://github.com/zhiyicx/plus-component-h5/compare/v4.0.0-rc.3...v4.0.0-rc.4) (2018-06-22)


### Bug Fixes

* ( [#341](https://github.com/zhiyicx/plus-component-h5/issues/341) ) 修改手机号验证 ([b37966e](https://github.com/zhiyicx/plus-component-h5/commit/b37966e))
* ( [#349](https://github.com/zhiyicx/plus-component-h5/issues/349) ) 修改动态详情bug ([afff106](https://github.com/zhiyicx/plus-component-h5/commit/afff106))
* ([#204](https://github.com/zhiyicx/plus-component-h5/issues/204)) 按需求格式化时间字符串(全局有效) ([f930f28](https://github.com/zhiyicx/plus-component-h5/commit/f930f28))
* ([#351](https://github.com/zhiyicx/plus-component-h5/issues/351)) 将通用弹框调整为绝对位置避免同时出现多个弹框 ([fd1124e](https://github.com/zhiyicx/plus-component-h5/commit/fd1124e))
* ([#360](https://github.com/zhiyicx/plus-component-h5/issues/360)) 动态详情页增加刷新操作 ([e43e2a0](https://github.com/zhiyicx/plus-component-h5/commit/e43e2a0))
* ([#438](https://github.com/zhiyicx/plus-component-h5/issues/438)) 图片灯箱允许点击任意区域关闭 ([3e765e9](https://github.com/zhiyicx/plus-component-h5/commit/3e765e9))
* (issue [#160](https://github.com/zhiyicx/plus-component-h5/issues/160)) 资讯详情 没有摘要时不显示摘要 ([2c970e0](https://github.com/zhiyicx/plus-component-h5/commit/2c970e0))
* (issue [#354](https://github.com/zhiyicx/plus-component-h5/issues/354)) 注册用户名数字校验优先级调整 ([ae356f1](https://github.com/zhiyicx/plus-component-h5/commit/ae356f1))
* (issue [#372](https://github.com/zhiyicx/plus-component-h5/issues/372)) 动态详情 增加右上角关注按钮 ([f915010](https://github.com/zhiyicx/plus-component-h5/commit/f915010))
* (issue [#388](https://github.com/zhiyicx/plus-component-h5/issues/388)) 排行榜列表中不应该显示自己的关注按钮 ([1646ed7](https://github.com/zhiyicx/plus-component-h5/commit/1646ed7))
* (issue [#394](https://github.com/zhiyicx/plus-component-h5/issues/394)) 图片动态 图片未上传完毕时发布按钮置灰 ([42a6df7](https://github.com/zhiyicx/plus-component-h5/commit/42a6df7))
* (issue [#402](https://github.com/zhiyicx/plus-component-h5/issues/402)) 个人主页删除动态后重新拉取用户数据 ([4dabe4c](https://github.com/zhiyicx/plus-component-h5/commit/4dabe4c))
* (issue [#411](https://github.com/zhiyicx/plus-component-h5/issues/411), [#420](https://github.com/zhiyicx/plus-component-h5/issues/420), [#394](https://github.com/zhiyicx/plus-component-h5/issues/394)) 发布图片动态时检测是否含有上传失败的图片 ([7458553](https://github.com/zhiyicx/plus-component-h5/commit/7458553))
* (issue [#411](https://github.com/zhiyicx/plus-component-h5/issues/411)) 动态发布失败后消息提示 ([36ac230](https://github.com/zhiyicx/plus-component-h5/commit/36ac230))
* (issue [#418](https://github.com/zhiyicx/plus-component-h5/issues/418)) 移除个人资料选择标签页右上角“下一步” ([cc21744](https://github.com/zhiyicx/plus-component-h5/commit/cc21744))
* (issue [#444](https://github.com/zhiyicx/plus-component-h5/issues/444)) 注册输入错误的验证码后按钮从忙时状态恢复 ([ef6c00f](https://github.com/zhiyicx/plus-component-h5/commit/ef6c00f))
* (issue [#446](https://github.com/zhiyicx/plus-component-h5/issues/446)) “动态”中的脚本转化为纯文本展示 ([a905620](https://github.com/zhiyicx/plus-component-h5/commit/a905620))
* Fixed some Bugs ([91fd54e](https://github.com/zhiyicx/plus-component-h5/commit/91fd54e))
* HTML in markdown support ([394c2be](https://github.com/zhiyicx/plus-component-h5/commit/394c2be))
* HTML 换行符转换 ([f239d21](https://github.com/zhiyicx/plus-component-h5/commit/f239d21))
* 个人资料页标签修改无效的问题 ([b294354](https://github.com/zhiyicx/plus-component-h5/commit/b294354))
* 修复 "附近的人无法关注" ([92df9e5](https://github.com/zhiyicx/plus-component-h5/commit/92df9e5))
* 修复个人主页分享后404 ([31d3ed7](https://github.com/zhiyicx/plus-component-h5/commit/31d3ed7))
* 修复为登录时, 获取关注的动态提示登录后不跳转的bug ([3335479](https://github.com/zhiyicx/plus-component-h5/commit/3335479))
* 修复修改新版登录接口后, 无法获取未读信息数的问题 ([5e5fdd9](https://github.com/zhiyicx/plus-component-h5/commit/5e5fdd9))
* 修复动态列表点赞数量bug ([e54233f](https://github.com/zhiyicx/plus-component-h5/commit/e54233f))
* 修复动态详情页不能加载更多评论的问题 ([5720836](https://github.com/zhiyicx/plus-component-h5/commit/5720836))
* 修复圈子帖子无法打开的问题 ([ab27025](https://github.com/zhiyicx/plus-component-h5/commit/ab27025))
* 修复圈子帖子详情打不开 ([96c125b](https://github.com/zhiyicx/plus-component-h5/commit/96c125b))
* 修复微信登录及微信分享时获取到的错误url ([b629577](https://github.com/zhiyicx/plus-component-h5/commit/b629577))
* 修复微信登录后的错误跳转地址 ([01fef86](https://github.com/zhiyicx/plus-component-h5/commit/01fef86))
* 修复打赏弹出层依旧使用余额的问题 ([0cf4766](https://github.com/zhiyicx/plus-component-h5/commit/0cf4766))
* 修复打赏边框消失的问题 ([0ed3352](https://github.com/zhiyicx/plus-component-h5/commit/0ed3352))
* 修复方法为引入的错误 ([07767f7](https://github.com/zhiyicx/plus-component-h5/commit/07767f7))
* 修复无法链接环信 ([271ede7](https://github.com/zhiyicx/plus-component-h5/commit/271ede7))
* 修复消息模块中错误的动态详情地址 ([8f05e81](https://github.com/zhiyicx/plus-component-h5/commit/8f05e81))
* 修复消息页面中小红点异常 ([b4c63c8](https://github.com/zhiyicx/plus-component-h5/commit/b4c63c8))
* 修复环信访问协议判断 ([bc8f516](https://github.com/zhiyicx/plus-component-h5/commit/bc8f516))
* 修复视频加载两次的BUG ([51ad9a3](https://github.com/zhiyicx/plus-component-h5/commit/51ad9a3))
* 修复获取图片错误 ([3286cde](https://github.com/zhiyicx/plus-component-h5/commit/3286cde))
* 修复语法错误 ([76afdf6](https://github.com/zhiyicx/plus-component-h5/commit/76afdf6))
* 修复语法错误 again ([5a6503d](https://github.com/zhiyicx/plus-component-h5/commit/5a6503d))
* 修复资讯详情页变量声明错误的问题 ([0e21aac](https://github.com/zhiyicx/plus-component-h5/commit/0e21aac))
* 修复进入圈子后没有数据的问题 ([7162fbe](https://github.com/zhiyicx/plus-component-h5/commit/7162fbe))
* 修改为对的图标 ([10e84fa](https://github.com/zhiyicx/plus-component-h5/commit/10e84fa))
* 修改排行榜中的问题 ([451f767](https://github.com/zhiyicx/plus-component-h5/commit/451f767))
* 修正列表页面显示文字 ([b1edaee](https://github.com/zhiyicx/plus-component-h5/commit/b1edaee))
* 修正动态列表图片缩略图裁剪位置 ([99ddd41](https://github.com/zhiyicx/plus-component-h5/commit/99ddd41))
* 修正动态详情页面的内容布局错误 ([fcb34a4](https://github.com/zhiyicx/plus-component-h5/commit/fcb34a4))
* 修正微信分享 ([819f4a0](https://github.com/zhiyicx/plus-component-h5/commit/819f4a0))
* 修正打赏用户接口 ([b85b317](https://github.com/zhiyicx/plus-component-h5/commit/b85b317))
* 修正积分展示字段 ([2987a61](https://github.com/zhiyicx/plus-component-h5/commit/2987a61))
* 去掉未实现的 ([92b684c](https://github.com/zhiyicx/plus-component-h5/commit/92b684c))
* 去除debug, 修正分享需要的图片和文字内容 ([27dbe13](https://github.com/zhiyicx/plus-component-h5/commit/27dbe13))
* 只弹出一个报错窗口 ([5eb265b](https://github.com/zhiyicx/plus-component-h5/commit/5eb265b))
* 图片上传失败后点击缩略图重新上传 ([d27a146](https://github.com/zhiyicx/plus-component-h5/commit/d27a146))
* 圈子帖子列表评论丢失的问题 ([9d1d559](https://github.com/zhiyicx/plus-component-h5/commit/9d1d559))
* 微信登录后, 获取正确的token保存 ([9c561d2](https://github.com/zhiyicx/plus-component-h5/commit/9c561d2))
* 打赏单位变更 小数部分移除 ([509c388](https://github.com/zhiyicx/plus-component-h5/commit/509c388))
* 改用签到新接口 ([0db2d64](https://github.com/zhiyicx/plus-component-h5/commit/0db2d64))
* 改错 ([cb9c227](https://github.com/zhiyicx/plus-component-h5/commit/cb9c227))
* 游客模式允许访问圈子模块 ([a93d9bf](https://github.com/zhiyicx/plus-component-h5/commit/a93d9bf))
* 真.解决ios在微信下分享的bug ([9a78972](https://github.com/zhiyicx/plus-component-h5/commit/9a78972))
* 获取base_url错误 ([1cc98f3](https://github.com/zhiyicx/plus-component-h5/commit/1cc98f3))
* 解决初始未拿到服务端启动信息的问题 ([5d5d413](https://github.com/zhiyicx/plus-component-h5/commit/5d5d413))
* 解决圈子首页的帖子操作条数据不对的问题 ([ecb231a](https://github.com/zhiyicx/plus-component-h5/commit/ecb231a))
* 资讯详情页置顶评论和普通评论中出现重复的键值 ([96ceef5](https://github.com/zhiyicx/plus-component-h5/commit/96ceef5))
* 非微信浏览器依然请求微信配置, 动态详情自动换行 ([908a221](https://github.com/zhiyicx/plus-component-h5/commit/908a221))
* 顶部标签栏垂直对齐的问题 ([2b4baab](https://github.com/zhiyicx/plus-component-h5/commit/2b4baab))


### Features

* ( [#442](https://github.com/zhiyicx/plus-component-h5/issues/442) ) 新增回答详情(基础预览页面) ([b26a03d](https://github.com/zhiyicx/plus-component-h5/commit/b26a03d))
* ([#361](https://github.com/zhiyicx/plus-component-h5/issues/361)) 增加修改密码页面 ([6b4527d](https://github.com/zhiyicx/plus-component-h5/commit/6b4527d))
* ([#391](https://github.com/zhiyicx/plus-component-h5/issues/391)) 排行榜页面上拉刷新下拉加载 ([1881d9a](https://github.com/zhiyicx/plus-component-h5/commit/1881d9a))
* ([#412](https://github.com/zhiyicx/plus-component-h5/issues/412)) 个人主页点击banner更换背景 ([42bd1aa](https://github.com/zhiyicx/plus-component-h5/commit/42bd1aa))
* 增加从服务端获取每页数据量 ([d879aa5](https://github.com/zhiyicx/plus-component-h5/commit/d879aa5))
* 增加我的页面 收藏的动态, 我的投稿 ([bd74a1f](https://github.com/zhiyicx/plus-component-h5/commit/bd74a1f))
* 完善动态图片购买 ([8227a8f](https://github.com/zhiyicx/plus-component-h5/commit/8227a8f))
* 文件 ([eab31bc](https://github.com/zhiyicx/plus-component-h5/commit/eab31bc))
* 新增 "圈子详情" 页面 ([70f1f25](https://github.com/zhiyicx/plus-component-h5/commit/70f1f25))
* 新增"发布/发布问题" ([ffa9cf7](https://github.com/zhiyicx/plus-component-h5/commit/ffa9cf7))
* 新增"发布图片/图片收费选项"功能 ([a36b8f2](https://github.com/zhiyicx/plus-component-h5/commit/a36b8f2))
* 新增动态打赏功能 ([6b28d46](https://github.com/zhiyicx/plus-component-h5/commit/6b28d46))
* 新增被评论页面的回复按钮 ([e5f8c50](https://github.com/zhiyicx/plus-component-h5/commit/e5f8c50))
* 条件api文件 ([b7e37b2](https://github.com/zhiyicx/plus-component-h5/commit/b7e37b2))
* 添加"发布/投稿"功能 ([f95fe94](https://github.com/zhiyicx/plus-component-h5/commit/f95fe94))
* 添加"签到"功能 ([2a73f51](https://github.com/zhiyicx/plus-component-h5/commit/2a73f51))
* **feed:** 新增"动态/打赏列表" ([a147209](https://github.com/zhiyicx/plus-component-h5/commit/a147209)), closes [#378](https://github.com/zhiyicx/plus-component-h5/issues/378)
* **feed:** 新增"动态点赞列表" ([e89b96d](https://github.com/zhiyicx/plus-component-h5/commit/e89b96d)), closes [#378](https://github.com/zhiyicx/plus-component-h5/issues/378)
* **feed:** 新增动态(更多操作)/[收藏/取消收藏、申请动态置顶、删除动态] ([f382e15](https://github.com/zhiyicx/plus-component-h5/commit/f382e15))



<a name="4.0.0-rc.3"></a>
# [4.0.0-rc.3](https://github.com/zhiyicx/plus-component-h5/compare/v4.0.0-rc.2...v4.0.0-rc.3) (2018-03-09)



<a name="4.0.0-rc.2"></a>
# [4.0.0-rc.2](https://github.com/zhiyicx/plus-component-h5/compare/v4.0.0-rc.1...v4.0.0-rc.2) (2018-03-08)



<a name="4.0.0-rc.1"></a>
# [4.0.0-rc.1](https://github.com/zhiyicx/plus-component-h5/compare/3.5.0...v4.0.0-rc.1) (2018-03-08)


### Bug Fixes

* 修复`组件内静态资源引用路径错误` ([592605a](https://github.com/zhiyicx/plus-component-h5/commit/592605a))
* 修复错误的返回参数 ([4dd3193](https://github.com/zhiyicx/plus-component-h5/commit/4dd3193))
* 改错 ([f2aedde](https://github.com/zhiyicx/plus-component-h5/commit/f2aedde))


### Features

* ( [#322](https://github.com/zhiyicx/plus-component-h5/issues/322) ) 新增 `发布帖子` 重构`发布菜单` 修改`[ commentInput, postMenu, feedItem...]`等组件 ([f1700ea](https://github.com/zhiyicx/plus-component-h5/commit/f1700ea))
* ( [#324](https://github.com/zhiyicx/plus-component-h5/issues/324) ) 新增`修改圈子详情` ([bcc27a3](https://github.com/zhiyicx/plus-component-h5/commit/bcc27a3))
* ( [#324](https://github.com/zhiyicx/plus-component-h5/issues/324) ) 新增`圈子发帖权限设置` ([82226a3](https://github.com/zhiyicx/plus-component-h5/commit/82226a3))
* ( [#324](https://github.com/zhiyicx/plus-component-h5/issues/324) ) 新增`圈子成员管理` ([8fed1ec](https://github.com/zhiyicx/plus-component-h5/commit/8fed1ec))
* ([#321](https://github.com/zhiyicx/plus-component-h5/issues/321)) 新增 ` 我的圈子 ` ([fae120d](https://github.com/zhiyicx/plus-component-h5/commit/fae120d))
* ([#321](https://github.com/zhiyicx/plus-component-h5/issues/321)) 新增 `圈子详情` ([5d1db47](https://github.com/zhiyicx/plus-component-h5/commit/5d1db47))
* ([#322](https://github.com/zhiyicx/plus-component-h5/issues/322)) 新增 `圈子 - 帖子 - 基础操作`  ([#327](https://github.com/zhiyicx/plus-component-h5/issues/327)) 重构 `动态` ([7b9a74c](https://github.com/zhiyicx/plus-component-h5/commit/7b9a74c))
* 新增  `圈子 - 首页列表, 圈子 - 我加入的列表` ([#321](https://github.com/zhiyicx/plus-component-h5/issues/321)) ([2096dec](https://github.com/zhiyicx/plus-component-h5/commit/2096dec))
* 新增 `创建圈子`  ([#321](https://github.com/zhiyicx/plus-component-h5/issues/321)) ([e0189c8](https://github.com/zhiyicx/plus-component-h5/commit/e0189c8))
* 新增 `圈子列表,  搜索圈子`, 调整 `HeadTop` 组件样式 ([6615404](https://github.com/zhiyicx/plus-component-h5/commit/6615404))
* **news:** Add news-list ([5279ea5](https://github.com/zhiyicx/plus-component-h5/commit/5279ea5))
* **news/search:** 增加搜索资讯功能 ([eaafc93](https://github.com/zhiyicx/plus-component-h5/commit/eaafc93))
* **newsDetail:** Add page "newsDetail" ([d245990](https://github.com/zhiyicx/plus-component-h5/commit/d245990))
* **Question:** 增加「问答」大部分功能。 ([f7b4166](https://github.com/zhiyicx/plus-component-h5/commit/f7b4166))
