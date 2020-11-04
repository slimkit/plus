@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
{{-- 个人中心头部个人信息 --}}
<div class="m-uchead profile_top">
    <div class="profile_top_cover" style="background-image: url({{ $user['bg'] ? $user['bg']['url'] : asset('assets/pc/images/default_cover.jpg') }});background-repeat: no-repeat;background-size: cover;"> </div>

    @if ($user['id'] === ($TS['id'] ?? 0))
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="change_cover" onclick="$('#cover').click()">更换封面</span>
    @endif

    <div class="profile_top_info">
        <div class="profile_top_img relative fl">
            <a href="{{ route('pc:mine', $user['id']) }}">
                <img class="round" src="{{ getAvatar($user, 160) }}"/>
                @if($user['verified'])
                    <img class="role-icon" src="{{ $user['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                @endif
            </a>
        </div>
        <div class="profile_top_info_d">
            <div class="profile_top_user">
                <a href="{{ route('pc:mine', $user['id']) }}">{{ $user['name'] }}</a>
                @if($user['location'])
                <span>{{$user['location'] ?? ''}}</span>&nbsp;&nbsp;|
                @endif
                @if ($user['id'] === ($TS['id'] ?? 0))
                &nbsp;<svg class="icon" aria-hidden="true"><use xlink:href="#icon-currency"></use></svg>{{$user['currency']['sum'] ?? '0'}} {{ $config['bootstrappers']['site']['currency_name']['name'] }}
                @endif
            </div>
            <div class="profile_top_bio">{{ $user['bio'] ?? '这家伙很懒，什么都没留下'}}</div>
            <div class="profile_top_tags">
                @foreach ($user['tags'] as $tag)
                    <span>{{$tag['name']}}</span>
                @endforeach
            </div>
            <div class="u-cert" title="{$user['verified']['description']">{{$user['verified']['description'] ?? ''}}</div>
        </div>
    </div>

    {{-- 个人中心导航栏 --}}
    <div class="profile_nav clearfix">
        @if (($TS['id'] ?? 0) === $user['id'])
            <ul class="profile_nav_list clearfix">
                <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user['id']) }}">主页</a></li>

                <li><a href="javascript:;" onclick="layer.alert(buyTSInfo)">圈子</a></li>

                <li><a href="javascript:;" onclick="layer.alert(buyTSInfo)">问答</a></li>

                <li @if($current == 'news') class="active" @endif><a href="{{ route('pc:profilenews', $user['id']) }}">资讯</a></li>

                <li @if($current == 'collect') class="active" @endif><a href="{{ route('pc:profilecollectfeeds') }}">收藏</a></li>
            </ul>

            <a class="btn btn-primary contribute-btn" href="javascript:;" onclick="layer.alert(buyTSInfo)">
                <svg class="icon"><use xlink:href="#icon-publish"></use></svg>投稿
            </a>
        @else
            <ul class="profile_nav_list clearfix">
                <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user['id']) }}">TA的主页</a></li>

                <li><a href="javascript:;" onclick="layer.alert(buyTSInfo)">TA的圈子</a></li>

                <li><a href="javascript:;" onclick="layer.alert(buyTSInfo)">TA的文章</a></li>

                <li><a href="javascript:;" onclick="layer.alert(buyTSInfo)">TA的问答</a></li>
            </ul>
            <div class="m-option">
                <span class="options" onclick="options(this)">
                    <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more1"></use></svg>
                </span>
                <div class="options_div">
                    <div class="triangle"></div>
                    <ul>
                        <li>
                            <a href="javascript:;" onclick="layer.alert(buyTSInfo)">
                                <svg class="icon"><use xlink:href="#icon-money"></use></svg>
                                <span>打赏</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="reported.init('{{$user['id']}}', 'user');">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg>
                                <span>举报</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <a class="btn profile-btn mcolor" href="javascript:;" onclick="easemob.createCon({{ $user['id'] }})">
                <svg class="icon"><use xlink:href="#icon-messaged"></use></svg>聊天
            </a>
            @if ($user['follower'])
                <a class="btn profile-btn mcolor" id="follow" status="1" uid="{{$user['id']}}" href="javascript:;">
                    <svg class="icon hide"><use xlink:href="#icon-add"></use></svg><span>已关注</span>
                </a>
            @else
                <a class="btn profile-btn mcolor" id="follow" status="0" uid="{{$user['id']}}" href="javascript:;">
                    <svg class="icon"><use xlink:href="#icon-add"></use></svg><span>关注</span>
                </a>
            @endif
        @endif
    </div>
</div>
