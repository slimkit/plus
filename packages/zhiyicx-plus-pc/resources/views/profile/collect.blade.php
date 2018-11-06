@section('title') {{ $user['name'] }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/profile.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/question.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="left_container">
        {{-- 收藏列表 --}}
        <div class="profile_content">
            <div class="profile_menu J-menu">
                <a @if(route('pc:profilecollectfeeds') == $url) class="active" @endif href="{{ route('pc:profilecollectfeeds') }}">动态</a>
                <a @if(route('pc:profilecollectnews') == $url) class="active" @endif href="{{ route('pc:profilecollectnews') }}">文章</a>
                <a href="javascript:;" onclick="layer.alert(buyTSInfo)">回答</a>
                <a href="javascript:;" onclick="layer.alert(buyTSInfo)">帖子</a>
            </div>
            <div id="content_list" class="profile_list"></div>
        </div>
    </div>

    <div class="right_box">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.profile.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.weibo.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.picshow.js') }}"></script>
<script>
$(function(){
    var type = {{ $type }};
    loader.init({
        container: '#content_list',
        loading: '.profile_content',
        url: '{{ $url }}',
        paramtype: type,
        params: {limit: 10, isAjax: true}
    });

    $('#content_list').PicShow({
        bigWidth: 815,
        bigHeight: 545
    });
});
</script>
@endsection
