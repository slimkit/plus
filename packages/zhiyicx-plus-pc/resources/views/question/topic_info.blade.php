@section('title') 问答 @endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/question.css') }}" />
@endsection

@section('content')
<div class="p-qa">
    <div class="question_left_container">
        <div class="question-topic">
            <div class="topic-info">
                <div class="info-left">
                    <img src="{{ $topic->avatar or asset('assets/pc/images/default_picture.png') }}" width="100px" height="100px">
                </div>
                <div class="info-right">
                    <div class="topic-title">{{ $topic->name }}

                        @if($topic->has_follow)
                        <a class="has-follow followed" href="javascript:;" tid="{{ $topic->id }}" status="1" onclick="QT.follow(this)">已关注</a>
                        @else
                            <a class="has-follow" href="javascript:;" tid="{{ $topic->id }}" status="0" onclick="QT.follow(this)">+关注</a>
                        @endif
                    </div>
                    <div class="topic-foot">
                        <span class="count">关注
                            <font class="mcolor" id="tf-count-{{ $topic->id }}">{{ $topic->follows_count }}</font>
                        </span>
                        <span class="count">问题
                            <font class="mcolor">{{ $topic->questions_count }}</font>
                        </span>
                        {{-- 第三方分享 --}}
                        <span class="show-share">
                            <svg class="icon mr10" aria-hidden="true"><use xlink:href="#icon-share"></use></svg>分享
                        </span>
                        <div class="share-show">
                            分享至：
                            @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:topicinfo', ['topic' => $topic->id]), 'share_title' => $topic->name, 'share_pic' => ($topic->avatar ? $topic->avatar : asset('assets/pc/images/default_picture.png'))])
                            <div class="triangle"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="topic-description">
                <span class="intro">专题简介：</span>
                <span class="h-d">{!! str_limit($topic->description, 250, '...') !!}</span>
                <span class="s-d">{{ $topic->description }}</span>
                @if(strlen($topic->description) > 250)
                    &nbsp; &nbsp; <a href="javascript:;" class="show-description" data-show="0">查看详情</a>
                @endif
            </div>
        </div>

        {{-- 问答 --}}
        <div class="g-mnc">
            <ul class="m-snav clearfix">
                <li class="cur" type="hot"> 热门 </li>
                <li type="excellent"> 精选 </li>
                <li type="reward"> 悬赏 </li>
                <li type="new"> 最新 </li>
                <li type="all"> 全部 </li>
            </ul>
            <div id="J-box" class="m-lst"></div>
        </div>
        {{-- /问答 --}}
    </div>
    <div class="right_container">
        {{-- 相关专家 --}}
        @if($experts->count() > 1)
            <div class="recusers">
                <div class="experts-users-title">
                    <div>相关专家</div>
                </div>
                <ul>
                    @foreach ($experts as $user)
                        <li>
                            <a href="{{ route('pc:mine', $user['id']) }}">
                                <img src="{{ getAvatar($user, 50) }}"/>
                                @if($user->verified)
                                    <img class="role-icon" src="{{ $user->verified['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
                                @endif
                            </a>
                            <span>
                                <a href="{{ route('pc:mine', $user['id']) }}">{{ $user['name'] }}</a>
                            </span>
                        </li>
                    @endforeach
                </ul>
                @if ($experts->count() >= 9)
                    <a class="recmore" href="{{ route('pc:topicexpert', $topic->id) }}">更多相关专家</a>
                @endif
            </div>
        @endif

        {{-- 提问 --}}
        <div class="q_c_post_btn">
            <a href="javascript:;" onclick="question.create({{ $topic->id }})">
            <span>
                <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-publish"></use></svg>提问
            </span>
            </a>
        </div>

        {{-- 热门问题 --}}
        @include('pcview::widgets.hottopics')
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/module.question.js') }}"></script>
    <script>
        var topic_id = "{{ $topic->id }}";
        $(function(){
            loader.init({
                container: '#J-box',
                loading: '.g-mnc',
                url: '/question-topics/' + topic_id,
                paramtype:1,
                loadtype: 1,
                params: {type: 'hot', 'isAjax' : true, limit: 10, topic_id : topic_id}
            });
        });

        // 切换分类
        $('.m-snav li').on('click', function() {
            var type = $(this).attr('type');
            $('#J-box').html('');
            loader.init({
                container: '#J-box',
                loading: '.g-mnc',
                url: '/question-topics/' + topic_id,
                paramtype:1,
                loadtype: 1,
                params: {type: type, 'isAjax' : true, limit: 10, topic_id : topic_id}
            });

            $('.m-snav li').removeClass('cur');
            $(this).addClass('cur');
        });

        $('.show-description').on('click', function () {
            if ($(this).data('show') == 0) {
                $('.h-d').hide();
                $('.s-d').show();
                $(this).data('show', 1);
                $(this).text('收起');
            } else {
                $('.s-d').hide();
                $('.h-d').show();
                $(this).data('show', 0);
                $(this).text('查看详情');
            }
        });
        $('.show-share').click(function(){
            $('.share-show').toggle();
        });
    </script>
@endsection
