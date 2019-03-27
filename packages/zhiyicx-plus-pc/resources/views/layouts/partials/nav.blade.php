
@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<div class="nav nav_border">
    <div class="nav_left">
        <a href="{{ route('pc:feeds') }}">
            <img src="@if(isset($config['common']['logo']) && $config['common']['logo']) {{ $routes['storage'] . $config['common']['logo'] }} @else {{ asset('assets/pc/images/logo.png') }} @endif " class="nav_logo" />
        </a>
    </div>

    {{-- 已登录 --}}
    @if (!empty($TS))
    <div class="nav_right relative">
        <img src="{{ getAvatar($TS, 30) }}" id="menu_toggle" alt="{{ $TS['name'] }}"/>
        @if($TS['verified'])
            <img class="role-icon" src="{{ $TS['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
        @endif
        <span class="font16 nav_name">{{$TS['name']}}</span>

        <div class="nav_menu">
            <div class="hover_cover clearfix">
            </div>
            <div class="triangle"></div>
            <ul>
                <li>
                    <a href="{{ route('pc:mine')}}">个人主页</a>
                </li>
                <li style="border-top: 1px solid #ededed; padding-top: 20px;">
                    <a href="{{ route('pc:wallet') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-wallet"></use></svg>我的钱包
                    </a>
                </li>
                <li>
                    <a href="{{ route('pc:authenticate') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-auth"></use></svg>我的认证
                    </a>
                </li>
                <li>
                    <a href="{{ route('pc:profilecollectfeeds') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-collect"></use></svg>我的收藏
                    </a>
                </li>
                <li>
                    <a href="{{ route('pc:account') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-setting"></use></svg>设置
                    </a>
                </li>
                <li style="border-top: 1px solid #ededed; padding-top: 20px;">
                    <a href="{{ route('logout') }}" id="action-logout">退出</a>
                </li>
            </ul>
        </div>
    </div>
    @else
    {{-- 未登录 --}}
    <div class="nav_right">
        @if(isset($config['bootstrappers']['registerSettings']['type']) && $config['bootstrappers']['registerSettings']['type'] == 'all')
        <a class="nava" href="{{ route('pc:register', ['type'=>'phone']) }}">注册</a>
        @endif
        <a class="nava" href="{{ route('login') }}">登录</a>
    </div>
    @endif

    <div class="nav_list clearfix">
        {{-- 导航 --}}
        <ul class="navs">
            @if (!empty($config['nav']))
            @foreach ($config['nav'] as $nav)
            <li>
                <a target="{{ $nav['target'] }}" href="{{ $nav['url'] }}" @if(!empty($current) && $current == $nav['app_name']) class="selected" @endif>{{ $nav['name']}} </a>
                {{-- 二级导航 --}}
                @php
                    $nav_childs = $nav->items()->get();
                @endphp
                @if (!empty($nav_childs))
                    <div class="child_navs">
                    @foreach ($nav_childs as $child)
                        <a target="{{ $child['target'] }}" href="{{ $child['url'] }}">{{ $child['name']}} </a>
                    @endforeach
                    </div>
                @endif
            </li>
            @endforeach
            @endif
        </ul>

        <div class="nav_search">
            <input autocomplete="off" class="nav_input" type="text" placeholder="输入关键词搜索" value="{{ $keywords ?? ''}}" id="head_search"/>
            <a class="nav_search_icon">
                <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"></use></svg>
            </a>

            <div class="head_search">
                {{-- 搜索历史记录 --}}
                <div class="history">
                    <p>历史记录</p>
                    <ul></ul>
                    <div class="clear">
                        <a href="javascript:;" onclick="delHistory('all')">清空历史记录</a>
                    </div>
                </div>

                {{-- 搜索类型 --}}
                <div class="search_types">
                    <ul>
                        <li type="1"><span>与<span class="keywords"></span>相关的动态</span></li>
                        <li type="2"><span>与<span class="keywords"></span>相关的问答</span></li>
                        <li type="3"><span>与<span class="keywords"></span>相关的文章</span></li>
                        <li type="4"><span>与<span class="keywords"></span>相关的用户</span></li>
                        <li type="5"><span>与<span class="keywords"></span>相关的圈子</span></li>
                        <li type="6"><span>与<span class="keywords"></span>相关的专题</span></li>
                        <li type="8"><span>与<span class="keywords"></span>相关的话题</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
