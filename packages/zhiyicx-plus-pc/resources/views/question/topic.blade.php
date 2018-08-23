@section('title') 问答 @endsection
@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/pc/css/question.css') }}" />
@endsection

@section('content')
<div class="p-qa p-topic">
    <div class="left_container">
        <div class="m-nav">
            <a href="{{ route('pc:question') }}">问答</a>
            <a class="cur" href="{{ route('pc:topic') }}">专题</a>
        </div>
        <div class="g-mnc">
            <ul class="m-snav clearfix">
                <li class="cur" type="all"> 全部专题 </li>
                @if(!empty($TS))
                    <li type="follow"> 我关注的 </li>
                @endif
            </ul>
            <div id="J-box" class="m-lst topic_list"></div>
        </div>
        {{-- /问答 --}}
    </div>

    <div class="right_container">
        <div class="g-sdc">
            <a class="u-btn" href="javascript:;" onclick="question.create()">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-publish"></use></svg>提问
            </a>
        </div>
        {{-- 热门问题 --}}
        @include('pcview::widgets.hotquestions')

        {{-- 问答达人排行 --}}
        {{-- @include('pcview::widgets.questionrank') --}}
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/pc/js/module.question.js') }}"></script>
    <script>
        $(function(){
            loader.init({
                container: '#J-box',
                loading: '.g-mnc',
                url: '/question-topics',
                paramtype: 1,
                params: {type: 'all', isAjax: true, limit: 10}
            });
        });
        $('.m-snav li').on('click', function() {
            var type = $(this).attr('type');
            $('#J-box').html('');
            loader.init({
                container: '#J-box',
                loading: '.g-mnc',
                url: '/question-topics',
                paramtype: (type=='all') ? 1 : 0,
                params: {type: type, isAjax: true, limit: 10}
            });
            // 修改样式
            $('.m-snav li').removeClass('cur');
            $(this).addClass('cur');
        });

    </script>
@endsection
