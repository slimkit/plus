    <div class="login_head">
        <div class="header">
            <div class="nav">
                <div class="nav_left">
                    <a href="{{ route('pc:feeds') }}">
                        <img src="@if(isset($config['common']['logo']) && $config['common']['logo']) {{ $routes['storage'] . $config['common']['logo'] }} @else {{ asset('assets/pc/images/logo.png') }} @endif" class="nav_logo" />
                    </a>
                </div>
                <div class="login_top">
                    @if (Route::currentrouteName() == 'login')
                        @if(isset($config['bootstrappers']['registerSettings']['type']) && $config['bootstrappers']['registerSettings']['type'] == 'all')
                            <a href="{{ route('pc:register', ['type'=>'phone']) }}" class="font16 ">注册</a>
                        @endif
                    @elseif(!($TS ?? 0))
                    <a href="{{ route('login') }}" class="font16 ">登录</a>
                    <a href="{{ route('pc:feeds') }}" class="font16 ">随便看看</a>
                    @endif
                    <a href="http://www.thinksns.com" class="font16 ">TS+官网</a>
                </div>
            </div>
        </div>
    </div>
