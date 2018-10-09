@extends('plus-blog::layout')
@section('title', $blog->name)
@section('head')
    @parent
    <meta name="keywords" content="{{ $blog->name }}" >
    <meta name="description" content="{{ $blog->desc }}">
    <style>
        .blog-logo {
            width: 140px;
            height: 140px;
            background-color: transparent;
            border: 2px solid #fff;
            border-radius: 50%;
            position: relative;
            top: -60px;
        }
        .blog-logo.text {
            display: block;
            font-weight: 500;
            color: #fff;
            text-align: center;
            cursor: default;
            font-size: 108px;
            line-height: 140px;
        }
        .left-blog-box {
            background-color: transparent;
            margin-top: 70px;
            padding: 0 20px 20px;
            padding-bottom: 20px;
        }
        .left-blog-box.color-1 { background-color: #6f5499; }
        .left-blog-box.color-2 { background-color: #d78a2f; }
        .left-blog-box.color-3 { background-color: #9307db; }
        .left-blog-box.color-4 { background-color: #07dbd4; }
        .left-blog-box.color-5 { background-color: #41c13a; }
        .left-blog-box.color-6 { background-color: #0785db; }
        .left-blog-box.color-7 { background-color: #7cc67b; }
        .blog-name {
            font-size: 40px;
            font-weight: 400;
            color: #fff;
            margin-top: 0;
        }
        .blog-desc {
            display: inline-block;
            margin-top: 5px;
            font-size: 16px;
            color: #bdb0d4;
        }
        .blog-state {
            color: #fff;
        }
    </style>
@endsection
@section('container')
    <div class="row">
        <div class="col-md-5">
            <div class="text-center left-blog-box">
                @if ($blog->logo)
                    <img class="blog-logo" src="{{ $blog->logo->url() }}" alt="{{ $blog->name }}">
                @else
                    <span class="blog-logo text center-block">{{ str_limit($blog->name, 1, '') }}</span>
                @endif
                <h2 class="blog-name">{{ $blog->name }}</h2>
                <p class="blog-desc">{{ $blog->desc ?: '😒这个用户太懒，还没有写博客描述！' }}</p>
                <div class="row blog-state">
                    <div class="col-xs-6 text-left">
                        {{ $blog->posts_count }}
                        <span class="glyphicon glyphicon-stats"></span>
                    </div>
                    <div class="col-xs-6 text-right">
                        {{ $blog->latest_post_sent_at }}
                        <span class="glyphicon glyphicon-time"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            @if (session('tip'))
                <div class="alert alert-{{ session('tip')['type'] }} text-center" role="alert">
                    {{ session('tip')['message'] }}
                </div> 
            @endif
            @if($articles->count() > 0)
                <div class="list-group">
                    @foreach($articles as $article)
                        <a href="#" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $article->title }}</h4>
                            <p class="list-group-item-text">{{ str_limit($article->contents, 255, '...') }}</p>
                        </a>
                    @endforeach
                </div>
                {{ $articles->links() }}
            @else
                <div class="alert alert-warning text-center" role="alert">
                    这个博客还没有文章，先去<a href="{{ route('home') }}">博客广场</a>看看吧！
                </div>
            @endif
        </div>
    </div>
@endsection
@push('footer-scripts')
    <script>
        $(function () {
            $('.left-blog-box').addClass('color-' + (Math.floor(Math.random() * 7) + 1));
        });
    </script>
@endpush