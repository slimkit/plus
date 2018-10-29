@section('title') 搜索 {{ $keywords }} @endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/topic.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/news.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/user.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/question.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/search.css') }}"/>
@endsection

@section('content')
    <div class="left_container clearfix">
        <div class="search_container">
            <div class="search_nav clearfix">
                <ul class="search_menu">
                    <li><a href="javascript:;" @if($type == 1) class="selected" @endif type="1">动态</a></li>
                    <li><a href="javascript:;" @if($type == 3) class="selected" @endif type="3">文章</a></li>
                    {{--<li><a href="javascript:;" @if($type == 2) class="selected" @endif type="2">问答</a></li>--}}
                    <li><a href="javascript:;" @if($type == 4) class="selected" @endif type="4">用户</a></li>
                    <li>
                        <div type="5" class="zy_select t_c gap12 select-gray" id="J-group">
                            <span @if($type == 5 || $type == 7) class="selected" @endif>{{ $type == 7 ? '帖子' : '圈子' }}</span>
                            <ul>
                                <li type="5" @if($type == 5) class="active" @endif>圈子</li>
                                <li type="7" @if($type == 7) class="active" @endif>帖子</li>
                            </ul>
                            <i></i>
                        </div>
                    </li>
                    <li>
                        <div type="2" class="zy_select t_c gap12 select-gray" id="J-question">
                            <span @if($type == 2 || $type == 6) class="selected" @endif>{{ $type == 6 ? '专题' : '问答' }}</span>
                            <ul>
                                <li type="2" @if($type == 2) class="active" @endif>问答</li>
                                <li type="6" @if($type == 6) class="active" @endif>专题</li>
                            </ul>
                            <i></i>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:;" @if($type == 8) class="selected" @endif type="8">话题</a>
                    </li>
                </ul>

                <div class="search_box">
                    <input class="search_input" type="text" placeholder="输入关键词搜索" value="{{ $keywords ?? ''}}" id="search_input"/>
                    <a class="search_icon">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"></use></svg>
                    </a>
                </div>
            </div>
            <div class="clearfix" id="content_list"></div>
        </div>
    </div>

    <div class="right_container">
        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')

        <!-- 近期热点 -->
        @include('pcview::widgets.hotnews')
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.weibo.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.picshow.js') }}"></script>

<script type="text/javascript">
$(function() {
    var type = '{{ $type }}';
    var keywords = '{{ $keywords }}';
    switchType(type);

    // 用户关注
    $('#content_list').on('click', '.follow_btn', function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = $(this).attr('uid');
        follow(status, user_id, _this, afterdata);
    })

    // 导航切换
    $('.search_menu a').click(function(){
        type = $(this).attr('type');
        $(this).parents('ul').find('a').removeClass('selected');
        !$('#J-question').hasClass('select-gray') && $('#J-question').addClass('select-gray');
        !$('#J-group').hasClass('select-gray') && $('#J-group').addClass('select-gray');
        $(this).addClass('selected');
        keywords = $('#search_input').val();
        switchType(type);
    });

    // 搜索点击
    $('.search_icon').click(function(){
        var val = $('#search_input').val();
        keywords = val;
        switchType(type);
    })

    function switchType(type) {
        switch(type) {
            case '1': // 动态加载
                var params = {
                    type: type,
                    limit: 10,
                    keywords: keywords
                };
                loader.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params
                });
                break;
            case '2': //问答加载
                layer.alert(buyTSInfo)
                return;
            case '3': // 资讯加载
                var params = {
                    type: type,
                    limit: 15,
                    keywords: keywords
                };
                loader.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params
                });
                break;

            case '4': // 用户加载
                var params = {
                    type: type,
                    limit: 10,
                    keywords: keywords
                };
                loader.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params,
                    paramtype: 1
                });
                break;

            case '5': // 圈子加载
                layer.alert(buyTSInfo)
                return;

            case '6': // 专题加载
                layer.alert(buyTSInfo)
                return;

            case '7': // 帖子加载
                layer.alert(buyTSInfo)
                return;

            case '8': // 话题加载
                var params = {
                    type: type,
                    limit: 10,
                    keywords: keywords
                };
                loader.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params,
                    paramtype: 0,
                })
                break;
        };
    }

    $('#content_list').html('');

    $('#J-question li, #J-group li').on('click', function(){
        $(this).parents('ul').find('a').removeClass('selected');
        $(this).removeClass('select-gray');
        type = $(this).attr('type');
        switchType(type);
    });

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.removeClass('followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.addClass('followed');
        }
    };

    $('#content_list').PicShow({
        bigWidth: 815,
        bigHeight: 545
    });
});

</script>
@endsection
