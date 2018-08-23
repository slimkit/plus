<p align="center"><img src="https://github.com/slimkit/thinksns-plus/raw/master/public/plus.png"></p>

## 说明
该程序为[ThinkSNS Plus](https://github.com/slimkit/thinksns-plus/)的Web应用拓展包

## 技术要求
- Laravel Blade
- HTML5 + CSS3
- JavaScript
- jQuery
- Lodash
- Axios
- Dexie

## 目录结构
根目录
- `config`
- `database`
- `resources`
- `routes `
- `src`

`database` 目录

存放数据库迁移文件

`resources` 目录

assets目录包含前后台的静态资源文件，views目录包含所有的Blade模板文件

`routes` 目录

包含前后台的路由定义文件

`src` 目录

包含前后台控制器，模型，视图Composer文件

## 安装
1. 首先需要安装[ThinkSNS Plus](https://github.com/slimkit/thinksns-plus/)主程序
2. 克隆代码到主程序packages目录
3. 编辑主程序根目录下的composer.json，找到json对象中的「repositories」属性，新增PC信息，找到「require」属性，新增PC依赖
    ```
    {
        "type": "path",
        "url": "packages/plus-componet-pc",
        "options": {
            "symlink": true,
            "plus-soft": true
        }
    }
    ```
    
    ```
    {
    ...
    "require": {
        ...
        "zhiyicx/plus-component-pc": "^3.0.0"
    }
    ```
}

4. 修改PC包中的composer.json, 新增version版本号
   ```
   {
        ...
        "require": {
            "overtrue/socialite": "^2.0",
            "gregwar/captcha": "1.*"
        },
        "version": "3.0.1"
    }
   ```
5. 主程序根目录执行composer update
6. 执行PC包命令
   ```
   php artisan package:handle pc install
   ```
7. 若需要经常修改静态资源，建议执行软链命令
   ```
   php artisan package:handle pc link
   ```

## 注意事项
1. PC拓展包数据均通过内部请求调用[ThinkSNS Plus](https://slimkit.github.io/docs/api-v2-overview.html)接口获得，不涉及相关业务逻辑，若需修改，请到接口对应程序位置。
2. 若需要修改js插件源码，需要修改后执行yarn prod命令进行编译，生成新的js文件，详见webpack.mix.js。
3. 若需要使用三方登录功能，需要后台PC管理-三方登录配置相关信息。
4. 若修改后台配置后，必须根目录执行`php artisan cache:clear`或前往后台PC管理点击清除缓存。
5. 图标采用的是阿里云的[iconfont](http://www.iconfont.cn/)，若需要修改图片，请联系我们将您拉进图标库后，自行创建仓库导入后新增修改。
6. 三方配置回调域：
  ~~~
  QQ
  网站回调域：绑定域名/socialite/qq/callback
  如：http://tsplus.zhibocloud.cn/socialite/qq/callback

  微信开放平台
  授权回调域：绑定域名即可
  如：tsplus.zhibocloud.cn

  微博开放平台
  应用地址：绑定域名
  如：http://tsplus.zhibocloud.cn
  安全域名：可填写多个，必须把绑定域名填写在其中，去掉http://或者https://
  如：tsplus.zhibocloud.cn
  ~~~

