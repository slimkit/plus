@section('title')话题@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/topic.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}">
@endsection

@section('content')
<div class="m-topic p-topic-index">
    <div class="left">

        <nav class="topic-nav">
            <a @if($type === 'hot')class="active"@endif href='{{ url("/topic?type=hot") }}'>热门</a>
            <a @if($type === 'new')class="active"@endif href='{{ url("/topic?type=new") }}'>最新</a>
        </nav>

        <hr>

        <div class="clearfix">
            @foreach ($list as $topic)
            @include('pcview::topic.widgets.topic_card', ['topic' => $topic])
            @endforeach
        </div>

    </div>
    <div class="right">
        <div class="interaction">
            <button class="ev-btn-create-topic">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic4"></use></svg>
                创建话题
            </button>
            <button class="ev-btn-show-post">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic"></use></svg>
                发动态
            </button>
        </div>

        {{-- TODO: 目前没有话题广告位,用动态广告位代替 (话题右侧广告位) --}}
        @include('pcview::widgets.ads', ['space' => 'pc:feeds:right', 'type' => 1])

        {{-- 热门话题 --}}
        @include('pcview::topic.widgets.hot_topics', ['list' => $list_hot])

    </div>
</div>

<div class="ev-post-feed-dialog" style="display: none;">
    @include('pcview::widgets.postfeed', ['list' => $list_hot])
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.weibo.js') }}"></script>
<script src="{{ asset('assets/pc/js/jquery.uploadify.js') }}"></script>
<script src="{{ asset('assets/pc/js/md5.min.js') }}"></script>
<script>
(function(){

    // 事件绑定工厂
    var eventMap = [
        { el: '.ev-btn-create-topic', on: 'click', fn: gotoCreateTopic },
        { el: '.ev-btn-show-post', on: 'click', fn: popupPostDialog },
    ];
    eventMap.forEach(function(event) {
        $('.p-topic-index').on(event.on, event.el, event.fn);
    });

    function gotoCreateTopic() {
        location.href = '{{ url("/topic/create") }}';
    }

    function popupPostDialog() {
        if (!TS.USER) return location.href= '{{ url("/auth/login") }}';
        layer.open({
            type: 1,
            title: '动态发布',
            area: '600px',
            content: $('.ev-post-feed-dialog')
        })

        // 发布微博
        var up = $('.layui-layer .post_extra').Huploadify({
            auto:true,
            multi:true,
            newUpload:true,
            buttonText:'',
            onUploadSuccess: weibo.afterUpload
        });
    }

})()
</script>
@endsection
