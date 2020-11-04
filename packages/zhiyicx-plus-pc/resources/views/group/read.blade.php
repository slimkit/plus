@section('title') 圈子-{{ $group['name'] }} @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
@endsection

@php
    use Illuminate\Support\Str;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="p-readgroup">
    <div class="g-mn left_container">
        <div class="g-hd">
            <div class="m-snav f-cb">
               <nav class="m-crumb m-crumb-arr f-ib">
                   <ul class="f-cb s-fc4">
                       <li><a href="{{ route('pc:group') }}">圈子</a></li>
                       <li><a href="{{ route('pc:group', ['category_id' => $group['category']['id']]) }}">{{$group['category']['name']}}</a></li>
                       <li>{{$group['name']}}</li>
                   </ul>
               </nav>
               <div class="m-sch f-fr">
                    <input class="u-schipt" type="text" placeholder="输入关键词搜索">
                    <a class="u-schico" id="J-search" href="javascript:;"><svg class="icon s-fc"><use xlink:href="#icon-search"></use></svg></a>
               </div>
            </div>
            <div class="g-hd-ct">
                <div class="m-ct f-cb">
                    <div class="ct-left">
                        <img src="{{ $group['avatar'] ? $group['avatar']['url'] : asset('assets/pc/images/default_picture.png') }}" height="100%">
                        <span class="ct-cate">{{$group['category']['name']}}</span>
                    </div>
                    <div class="ct-right">
                        <div class="ct-tt">
                            {{$group['name']}}
                            <span class="options" onclick="options(this)">
                                <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
                            </span>
                            <div class="options_div">
                                <div class="triangle"></div>
                                    <ul>
                                        @if($TS['id'] ?? 0)
                                        <li>
                                            <a href="javascript:;" onclick="repostable.show('groups', '{{$group['id']}}')">
                                                <svg class="icon"><use xlink:href="#icon-share"></use></svg> 转发
                                            </a>
                                        </li>
                                        @endif
                                        @php
                                            $share_url = route('redirect', ['target' => '/groups/'.$group['id']]);
                                            $share_title = str_replace(array("\r", "\n"), array('', '\n'), addslashes($group['name']));
                                            $color = "#666";
                                            $share_pic = $group['avatar'] ? $group['avatar']['url'] : asset('assets/pc/images/default_group_cover.png')
                                        @endphp
                                        <li>
                                            <a href="javascript:;" onclick="thirdShare(1, '{{ $share_url }}', '{{ $share_title }}', '{{ $share_pic }}', this)" title="分享到新浪微博">
                                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-weibo" @if($color)fill="{{$color}}"@endif></use></svg>
                                                微博
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" onclick="thirdShare(2, '{{ $share_url }}', '{{ $share_title }}', '{{ $share_pic }}', this)" title="分享到腾讯微博">
                                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-qq" @if($color)fill="{{$color}}"@endif></use></svg>
                                                QQ
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" onclick="thirdShare(3, '{{ $share_url }}', '{{ $share_title }}', '{{ $share_pic }}', this)" title="分享到朋友圈">
                                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-weixin" @if($color)fill="{{$color}}"@endif></use></svg>
                                                微信
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                        </div>
                        @if(strlen($group['summary']) <= 300)
                        <p class="ct-intro">{{$group['summary']}}</p>
                        @else
                        <p class="ct-intro-all">{{$group['summary']}}<span class="ct-intro-more" onclick="grouped.intro(0)">收起</span></p>
                        <p class="ct-intro">{{Str::limit($group['summary'], 160, '...')}}<span class="ct-intro-more" onclick="grouped.intro(1)">显示全部</span></p>
                        @endif

                        <div class="ct-stat">
                            <span>帖子 <font class="s-fc">{{$group['posts_count']}}</font></span>
                            <span>成员 <font class="s-fc" id="join-count-{{$group['id']}}">{{$group['users_count']}}</font></span>
                            <div class="u-poi f-toe">
                                <svg class="icon s-fc2 f-vatb"><use xlink:href="#icon-position"></use></svg>
                                <font class="s-fc">{{$group['location']}}</font>
                            </div>
                            @if ($group['joined'] && ($group['joined']['role'] == 'member') && !$group['joined']['disabled'])
                                <a class="u-report" href="javascript:;" onclick="reported.init({{$group['id']}}, 'group');">举报圈子</a>
                            @endif
                                @if ($group['joined'])
                                    <button
                                        class="joinbtn joined"
                                        id="J-hoverbtn"
                                        gid="{{$group['id']}}"
                                        state="1"
                                        mode="{{$group['mode']}}"
                                        money="{{$group['money']}}"
                                    >已加入</button>
                                @else
                                    <button
                                        class="joinbtn"
                                        gid="{{$group['id']}}"
                                        state="0"
                                        mode="{{$group['mode']}}"
                                        money="{{$group['money']}}"
                                    >+加入</button>
                                @endif
                        </div>
                    </div>
                </div>
                <div class="m-tag">
                    <span>圈子标签</span>
                    @foreach ($group['tags'] as $tag)
                        <span class="u-tag">{{$tag['name']}}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 动态列表 --}}
        <div class="feed_content ev-check-joined">
            {{-- 圈子预览遮罩 --}}
            @if (in_array($group['mode'], ['paid', 'private']) && !$group['joined'])
            <div class="unjoined-cover"></div>
            @endif

            <div class="feed_menu">
                @if (!in_array($group['mode'], ['paid', 'private']) || $group['joined'])
                <a href="javascript:;" rel="post" class="font16 @if($type=='post')selected @endif">最新帖子</a>
                <a href="javascript:;" rel="reply" class="font16 @if($type=='reply')selected @endif">最新回复</a>
                <a href="javascript:;" rel="excellent" class="font16 @if($type=='excellent')selected @endif">精华帖子</a>
                @else
                <a href="javascript:;" rel="preview" class="font16 @if($type=='preview')selected @endif">帖子预览</a>
                @endif
            </div>
            <div id="content_list"></div>
        </div>
    </div>

    <div class="g-side right_container">
        <div class="f-mb30">
            <a
                @if($group['joined'])
                    @if (!Str::contains($group['permissions'], $group['joined']['role']))
                        href="javascript:;" onclick="noticebox('当前圈子没有权限发帖', 0)"
                    @elseif($group['joined']['disabled'])
                        href="javascript:;" onclick="noticebox('用户已被禁用，不能进行发帖', 0)"
                    @else
                        href="{{ route('pc:postcreate', ['group_id'=>$group['id']]) }}"
                    @endif
                @else
                    href="javascript:;" onclick="noticebox('请先加入该圈子', 0)"
                @endif
            >
                <div class="u-btn">
                    <svg class="icon f-vatb" aria-hidden="true"><use xlink:href="#icon-writing"></use></svg>
                    <span>发 帖</span>
                </div>
            </a>
        </div>
        <div class="g-sidec s-bgc">
            <h3 class="u-tt">圈子公告</h3>
            @if(strlen($group['notice']) >= 100)
            <p class="u-ct">{{Str::limit($group['notice'], 100, '...')}}</p>
            @else
            <p class="u-ct">{{$group['notice'] ?? '暂无公告信息'}}</p>
            @endif
        </div>
        <p class="u-more f-csp">
            <a class="f-db" href="{{ route('pc:groupnotice', ['group_id'=>$group['id']]) }}">查看详细公告</a>
        </p>
        @if ($group['joined'] && in_array($group['joined']['role'], ['administrator', 'founder']))
            <div class="g-sidec f-csp f-mb30">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-setting"></use></svg>
                &nbsp;&nbsp;&nbsp;<a href="{{ route('pc:groupedit', ['group_id'=>$group['id']]) }}">
                <span class="f-fs3">圈子管理</span></a>
            </div>
        @endif
        <div class="g-sidec">
            <h3 class="u-tt">圈子成员</h3>
            <dl class="qz-box">
                <dt>
                    <a href="{{ route('pc:mine', $group['founder']['user']['id']) }}">
                        <img class="avatar" src="{{ getAvatar($group['founder']['user']) }}">
                        @if($group['founder']['user']['verified'])
                            <img class="role-icon" src="{{ $group['founder']['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </a>
                </dt>
                @if (($TS['id'] ?? 0) !== $group['founder']['user']['id'])
                <dd>圈主：{{$group['founder']['user']['name']}}</dd>
                <dd>
                    <span class="contact" onclick="easemob.createCon({{ $group['founder']['user']['id'] }})">联系圈主</span>
                </dd>
                @else
                    <dd class="self"><a href="{{ route('pc:mine', $group['founder']['user']['id']) }}">圈主：{{$group['founder']['user']['name']}}</a></dd>
                @endif
            </dl>
            <ul class="cy-box">
                @foreach ($manager as $manage)
                    <li>
                        <a href="{{ route('pc:mine', $manage['user_id']) }}">
                            <img class="avatar" src="{{ getAvatar($manage['user']) }}" width="50">
                            @if($manage['user']['verified'])
                                <img class="role-icon" src="{{ $manage['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                            <p class="f-toe">{{$manage['user']['name']}}</p>
                        </a>
                    </li>
                @endforeach
                @foreach ($members as $member)
                    <li>
                        <a href="{{ route('pc:mine', $member['user_id']) }}">
                            <img class="avatar" src="{{ getAvatar($member['user']) }}" width="50">
                            @if($member['user']['verified'])
                                <img class="role-icon" src="{{ $member['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                            <p class="f-toe">{{$member['user']['name']}}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <p class="u-more f-csp">
            <a class="f-db" href="{{ route('pc:memberpage',['group_id'=>$group['id']]) }}">更多圈子成员</a>
        </p>
        {{-- 热门圈子 --}}
        @include('pcview::widgets.hotgroups')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.picshow.js') }}"></script>
<script src="{{ asset('assets/pc/js/qrcode.js') }}"></script>
<script>
    $(function () {
        // 初始帖子列表
        loader.init({
            container: '#content_list',
            loading: '.feed_content',
            url: '/groups/{{ $group['id'] }}',
            paramtype: 1,
            params: {type:"{{$type}}", isAjax:true, limit:15}
        });

        $('#content_list').PicShow({
            bigWidth: 815,
            bigHeight: 545
        });
    });

    /**
     * 加入圈子检查 如果没有假如圈子，则提示需要加入圈子才能进行更多操作
     */
    $('.ev-check-joined').on('click', function(event) {
        if ("{{(in_array($group['mode'], ['paid', 'private']) && !$group['joined'])}}") {
            noticebox('请先加入圈子', 0);
            return event.stopPropagation(); // 阻止冒泡， 禁止继续点击行为
        }
    })

    // 切换帖子列表
    $('.feed_menu a').on('click', function() {
        $('#J-search').prev('input').val('');
        $('#content_list').html('');
        var type = $(this).attr('rel');
        loader.init({
            container: '#content_list',
            loading: '.feed_content',
            url: '/groups/{{ $group['id'] }}',
            paramtype: 1,
            params: {type: type, isAjax: true, limit: 15},
            callback: function() {
                if (type === 'excellent') {
                    $('.feed_item .excellent').remove();
                }
            }
        });

        $('.feed_menu a').removeClass('selected');
        $(this).addClass('selected');
    });

    // 分享
    $('.u-share').click(function(){
        $('.u-share-show').toggle();
    });

    // 所有帖子搜索
    $('#J-search').on('click', function(){
        var key = $(this).prev('input').val();
        if (!key) {noticebox('搜索关键字不能为空', 0);return;}
        var params = {
            limit: 15,
            keyword: key,
            isAjax: true
        };
        $('#content_list').html('');

        loader.init({
            container: '#content_list',
            loading: '.feed_content',
            url: '/groups/{{ $group['id'] }}',
            paramtype: 1,
            params: params,
            nodatatype: 1
        });
    });
    $("#J-hoverbtn").on('mouseover mouseout', function(e){
        if ($(this).attr('state') == '1') {
            if (e.type == 'mouseover') {
                $(this).text('退 出');
            }
            if (e.type == 'mouseout') {
                $(this).text('已加入');
            }
        }
    });
</script>
@endsection
