@section('title')
    动态详情
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
@endsection

@section('content')
    <div class="left_container clearfix">
        <div class="feed_left">
            <dl class="user-box clearfix">
                <dt class="fl">
                    <a class="avatar_box" href="{{ route('pc:mine', $user['id']) }}">
                        <img class="round" src="{{ getAvatar($user, 60) }}" width="60">
                        @if($user['verified'])
                            <img class="role-icon"
                                 src="{{ $user['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </a>
                </dt>
                <dd class="fl ml20 body-box">
                    <span class="tcolor">{{ $user['name'] }}</span>
                    <div class="gcolor mt10">{{ getTime($feed['created_at']) }}</div>
                </dd>
                <dd class="fr mt20 relative">
                    <span class="options" onclick="options(this)">
                        <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
                    </span>
                    <div class="options_div">
                        <div class="triangle"></div>
                        <ul>
                            <li>
                                <a href="javascript:;" onclick="repostable.show('feeds', {{$feed['id']}})">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-share"></use></svg>转发
                                </a>
                            </li>
                        @if($user['id'] === ($TS['id'] ?? 0))
                            <li>
                                <a href="javascript:;" onclick="weibo.pinneds({{$feed['id']}});">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pinned2"></use></svg>申请置顶
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-share"></use>
                                    </svg>
                                    转发
                                </a>
                            </li>
                                <li>
                                    <a href="javascript:;" onclick="weibo.delFeed({{$feed['id']}}, 1);">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-delete"></use>
                                        </svg>
                                        删除
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="javascript:;" onclick="reported.init('{{$feed['id']}}', 'feed');">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-report"></use>
                                        </svg>
                                        <span>举报</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </dd>
            </dl>

            @if(!empty($feed['images']))
                <div class="detail_images">
                    @foreach($feed['images'] as $store)
                        {{-- 计算图片高度 --}}
                        @php
                            $size = explode('x', $store['size']);
                            $store_height = $size[0] > 675 ? 675 / $size[0] * $size[1] : $size[1];
                            // 下载付费设置为高斯模糊
                            $blur = (isset($store['type']) && $store['type'] == 'download') ? 96 : 0;
                        @endphp
                        @if(isset($store['paid']) && !$store['paid'])
                            <img style="height:{{ $store_height }}px"
                                 data-original="{{ getImageUrl($store, '', '', false, $blur) }}" class="per_image lazy"
                                 onclick="weibo.payImage(this)" data-node="{{ $store['paid_node'] }}"
                                 data-amount="{{ $store['amount'] }}" data-file="{{ $store['file'] }}"/>
                        @else
                            <a target="_blank" href="{{ getImageUrl($store, '', '', false) }}">
                                <img
                                    style="display: block;"
                                    data-original="{{ getImageUrl($store, '', '', false) }}"
                                    class="per_image lazy"/>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif

            @if($feed['video'])
                <div>
                    <video poster="{{ $routes['storage'] . $feed['video']['cover_id'] }}"
                           src="{{ $routes['storage'] . $feed['video']['video_id'] }}" width="735" height="500"
                           controls="controls">
                    </video>
                </div>
            @endif

            <div class="detail_body">
                {!! formatContent($feed['feed_content']) !!}
            </div>

            @if($feed['repostable_type'])
                @include('pcview::templates.feed_repostable', ['feed' => $feed])
            @endif

            @if(count($feed['topics']))
                <div class="selected-topics" style="margin-bottom: 20px;">
                    @foreach($feed['topics'] as $topic)
                        <a href='javascript:;' onclick="layer.alert(buyTSInfo)"
                           class="selected-topic-item">{{ $topic['name'] }}</a>
                    @endforeach
                </div>
            @endif

            <div class="detail_share">
                <span id="J-collect{{ $feed['id'] }}" rel="{{ $feed['collect_count'] }}"
                      status="{{(int) $feed['has_collect']}}">
                    @if($feed['has_collect'])
                        <a href="javascript:;" onclick="collected.init({{$feed['id']}}, 'feeds', 0);" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                        <font class="cs">{{ $feed['collect_count'] }}</font> 人收藏
                    </a>
                    @else
                        <a href="javascript:;" onclick="collected.init({{$feed['id']}}, 'feeds', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                        <font class="cs">{{ $feed['collect_count'] }}</font> 人收藏
                    </a>
                    @endif
                </span>
                <span id="J-likes{{$feed['id']}}" rel="{{ $feed['like_count'] }}" status="{{(int) $feed['has_like']}}">
                    @if($feed['has_like'])
                        <a href="javascript:;" onclick="liked.init({{$feed['id']}}, 'feeds', 0);" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg>
                        <font>{{ $feed['like_count'] }}</font> 人喜欢
                    </a>
                    @else
                        <a href="javascript:;" onclick="liked.init({{$feed['id']}}, 'feeds', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg>
                        <font>{{ $feed['like_count'] }}</font> 人喜欢
                    </a>
                    @endif
                </span>

                {{-- 第三方分享 --}}
                <div class="detail_third_share">
                    分享至：
                    @php
                        //设置第三方分享图片，若未付费则为锁图。
                        if (count($feed['images']) > 0) {
                            $share_pic = config('app.url') . '/api/v2/files/' . $feed['images'][0]['file'];
                        } else {
                            $share_pic = '';
                        }
                    @endphp
                    @include('pcview::widgets.thirdshare' , ['share_url' => route('redirect', ['target' => '/feeds/'.$feed['id']]), 'share_title' => $feed['feed_content'], 'share_pic' => $share_pic])
                </div>

                {{-- 打賞 --}}
                @if(($config['bootstrappers']['feed']['reward'] ?? false) && $user['id'] !== ($TS['id'] ?? 0))
                    @include('pcview::widgets.rewards' , [
                        'rewards_data' => $feed['rewards'],
                        'rewards_type' => 'feeds',
                        'rewards_id' => $feed['id'],
                        'rewards_info' => $feed['reward'],
                    ])
                @endif
            </div>

            {{-- 评论 --}}
            @include('pcview::widgets.comments', [
                'id' => $feed['id'],
                'comments_count' => $feed['feed_comment_count'],
                'comments_type' => 'feeds',
                'loading' => '.feed_left',
                'position' => 0,
                'top' => 1,
            ])

        </div>
    </div>

    <div class="right_container">
        <div class="right_about">
            <div class="info clearfix">
                <div class="auth_header">
                    <a href="{{ route('pc:mine', $user['id']) }}">
                        <img class="round" src="{{ getAvatar($user, 50) }}" width="50">
                        @if($user['verified'])
                            <img class="role-icon"
                                 src="{{ $user['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </a>
                </div>
                <div class="auth_info">
                    <div class="info_name">
                        <a href="#">{{ $user['name'] }}</a>
                    </div>
                    <div class="info_bio">{{ $user['bio'] ?? '暂无简介' }}</div>
                </div>
            </div>
            <ul class="auth_fans">
                <li>
                    <a href="{{ route('pc:follower', ['user_id' => $user['id']]) }}">粉丝<span>{{ $user['extra']['followers_count'] }}</span></a>
                </li>
                <li>
                    <a href="{{ route('pc:following', ['user_id' => $user['id']]) }}">关注<span>{{ $user['extra']['followings_count'] }}</span></a>
                </li>
            </ul>
        </div>
        {{-- 推荐用户 --}}
        @include('pcview::widgets.recusers')

        {{-- 收入达人排行榜 --}}
        @include('pcview::widgets.incomerank')
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/module.weibo.js') }}"></script>
    <script src="{{ asset('assets/pc/js/module.mention.js') }}"></script>
    <script src="{{ asset('assets/pc/js/qrcode.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $("img.lazy").lazyload({effect: "fadeIn"});
        });
    </script>
@endsection
