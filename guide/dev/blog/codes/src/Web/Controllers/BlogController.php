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
