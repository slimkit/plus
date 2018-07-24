# 合并 Laravel `master` 分支至 Plus `master` 指南

- [场景描述](#overview)
- [Merging 时机](#merging-time)
- [添加 Laravel 远端到本地仓库](#add-laravel-repo)
- [步骤 1（合并 Plus `master` 分支）](#merged-plus-master-branch)
- [步骤 2（合并 Laravel `master` 分支）](#merged-laravel-master-branch)
    - [解决合并冲突](#resolve-conflict)
- [步骤 3 提交 `merge_laravel`](#push-merge-laravel-branch)
- [步骤 4 将 `merge_laravel` 合并至 `master`](#merged-merge-laravel-branch-to-master)

<a name="overview"></a>
## 场景描述

Plus（ThinkSNS Plus 缩写） 是基于 [laravel/laravel](https://github.com/laravel/laravel) 仓库进行开发的一个程序，
前期是无纪律的直接向 Plus `master` 分支进行 「merge」 操作，造成 Plus 的 Commits 线并非存粹，
而是包含了 Laravel 的 Commits。

本指南从而解决这个问题，本指南将指导如何干净的跟随 Laravel 的更新。

<a name="merging-time"></a>
## Merging 时机

首先，我们应当牢记或者合并前查看 [slimkit/thinksns-plus:merge_laravel](https://github.com/slimkit/thinksns-plus/tree/merge_laravel) 上次合并 Laravel 的 commit hash，当然，会在本节下记录当前合并信息。

其次，更应当至少以 **周** 为节点关注 [laravel/laravel:master](https://github.com/laravel/laravel/tree/master) 的变动情况，
但是这不是必须的。我们更应关注的是 [Laravel Releases](https://github.com/laravel/laravel/releases) 一旦发现基于 `master` 分支的新「tag」或者 `release` 发布时，我们应当进行 `merge` 操作。

上次 Merge 的 Larave 版本 | 上次 Merge 操作执行时间
------------------------|----------------------
5.6.* | 2018-07-27

<a name="add-laravel-repo"></a>
## 添加 Laravel 远端到本地仓库

首先记住一个重要信息，Laravel 远端仓库为：`https://github.com/laravel/laravel`

执行 `git remote add laravel https://github.com/laravel/laravel` 此时，你本地已经添加了 Laravel 远端在本地，

> 如果上述你本地早已完成，可忽略。

<a name="merged-plus-master-branch"></a>
## 步骤 1（合并 Plus `master` 分支）

当我们发现可以 `merge` 操作的版本时，不要着急去进行合并。

首先我们使用 `git checkout merge_laravel` 进入 **合并操作分支**，进入 `merge_laravel` 分支后我们应该使用常规 `merge` 将 `master` 
分支代码合并过来，我们现在执行 `git merge master`

> 此时，我们已经把 master 代码合并到了 `merge_laravel` 分支了，如果你出现了冲突，那只能说明一个问题，团队成员以前的 `merge` 操作对 `merge_laravel` 产生了干扰。
> 还有可能是另一个问题，团队成员在 `merge_laravel` 分支做出了非 merged 的干扰提交。

<a name="merged-laravel-master-branch"></a>
## 步骤 2（合并 Laravel `master` 分支）

首先，我们应该切换到 `merge_laravel` 分支，可以执行 `git checkout merge_laravel` 切换。

> 注意：在我们拉取 Laravel 源的代码前，请确认你的修改都已经保存并 commit。

接下来我们从 Laravel 远端的 `master` 拉取提交到本地的 `merge_laravel` 分支：

```shell
git pull laravel master
```

<a name="resolve-conflict"></a>
### 解决合并冲突

大多数情况下，因为我们也需要再 Laravel 远端的代码上增加我们自己的代码。一般拉取完成后基本上都会出现冲突的，冲突的部分就是 Laravel 更新，我们也修改过的地方，此时你应该根据合并结果找到 `CONFLICT (content): Merge conflict in xxxx.xxx` 部分的文件打开，找到
冲突的地方合并修正就好了。

解决完冲突的代码结构就与 Laravel 官方一致了，而且这种合并方式不会让落下任何一个不同的地方。

<a name="push-merge-laravel-branch"></a>
## 步骤 3 提交 `merge_laravel`

上面步骤都完成后，你会在 `merge_laravel` 分支下产生几条 Commit 记录，此时，你应该先将这些没有提交到 Plus 远端的 Commit 提交上去，并且等待 **持续集成** 的测试结果，以此类方式与官方保持一致很少会出现持续集成失败的。

很多时候失败都是更新了一些配置版本导致，尤其是前端，此时请在 `merge_laravel`
 下解决合并带来的不兼容问题。然后执行 `git push` 将 Commit 推到远端。

<a name="merged-merge-laravel-branch-to-master"></a>
## 步骤 4 将 `merge_laravel` 合并至 `master`

此时，我们距离合并工作只只剩下最后一步了，就是将 `merge_laravel` 合并完成并解决了冲入的 Laravel 最新代码结构合并到 `master` 分支中。

此时我们的合并必须采用 `squash` 模式进行合并，以免 Laravel 的更新工作给 Plus 的 Commits 线带来分叉扰乱团队的历史记录。

首要任务是切换回 `master` 分支：`git checkout master`，然后我们执行 `squash` 进行合并：

```shell
git merge --squash merge_laravel
```

一般这个步骤都不会报错，如果报错，一定是团队有成员扰乱了 `merge_laravel` 的 Commits，因为我们已经解决了 Laravel 在 Plus 中的冲突，这一步骤不会报错。

好了，我们合并完成，剩下的就是将 `master` 提交到远端即可。

Commit message 推荐写法：

```
chore: Merged laravel:master to master branch, Updated Laravel version to x.x.x
```
