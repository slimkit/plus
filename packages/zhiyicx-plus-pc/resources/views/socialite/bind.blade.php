@section('title')
    三方注册
@endsection

@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
    <div class="reg_cont" style="height:640px;">
        <ul class="reg_menu">
            @if(isset($config['bootstrappers']['registerSettings']['type']) && ($config['bootstrappers']['registerSettings']['type'] == 'all' || $config['bootstrappers']['registerSettings']['type'] == 'thirdPart'))
            <li><a href="javascript:" class="current" data-type="register" id="menu_register">新用户注册</a></li>
            @endif
            <li><a href="javascript:" data-type="bind" id="menu_bind">绑定账号</a></li>
        </ul>

            <div class="reg_form">
                <form method="POST" id="auth_form" autocomplete="off">

                    <div id="other_register">
                        <div class="reg_input">
                            <label>设置用户名</label>
                            <span class="input_span">
                            <input type="text" placeholder="2-8个字符" name="name" value="{{ mb_substr($name, 0, 8) }}" maxlength="8"/>
                        </span>
                        </div>
                    </div>
                    <div id="other_bind" style="display: none">
                        <div class="reg_input">
                            <label>账号</label>
                            <span class="input_span">
                            <input type="text" placeholder="手机/邮箱/用户名" name="login" value=""/>
                        </span>
                        </div>
                        <div class="reg_input">
                            <label>密码</label>
                            <span class="input_span">
                            <input type="password" placeholder="输入正确的密码" name="password" value=""/>
                        </span>
                        </div>
                    </div>

                    <input type="hidden" name="access_token" value="{{$access_token}}">
                    <input type="hidden" name="other_type" value="{{$other_type}}">
                    <input type="hidden" name="verifiable_type" id="verifiable_type" value="register">

                    <a id="oauth_btn" class="reg_btn">确定</a>
                </form>
            </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/jquery.form.js') }} "></script>
    <script src="{{ asset('assets/pc/js/module.socialite.js') }} "></script>
    <script>
        $(function () {
            $('.reg_menu').on('click', 'a', function () {
                var _this = this;
                var type = $(_this).data('type');
                $(_this).addClass('current');
                if (type == 'bind') {
                    $("#verifiable_type").val('bind');
                    $("title").html('三方绑定');
                    $('#menu_register').removeClass('current');
                    $('#other_register').hide();
                    $('#other_bind').show();
                } else {
                    $("#verifiable_type").val('register');
                    $("title").html('三方注册');
                    $('#menu_bind').removeClass('current');
                    $('#other_bind').hide();
                    $('#other_register').show();
                }
            })
        })
    </script>
@endsection
