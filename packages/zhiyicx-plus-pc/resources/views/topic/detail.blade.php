@section('title')话题 - {{ $topic->name }}@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/topic.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}">
@endsection

@section('content')
<div class="m-topic p-topic-detail">
    <div class="left">
        <header>
            <div class="bg" style="background-image: url(/api/v2/files/{{$topic->logo}}?w=815&h=426)"></div>
            <div class="mask">
                <h1>{{ $topic->name }}</h1>
                <p class="author">创建者: {{ $creator->name }}</p>
                <p class="description">{{ $topic->desc }}</p>
            </div>

            @if($creator->id !== $TS['id'])
                <button class="report ev-btn-report">举报</button>
            @else
                <button class="report ev-btn-edit-topic">编辑话题</button>
            @endif

        </header>

        <div class="ev-post-box hide">
            @include('pcview::widgets.postfeed', ['list' => $list_hot, 'default_topic'=> $topic])
        </div>

        {{-- 话题动态列表 --}}
        <div class="ev-feed-list feed-list"> </div>
    </div>
    <div class="right">
        <div class="interaction">
            @if($TS && $TS->id !== $topic->creator_user_id)
            <button class="ev-btn-follow-topic" @if($topic->has_followed)style="display: none;"@endif>
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic"></use></svg>
                关注话题
            </button>
            <button class="ev-btn-unfollow-topic actived" @if(!$topic->has_followed)style="display: none;"@endif>
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic2"></use></svg>
                已关注
            </button>
            @endif
            <button class="ev-btn-show-post">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic3"></use></svg>
                发动态
            </button>
        </div>
        <div class="share-wrap">
            <div class="info">
                <div class="item"><p>{{ $topic->feeds_count }}</p><p><small>动态</small></p></div>
                <div class="item"><p>{{ $topic->followers_count }}</p><p><small>关注</small></p></div>
            </div>
            <div class="share">
                分享至:
                @include('pcview::widgets.thirdshare' , [
                    'color' => '#fff',
                    'share_url' => route('pc:topicDetail', ['topicid_id' => $topic->id]),
                    'share_title' => $topic->name,
                    'share_pic' => ($topic->logo ? getenv('APP_URL') . '/api/v2/files/' . $topic->logo : asset('assets/pc/images/default_picture.png'))
                ])
            </div>
        </div>

        <div class="participant">
            <header>参与话题的人</header>
            <hr>
            <ul>
                @foreach($participants as $user)
                <li>
                    <a href="/users/{{ $user->id }}">
                        <div class="avatar"></div><span class="name">{{ $user->name }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            <footer>更多</footer>
        </div>

        {{-- TODO: 目前没有话题广告位,用动态广告位代替 (话题右侧广告位) --}}
        @include('pcview::widgets.ads', ['space' => 'pc:feeds:right', 'type' => 1])

        {{-- 热门话题 --}}
        @include('pcview::topic.widgets.hot_topics', ['list' => $list_hot])

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.picshow.js') }}"></script>
<script src="{{ asset('assets/pc/js/qrcode.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.weibo.js') }}"></script>
<script src="{{ asset('assets/pc/js/jquery.uploadify.js') }}"></script>
<script src="{{ asset('assets/pc/js/md5.min.js') }}"></script>
<script>
(function() {

    // 加载微博
    var params = {
        isAjax: true,
        topic_id: Number('{{$topic->id}}') || 0,

    };
    loader.init({
        container: '.ev-feed-list',
        loading: '.feed-list',
        url: '/feeds',
        params: params,
        loadtype: 0,
    });

    // 事件绑定工厂
    var eventMap = [
        { el: '.ev-btn-show-post', on: 'click', fn: togglePostBox },
        { el: '.ev-btn-report', on: 'click', fn: reportTopic },
        { el: '.ev-btn-edit-topic', on: 'click', fn: editTopic },
        { el: '.ev-btn-follow-topic', on: 'click', fn: followTopic },
        { el: '.ev-btn-unfollow-topic', on: 'click', fn: unfollowTopic },
    ]
    eventMap.forEach(function(event) {
        $('.p-topic-detail').on(event.on, event.el, event.fn);
    })

    // 发布微博
    var up = $('.post_extra').Huploadify({
        auto:true,
        multi:true,
        newUpload:true,
        buttonText:'',
        onUploadSuccess: weibo.afterUpload
    });

    function togglePostBox() {
        if (!TS.USER) return location.href= '/auth/login';
        $('.ev-post-box').slideToggle();
    }

    function reportTopic() {
        reported.init('{{$topic->id}}', 'topic')
    }

    function editTopic() {
        location.href = '/topic/{{$topic->id}}/edit'
    }

    function followTopic() {
        axios.put('/api/v2/user/feed-topics/{{$topic->id}}')
        $('.ev-btn-follow-topic').hide()
        $('.ev-btn-unfollow-topic').show()
    }

    function unfollowTopic() {
        axios.delete('/api/v2/user/feed-topics/{{$topic->id}}')
        $('.ev-btn-unfollow-topic').hide()
        $('.ev-btn-follow-topic').show()
    }

})()
</script>
@endsection
