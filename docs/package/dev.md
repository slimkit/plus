# 内置开发支持

内置开发支持，是可以方便开发 拓展而生的一种模式，我们都知道，因为包都是采用 Composer 进行的依赖，而开发包，又要实时加载，还要打包，难免繁琐和加上国内的网络问题干扰。

## 创建拓展

创建拓展是可以让你快速开发的一种模式，会生成一个可运行的标准拓展包，你只需:

```shell
php artisan package:create
```

按照询问输入对应的信息，即可完成。

> 生成后会存放在 `resource/repsitorie/source` 目录下

## 打包

无论你是在 `resource/repsitorie/source` 目录下手动创建的包还是 `php artisan package:create` 生成的标准包，都可以运行:

```shell
php artisan package:archive vendor/name [version]
```

打包一个包，例如你的包叫做 `zhiyicx/example` 你只需要输入 `php artisan package:archive zhiyicx/example` 即可打包，当然按照 Composer 当前打包的会是 master 版本，你要模拟发布版本，只需要加入版本即可 `php artisan package:archive zhiyicx/example 1.0.0` 这样我们就模拟发布了一个 `1.0.0` 版本的 Composer 包，你可以回到 ThinkSNS+ 根，运行 `composer require zhiyicx/example` 或者 `composer update zhiyicx/example` 来使用我们打包的包。

## 软链

打包需求在什么时候有用？当然是对初次 `composer require` 或者对 `composer.json` 进行了修改的时候，但是大多数开发情况下不会频繁的修改 `composer.json`，我们都是修改 PHP 代码，也要打包吗？显然，打包会降低开发效率，所以我们提供了:

```shell
php artisan package:link vendor/name
```

这个命令可以把你在 `resource/repsitorie/source` 目录下的包「软链」的形式链接到 vendor 下，所以，你只需要修改你的代码，就会直接生效，这个对开发过程中帮助很大。

------------------

> 开发 ThinkSNS+ 拓展包，配合内酯开发模式，会非常方便，只要熟了的运用这几个命令即可。
> 你不再需要正式打包，就可以进行打包发布，这样在你开发完成后就可以正式打包了，而不是开发过程中就打包。
>
> `vendor/name` 可以是标准的包名称， `php artisan package:create` 生成的就是标准包名称目录，也可以是你在 `resource/repsitorie/source` 目录下的目录名称，例如你的包在 `resource/repsitorie/source/example` 你可以 `php artisan package:link example` 而不用输入完整的包名称。
