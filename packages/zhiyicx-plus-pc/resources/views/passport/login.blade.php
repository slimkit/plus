@section('title')
登录
@endsection

@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="login_container">

    <div class="login_left">
        <img src="@if(isset($config['common']['loginbg']) && $config['common']['loginbg']) {{ $routes['storage'] . $config['common']['loginbg'] }} @else {{ asset('assets/pc/images/login_bg.png') }} @endif"/>
    </div>
    <div class="login_right">
        <form role="form" method="POST" action="{{ url('/auth/login') }}">
            {{ csrf_field() }}
            <div class="login_input">
                <input type="text" placeholder="输入手机号/邮箱/昵称" name="login" value="{{ old('email', old('phone', old('name', old('id', '')))) }}" required autofocus />
            </div>
            <div class="login_input">
                <input type="password" placeholder="输入密码" name="password" required/>
            </div>
            <div class="login_extra">
                <a class="forget_pwd" href="{{ route('pc:findpassword') }}">忘记密码</a>
                <a class="" href="{{ route('pc:dynamiclogin') }}">验证码登录 </a>
            </div>
            <button class="login_button" type="submit">登录</button>
            <!-- <a class="login_button" type="submit">登录</a> -->
        </form>

        <div class="login_right_bottom">
            @if(isset($config['bootstrappers']['registerSettings']['type']) && $config['bootstrappers']['registerSettings']['type'] == 'all')
                <span class="no_account">没有账号？<a href="{{ route('pc:register', ['type'=>'phone']) }}"><span>注册</span></a></span>
            @endif

            @if(isset($config['bootstrappers']['registerSettings']['type']) && ($config['bootstrappers']['registerSettings']['type'] == 'all' || $config['bootstrappers']['registerSettings']['type'] == 'thirdPart'))
            <div class="login_share" >
                三方登录：
                <a href="javascript:" data-type="weibo" class="bind">
                    <svg class="icon icon_weibo" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weibo"></use>
                    </svg>
                </a>
                <a href="javascript:" data-type="qq" class="bind">
                    <svg class="icon icon_qq" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-qq"></use>
                    </svg>
                </a>
                <a href="javascript:" data-type="wechat" class="bind">
                    <svg class="icon icon_weixin" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weixin"></use>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        @if($errors->isNotEmpty())
            noticebox('{{ $errors->first() }}', 0);
        @endif
        @if(isset($config['bootstrappers']['registerSettings']['type']) && ($config['bootstrappers']['registerSettings']['type'] == 'all' || $config['bootstrappers']['registerSettings']['type'] == 'thirdPart'))
            $(function(){
                $('.bind').click('on', function () {
                    var type = $(this).data('type');
                    window.open("/socialite/" + type, "", "height=560, width=700");
                });
            });

            function getToken() {
                window.location.href = '/feeds';
            }

            function toBind(other_type, access_token, name) {
                var _token = $('meta[name="_token"]').attr('content');
                var args = {};
                args.other_type = other_type;
                args.access_token = access_token;
                args.name = name;
                args._token = _token;
                var form = $("<form method='post'></form>"),
                    input;

                form.attr({"action": '/socialite'});

                $.each(args,function(key,value){
                    input = $("<input type='hidden'>");
                    input.attr({"name":key});
                    input.val(value);
                    form.append(input);
                });
                form.appendTo(document.body);
                form.submit();
            }
        @endif
    </script>
@endsection
