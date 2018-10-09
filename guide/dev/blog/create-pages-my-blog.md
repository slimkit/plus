---
title: 我的博客
---

我们优先开发的 Blog 页面是「我的博客」页面，页面功能：

1. 如果用户已经创建了 Blog 那么就 302 到当前登录用户的博客页面
2. 如果用户没有创建 Blog 则显示创建页面

## 创建博客页面

我们先不着急开发跳转判断逻辑，先开发创建博客页面，首先，我们应该定一个路由，我们打开包的 `routes/web.php` 在之前定义的一个函数中定义一个「我的博客」路由：

```php
// 我的博客
    $route
        ->get('me', Web\HomeController::class.'@me')
        ->name('blog:me')
    ;
```

然后我们打开包的 `src/Web/Controllers/HomeController.php` 修改其内容如下：

```php
<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Web\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this
            ->middleware('auth:web')
            ->only(['me']);
    }

    /**
     * Home.
     */
    public function index()
    {
        return view('plus-blog::home');
    }

    /**
     * Create my blog page.
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function me(\Illuminate\Http\Request $request)
    {
        return view('plus-blog::create-blog');
    }
}
```

然后我们打开 `resources/views/header.blade.php` 将 `<li class=""><a href="#">我的博客</a></li>` 替换为下面的内容：

```html
<li class="{{ Route::currentRouteName() === 'blog:me' ? 'active' : '' }}">
    <a href="{{ route('blog:me') }}">我的博客</a>
</li>
```

我们在包的 `resources/views` 下面创建一个名为 `create-blog.blade.php` 的文件，内容如下：

```html
@extends('plus-blog::layout')
@section('title', '创建博客')
@section('container')
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">创建博客</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('blog:me') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- 唯一标识 -->
                        <div class="form-group {{ $errors->has('slug') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">标识</label>
                            <div class="col-sm-10">
                                <input name="slug" type="text" class="form-control" placeholder="博客唯一标识" value="{{ old('slug') }}">
                                <span class="help-block">
                                    {{ 
                                        $errors->has('slug')
                                            ? $errors->first('slug')
                                            : '输入你博客的唯一标识，一旦创建将不再允许修改'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 名称 -->
                        <div class="form-group {{ $errors->has('name') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-10">
                                <input name="name" type="text" class="form-control" placeholder="博客名称" value="{{ old('name') }}">
                                <span class="help-block">
                                    {{ 
                                        $errors->has('name')
                                            ? $errors->first('name')
                                            : '输入博客名称'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 博客描述 -->
                        <div class="form-group {{ $errors->has('desc') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-10">
                                <textarea name="desc" class="form-control" rows="3" placeholder="博客描述">{{ old('desc') }}</textarea>
                                <span class="help-block">
                                    {{ 
                                        $errors->has('desc')
                                            ? $errors->first('desc')
                                            : '请输入你博客的描述，好的描述让你的博客更有魅力哦！'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 博客 Logo -->
                        <div class="form-group {{ $errors->has('logo') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">图标</label>
                            <div class="col-sm-10">
                                <input name="logo" type="file" class="form-control" >
                                <span class="help-block">
                                    {{ 
                                        $errors->has('logo')
                                            ? $errors->first('logo')
                                            : '请选择博客所使用的图标'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 提交按钮 -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">创建</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning" role="alert">你还没有创建属于你的博客，你可以在这里创建一个属于你自己的博客，博客创建完成后你可以发布你的文章了！</div>
        </div>
    </div>
@endsection
```

## 创建博客逻辑

首先，我们应该创建一个「[表单验证](https://laravel-china.org/docs/laravel/5.7/validation/2262)」，我们创建一个文件 `src/Web/Requests/CreateBlog.php`：

```php
<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Web\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBlog extends FormRequest
{
    /**
     * Get request authorize.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom message from validateor rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'min:4', 'max:26', 'regex:/[a-z][a-z0-9]/s', Rule::unique('blogs', 'slug')],
            'name' => ['required', 'string', 'max:100'],
            'desc' => ['nullable', 'string', 'max:250'],
            'logo' => ['nullable', 'image']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'slug.regex' => ':attribute必须是字母开头仅含有字母和数字的小写字符串'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'slug' => '博客唯一标识',
            'name' => '博客名称',
            'desc' => '博客描述',
            'logo' => '博客图标'
        ];
    }
}
```

为了之后的快捷获取，以及创建的时候更加优雅，我们为 `User` 模型附加一个 `blog` 关系，我们打开包的 `src/Providers/ModelServiceProvider.php` 文件，在 `namespace` 下面，插入下面的代码：

```php
use Zhiyi\Plus\Models\User;
use SlimKit\Plus\Packages\Blog\Models\Blog;
```

然后我们找到 `registerUserMacros` 方法，插入下面的内容：

```php
User::macro('blog', function () {
    return $this->hasOne(Blog::class, 'owner_id');
});
```

接下来，我们打开 `src/Web/Controllers/HomeController.php` 在类的 `namespace` 下面添加代码：

```php
use Zhiyi\Plus\FileStorage\Storage;
use SlimKit\Plus\Packages\Blog\Web\Requests\CreateBlog;
```

然后我们在 `__construct` 方法的 `auth:web` 中间件下面的 `only` 数组中增加 `createBlog`。完成后，我们在类里面写入下面的方法：

```php
/**
 * create a blog.
 * @param \SlimKit\Plus\Packages\Blog\Web\Requests\CreateBlog $request
 * @param \Zhiyi\Plus\FileStorage\Storage $storage
 * @return mixed
 */
public function createBlog(CreateBlog $request, Storage $storage)
{
    // 判断是否已有博客，如果有，跳转到博客页面并提示！
    if ($blog = $request->user()->blog) {
        return redirect()
            ->route('blog:profile', ['blog' => $blog])
            ->with('tip', [
                'type' => 'warning',
                'message' => '您已有博客，无法继续创建！'
            ]);
    }
    $blog = new \SlimKit\Plus\Packages\Blog\Models\Blog();
    $blog->slug = $request->input('slug');
    $blog->name = $request->input('name');
    $blog->desc = $request->input('desc');

    // 上传 Logo
    if ($logo = $request->file('logo')) {
        $resource = $storage->createResource(
            'public', $storage->makePath($logo->hashName())
        );
        $storage->put($resource, $logo->get());
        $blog->logo = (string) $resource;
    }

    // 创建 Blog 记录
    $request->user()->blog()->save($blog);
    // 创建成功，跳转到博客页面并提示
    return redirect()
        ->route('blog:profile', ['blog' => $blog])
        ->with('tip', [
            'type' => 'success',
            'message' => '创建博客成功！'
        ])
    ;
}
```

## 跳转功能

上面我们拟定了用户在没有博客的情况下创建了博客，现在我们假设用户已经有了博客，点击将跳转到我的博客页面。

我们打开包的 `routes/web.php` 文件，增加一个路由定义：

```php
// 博客主页
$route
    ->get('{blog}', Web\BlogController::class.'@show')
    ->name('blog:profile');
```

然后我们打开包的博客 Model（`src/Models/Blog.php`），我们修改路由查找使用的 `slug` 进行博客查找，在模型类中写入下面的代码：

```php
/**
 * Get the route key for the model.
 *
 * @return string
 */
public function getRouteKeyName()
{
    return 'slug';
}
```

然后我们创建在包里面创建 `src/Web/Controllers/BlogController.php` 文件：

```php
<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Web\Controllers;

use Illuminate\Routing\Controller;
use SlimKit\Plus\Packages\Blog\Models\Blog as BlogModel;

class BlogController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        // todo.
    }

    /**
     * Get the blog profile.
     * @param SlimKit\Plus\Packages\Blog\Models\Blog $blog
     * @return mixed
     */
    public function show(BlogModel $blog)
    {
        return view('plus-blog::blog-profile', [
            'blog' => $blog,
            'articles' => $blog->articles()->paginate(15),
        ]);
    }
}
```

## 文章关系

在上一节中，我们调用了 `$blog->articles()` 关系，但是这个关系现在还是不存在的，我们打开 `src/Models/Blog.php` 在下面的添加方法：

```php
/**
 * The blog has many articles.
 */
public function articles()
{
    return $this->hasMany(Article::class, 'blog_id');
}
```

好了，我们整个我的博客逻辑就完成了！

## 页面预览

<img :src="$withBase('/assets/img/guide/dev/blog/view-create-blog-page.png')" />

