@section('title') {{ $user['name'] }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="left_container">
        {{-- 资讯列表 --}}
        <div class="profile_content">
            <div class="profile_menu J-menu">
            @if ($user['id'] === ($TS['id'] ?? 0))
                <a class="active" href="javascript:;" cid="0">已发布</a>
                <a href="javascript:;" cid="1">投稿中</a>
                <a href="javascript:;" cid="3">被驳回</a>
            @else
                <a class="active" href="javascript:;">TA的文章</a>
            @endif
            </div>
            <div id="content_list" class="profile_list"></div>
        </div>
    </div>

    <div class="right_container">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.profile.js') }}"></script>
<script>
$(function(){
    var params = { type: {{ $type }}, isAjax: true };

    @if ($user['id'] !== ($TS['id'] ?? 0))
        params = { user: {{$user['id']}}, isAjax: true }
    @endif

    loader.init({
        container: '#content_list',
        loading: '.profile_content',
        url: '/users/{{$user['id']}}/news',
        params: params
    });
})

$('.J-menu > a').on('click', function(){
	layer.alert(buyTSInfo)
});
</script>
@endsection
