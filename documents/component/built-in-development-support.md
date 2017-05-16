# 内置开发支持

内置开发支持，是为了方面开发 TS+ component而生的，当然这个是可选的，你可以使用任何你喜欢的方式开发，只要最后保证能通过安装方法安装应用即可～

内置开发模式的存在是为了解决开发过程中需要平凡的为 composer 打包 tag 而生的一种模式。

## 代码存放

内置开发支持需要将你的代码存放在 **resource/repositorie/source** 目录下，在该目录下新建一个你拓展的名录，然后在该目录中开始开发代码。

然后手动编辑 TS+ 的 composer.json 文件 require 项增加你真实的完整包依赖配置

## tag 打包
每当你需要为你的 component 新建一个 composer 依赖版本的时候，只需要运行
```shell
php artisan component:archive package
```

> package 为你建立的目录名称

此时，程序会按照你的项目 composer.json 打包一个归档。

你只需要运行
```shell
composer update
```

这样就模拟完成了整个线上 `git tag` 和发布tag版本过程。

> 这个过程不需要依赖网络～只会在你本地打包一个 zip 归档用于 update 使用。

## 注意事项

默认情况下，打包的归档版本都是 dev-master 对应的 hash 值，开发过程中，你需要在你的 composer.json 文件中配置 **version** 来告诉本地打包程序，需要打包的版本，简而言之，你每一次的本地打包安装调试，都需要修改你自己的版本信息打包。

> 如果不修改，每次程序打包的版本都一致，composer 则会根据版本判定无升级.
