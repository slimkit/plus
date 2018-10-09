<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Web\Controllers;

use Illuminate\Routing\Controller;
use Zhiyi\Plus\FileStorage\Storage;
use SlimKit\Plus\Packages\Blog\Web\Requests\CreateBlog;

class HomeController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this
            ->middleware('auth:web')
            ->only(['me', 'createBlog']);
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
        if ($blog = $request->user()->blog) {
            return redirect()->route('blog:profile', ['blog' => $blog]);
        }

        return view('plus-blog::create-blog');
    }

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
}
