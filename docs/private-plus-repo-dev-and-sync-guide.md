# 私有 Plus 仓库开发与同步指南

- [场景描述](#overview)
- [如何开发](#how-to-dev)
- [如何集合](#how-to-assemble)
- [添加 Plus 私有远程库](#add-private-plus-to-public-plus)
- [合并公开库](#merging-public-plus-repo)

<a name="overview"></a>
## 场景描述

首先，您开始阅读这份指南表示您依旧对 Plus 的多库开发产生了质疑，而本份指南将解决您心中的疑惑。

起初，我们的设计是进行模块化设计，但是随着我们的需求「甲方」需要的东西越来越不适合做这件事，我们开始尝试「大集合」模式，但是仍然保留「模块化」
的机制，而起初进行开发啊的私有模块都分散在各个仓库中，而团队本身没有时间进行「线上授权分发库」的开发。

这导致我们的客户以及维护人员在维护和更新模块的时候工作变得异常困难，从何在尝试「大集合」模式的时候我们初步整合了所有公开包，随着私有包越来越多，我们也需要对私有包进行集合。

由此而生了 https://github.com/zhiyicx/plus 这个私有库。而本库所做的开发工作只能包含「私有性」的开发维护。

<a name="how-to-dev"></a>
## 如何开发

> 首先明确一点，我们所有的开发工作都应该在 https://github.com/slimkit/thinksns-plus 下进行，而本指南将描述 **例外**。

首先明确 **2** 个文件和 **3** 个目录：

- /composer.json
- /package.json

上述这两个文件，只允许在 Plus 公开库的基础上增加，以及修改公开库没有的部分，绝对不允许修改公开库拥有的部分。

- /docs
- /packages
- /tests

上述文件夹只允许修改公开库内没有的文件，或者新增公开库不会新增的文件。

> 除了上述在不更改这些文件或者目录下的 Plus 前提下，你只能修改私有库部分，绝对不允许修改公开库部分，如需修改公开库部分，必须在公开库修改。

<a name="how-to-assemble"></a>
## 如何集合

在「大集合」中，所有拓展依旧使用「模块化」开发，并存放目录在 `/packages` 目录中，而代码放在此处后，你应该编辑 Plus 的
`composer.json` 文件增加一个 `repositories`，示例如下：

```json
{
    "type": "path",
    "url": "packages/slimkit-plus-id",
    "options": {
        "symlink": true,
        "plus-soft": true
    }
}
```

字段 | 描述
----|----
type | 固定 `path`
url | 相对于 Plus 被集合的包的所在目录
options.symlink | 固定 `true`，以保证拓展包随时更新
options.plus-soft | 视情况而定，如果需要和 Plus 进行版本统一则必须设置为 `true` 这样在执行 `app:version` 设置新版本的时候才会得到控制

> 放入新的拓展包后你应该执行 `php artisan app:version` 然后设置新版本后提交

<a name="add-private-plus-to-public-plus"></a>
## 添加 Plus 私有远程库

> 这里拟定你已经是 Plus 私有库的协作者成员。

**再次声明，我们所有的开发工作都是以 Plus 公开库作为基准**，所以这拟定你现在已经在 Plus 公开库中进行工作。

现在你有部分工作需要切换到 Plus 私有库进行，我们执行 `git remote add plus https://github.com/zhiyicx/plus` 将远程私有 Plus 库
添加到本地。

然后你需要执行 `git fetch plus` 来同步 Plus 私有库的数据库索引，然后执行 `git checkout -b plus plus:master` 将 `plus:master` 创建索引到本地 `plus` 分支。

> 如果您不是第一次工作在私有库，之前就完成过上述工作，你可以直接使用 `git checkout plus` 进行分支切换，然后执行 `git pull` 拉取一次代码。

<a name="merging-public-plus-repo"></a>
## 合并公开库

上面说了，私有库只允许工作私有部分的事情，所以，合并也变得相对简单。你切换到 `plus` 分支后直接执行 `git merge master` 即可，
合并完成执行 `git push plus plus:master` 即可推送到私有库远端。
