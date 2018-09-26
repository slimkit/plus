@section('title') 回答详情 @endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatMarkdown;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('assets/pc/css/feed.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/pc/css/question.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/pc/markdown/pluseditor.css') }}" />
@endsection

@section('content')
<div class="question_left_container">
    <div class="answer-detail-box bgwhite">
        <dl class="user-box clearfix">
            <dt class="fl">
                @if($answer['anonymity'] == 1 && !(isset($TS) && $TS['id'] == $answer['user_id']))
                    <img class="round" src="{{ asset('assets/pc/images/ico_anonymity_60.png') }}" width="60">
                    @if ($answer['user']['verified'])
                        <img class="role-icon" src="{{ $answer['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                    @endif
                @else
                    <a href="{{ route('pc:mine', $answer['user']['id']) }}">
                        <img class="round" src="{{ getAvatar($answer['user'], 60) }}" width="60">
                        @if ($answer['user']['verified'])
                            <img class="role-icon" src="{{ $answer['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </a>
                @endif
            </dt>
            <dd class="fl user-info">
                @if($answer['anonymity'] == 1 && !(isset($TS) && $TS['id'] == $answer['user_id']))
                    <span href="javascript:;" class="anonymity">匿名用户</span>
                @else
                    <a href="{{ route('pc:mine', $answer['user']['id']) }}" class="tcolor">{{ $answer['user']['name'] }} {{ (isset($TS) && $answer['anonymity'] == 1 && $TS['id'] == $answer['user_id']) ? '（匿名）' : '' }}</a>
                    {{-- <div class="user-tags">
                        @if ($answer['user']['tags'])
                            @foreach ($answer['user']['tags'] as $tag)
                                <span class="tag ucolor">{{ $tag['name'] }}</span>
                            @endforeach
                        @endif
                    </div> --}}
                @endif
            </dd>
            <div class="fr mt20 relative">
                <a href="javascript:;" class="options" onclick="options(this)">
                    <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
                </a>
                <div class="options_div">
                    <div class="triangle"></div>
                    <ul>
                        @if($TS && $answer['user']['id'] == $TS['id'] && !$answer['adoption'] && !$answer['invited'])
                        <li>
                            <a href="{{ route('pc:answeredit', $answer['id']) }}">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-edit"></use></svg>编辑
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="QA.delAnswer({{ $answer['question_id'] }}, {{ $answer['id'] }}, '/questions/{{ $answer['question_id'] }}')">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-delete"></use></svg>删除
                            </a>
                        </li>
                        @elseif($TS && $answer['question']['user_id'] == $TS['id'])
                        <li>
                            @if($answer['adoption'] == 1)
                                <a class="act" href="javascript:;">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-adopt"></use></svg>已采纳
                                </a>
                            @else
                                <a href="javascript:;" onclick="QA.adoptions('{{$answer['question_id']}}', '{{$answer['id']}}', '/questions/{{ $answer['id'] }}')">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-adopt"></use></svg>采纳
                                </a>
                            @endif
                        </li>
                        @else
                            <li>
                                <a href="javascript:;" onclick="reported.init('{{$answer['id']}}', 'question-answer');">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg><span>举报</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </dl>

        <div class="question-title">
            <a href="{{ route('pc:questionread', $answer['question']['id']) }}">{{ $answer['question']['subject'] }}</a>
        </div>

            @if($answer['invited'] == 0 || $answer['question']['look'] == 0 || (isset($TS) && $answer['invited'] == 1 && ($answer['could'] || $answer['question']['user_id'] == $TS['id'] || $answer['user_id'] == $TS['id'])))
                <div class="answer-body markdown-body"> {!! formatMarkdown($answer['body']) !!} </div>
            @else
                <div class="answer-body fuzzy" onclick="QA.look({{ $answer['id'] }}, '{{ $config['bootstrappers']['question:onlookers_amount'] }}' , {{ $answer['question_id'] }})">@php for ($i = 0; $i < 250; $i ++) {echo 'T';} @endphp</div>
            @endif

        <div class="detail_share">
            <span id="J-collect{{ $answer['id'] }}" rel="{{ count($answer['collectors']) }}" status="{{(int) $answer['collected']}}">
                @if($answer['collected'])
                <a class="act" href="javascript:;" onclick="collected.init({{$answer['id']}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                    <font class="cs">{{ count($answer['collectors']) }}</font> 人收藏
                </a>
                @else
                <a href="javascript:;" onclick="collected.init({{$answer['id']}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                    <font class="cs">{{ count($answer['collectors']) }}</font> 人收藏
                </a>
                @endif
            </span>
            <span id="J-likes{{$answer['id']}}" rel="{{ $answer['likes_count'] }}" status="{{(int) $answer['liked']}}">
                @if($answer['liked'])
                <a class="act" href="javascript:;" onclick="liked.init({{$answer['id']}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg>
                    <font>{{ $answer['likes_count'] }}</font> 人喜欢
                </a>
                @else
                <a href="javascript:;" onclick="liked.init({{$answer['id']}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg>
                    <font>{{ $answer['likes_count'] }}</font> 人喜欢
                </a>
                @endif
            </span>
            {{-- 第三方分享 --}}
            <div class="detail_third_share">
                分享至：
                @php
                    // 设置第三方分享图片
                        preg_match('/<img src="(.*?)".*?/', $answer['body'], $imgs);
                        if (count($imgs) > 0) {
                            $share_pic = $imgs[1];
                        } else {
                            $share_pic = '';
                        }
                @endphp
                @include('pcview::widgets.thirdshare' , ['share_url' => route('redirect', ['target' => '/questions/'.$answer['question_id'].'/'.'answers/'.$answer['id']]), 'share_title' => addslashes($answer['body']), 'share_pic' => $share_pic])
            </div>

            {{-- 打賞 --}}
            @php
                $rewards_info['count'] = $answer['rewarder_count'];
                $rewards_info['amount'] = $answer['rewards_amount'];
            @endphp
            @include('pcview::widgets.rewards' , ['rewards_data' => $answer['rewarders'], 'rewards_type' => 'answer', 'rewards_id' => $answer['id'], 'rewards_info' => $rewards_info])
        </div>

        {{-- 评论 --}}
        @include('pcview::widgets.comments', [
            'id' => $answer['id'],
            'comments_count' => $answer['comments_count'],
            'comments_type' => 'question-answers',
            'loading' => '.answer-detail-box',
            'position' => 0,
        ])

    </div>
</div>

<div class="right_container">
    {{-- 回答者信息 --}}
    @if($answer['anonymity'] != 1)
        <div class="answer-author">
            <div class="author-con">
                <div class="author-avatar">
                    <a href="{{ route('pc:mine', $answer['user']['id']) }}">
                        <img src="{{ getAvatar($answer['user']) }}" class="avatar">
                        @if ($answer['user']['verified'])
                            <img class="role-icon" src="{{ $answer['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </a>
                </div>
                <div class="author-right">
                    <div class="author-name"><a href="{{ route('pc:mine', $answer['user']['id']) }}">{{ $answer['user']['name'] }}</a></div>
                    <div class="author-intro">{{ $answer['user']['bio'] ?? '暂无简介~~'}}</div>
                </div>
            </div>
            <div class="author-count">
                <div>提问 <span>{{ $answer['user']['extra']['questions_count'] }}</span></div>
                <div>回答 <span>{{ $answer['user']['extra']['answers_count'] }}</span></div>
            </div>
            @if(!isset($TS) || $TS['id'] != $answer['user']['id'])
                <div class="author-collect">
                    @if($answer['user']['following'])
                        <a href="javascript:;" id="follow" status="1">已关注</a>
                    @else
                        <a href="javascript:;" id="follow" class="followed" status="0">+关注</a>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- 热门问题 --}}
    @include('pcview::widgets.hotquestions')
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.question.js') }}"></script>
<script src="{{ asset('assets/pc/js/qrcode.js') }}"></script>
<script>
$(function(){
    $("img.lazy").lazyload({effect: "fadeIn"});

    // 关注
    $('#follow').click(function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = "{{ $answer['user']['id'] }}";
        follow(status, user_id, _this, afterdata);
    });

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.addClass('followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.removeClass('followed');
        }
    }
})

</script>
@endsection
