# 安装类基础指导

现在，我们来开发拓展应用中最基础的信息部分

## 建立文件

Installer.php
```php
<?php

namespace Vendor\Name;

use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;

class Installer extends AbstractInstaller
{
    // TODO.
}

```
其中，我们继承 `Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller` 这个类，这个类是基于 `Zhiyi\Component\Installer\PlusInstallPlugin\InstallerInterface` 接口的二次抽象封装。
一旦继承，你需要实现的事情就变少了，你只需要完成:

- public function getComponentInfo();

当然，这个接口也是可选的～你可以定义一个空方法，表示没有定义～

一但定义，你必须 return `Zhiyi\Component\Installer\PlusInstallPlugin\ComponentInfoInterface` 接口的实例。

## ComponentInfoInterface

接口需要实现的方法：

- public fucntion getName(): string
- public function getLogo(): string
- public function getIcon(): string
- public function getAdminEntry();

```php
<?php

namespace Zhiyi\Component\Installer\PlusInstallPlugin;

interface ComponentInfoInterface
{
    /**
     * Get the coppnent display name.
     *
     * ```php
     * public function getName(): string {
     *     return '测试应用';
     * }
     * ```
     *
     * @see 测试应用
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getName(): string;

    /**
     * Get the component show logo.
     *
     * ```php
     * public function getLogo(): string
     * {
     *     // return 'https://avatars0.githubusercontent.com/u/5564821?v=3&s=460';
     *     // The func created the component resource to public.
     *     return assset('medz/plus-component-example/logo.png');
     * }
     * ```
     *
     * @see asset('medz/plus-component-example/logo.png')
     * @see https://example/logo.png
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getLogo(): string;

    /**
     * Get the component admin list show icon.
     *
     * reference ::getLogo()
     *
     * @see asset('medz/plus-component-example/icon.png')
     * @see https://example/icon.png
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getIcon(): string;

    /**
     * Get the component admin list link.
     *
     * @see route('/example/admin');
     * @see url('/example/admin');
     * @see https://example/example/admin
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getAdminEntry();
}

```
