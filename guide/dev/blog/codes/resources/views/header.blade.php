<header class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Blog</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="{{ Route::currentRouteName() === 'home' ? 'active' : '' }}">
                <a href="{{ route('home') }}">博客广场</a>
            </li>
            <li class="{{ Route::currentRouteName() === 'blog:me' ? 'active' : '' }}">
                <a href="{{ route('blog:me') }}">我的博客</a>
            </li>
        </ul>
        @include('plus-blog::headers.user')
    </div>
</header>
