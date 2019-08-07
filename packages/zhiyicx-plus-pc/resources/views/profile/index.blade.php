@section('title') {{ $user['name'] }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/profile.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="left_container">
        {{-- 动态列表 --}}
        <div class="profile_content">
            <div class="profile_menu">
                <a href="javascript:;" class="active">全部</a>
            </div>
            <div id="content_list" class="profile_list"></div>
        </div>
    </div>

    <div class="right_container">
        {{-- 推荐用户 --}}
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.profile.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.weibo.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.picshow.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.mention.js') }}"></script>
<script src="{{ asset('assets/pc/js/md5.min.js') }}"></script>
<script>
$(function(){
    // 加载微博
    var params = {
        type: 'users',
        cate: 1,
        isAjax: true,
        user: {{$user['id']}},
        limit: 15
    };

    loader.init({
        container: '#content_list',
        loading: '.profile_content',
        url: '/users',
        params: params
    });

    $('#content_list').PicShow({
        bigWidth: 815,
        bigHeight: 545
    });
})
</script>
@endsection
