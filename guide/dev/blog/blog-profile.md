---
title: 博客主页
---

前面的章节中，我们已经完成了创建博客逻辑，现在我们来实现博客主页逻辑。

## 博客 Logo

我们需要使用 `$blog->logo` 调用图片，但是我们数据库存储的数据是 `public:*.*` 这样的结构，所以，我们打开 `src/Models/Blog.php` 文件，在类内部添加下面的高亮代码：

<<< @/guide/dev/blog/codes/src/Models/Blog.php{8,12,44,45,46,47,48,49,50,51}

## 创建视图

我们在包里面创建一个 `resources/views/blog-profile.blade.php` 文件写入下面的内容：

<<< @/guide/dev/blog/codes/resources/views/blog-profile.blade.php

## 我的博客跳转逻辑

在之前，我们开发了我的博客页面，这个页面假定用户没有博客，显示创建页面，现在，我们开发完了博客主页！所以，我们为之前页面检查用户有博客的情况下进行跳转处理！

打开 `src/Web/Controllers/HomeController.php` 找到 `me` 方法，将里面的内容修改为：

```php
if ($blog = $request->user()->blog) {
    return redirect()->route('blog:profile', ['blog' => $blog]);
}

return view('plus-blog::create-blog');
```

## 页面预览

我们来看看我们创建完成的博客主页是什么样子的吧：

<img :src="$withBase('/assets/img/guide/dev/blog/blog-profile-view.png')" />
