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
        <form role="form" method="POST" id="dynamic-form" action="{{ url('/auth/dynamic-login') }}" onsubmit="return beforeSubmit();">
            {{ csrf_field() }}
            <div class="login_input">
                <input type="text" placeholder="输入手机号" name="login" value="{{ old('phone', '') }}" required autofocus maxlength="11" />
            </div>
            <div class="login_input">
                <input type="text" placeholder="输入验证码" name="verifiable_code" maxlength="6" required/>
                <span id="J-get-verify-code" class="get_verify_code">获取验证码</span>
            </div>
            <div class="login_extra">
                <a class="forget_pwd" href="{{ url('/auth/login') }}">使用密码登录</a>
            </div>
            <button class="login_button" type="submit">登录</button>
        </form>

        <div class="login_right_bottom">
            @if(isset($config['bootstrappers']['registerSettings']['type']) && $config['bootstrappers']['registerSettings']['type'] == 'all')
                <span class="no_account">没有账号？<a href="{{ route('pc:register', ['type'=>'phone']) }}"><span>注册</span></a></span>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var verify
        @if($errors->isNotEmpty())
            noticebox('{{ $errors->first() }}', 0);
        @endif
        @if(isset($config['bootstrappers']['registerSettings']['type']) && ($config['bootstrappers']['registerSettings']['type'] == 'all' || $config['bootstrappers']['registerSettings']['type'] == 'thirdPart'))
            function getToken() {
                window.location.href = '/feeds';
            }
        @endif
        var verifyCodeTime = 0;
        $('#J-get-verify-code').on('click', function() {
            if (verifyCodeTime > 0) return false
            var phone = $('input[name="login"]').val()
            if (phone.length !== 11) return noticebox('请输入正确的手机号码', 0)
            var verifyUrl;
            axios.get(TS.API + '/users/' + phone, {validateStatus: function(s) {return s === 200 || s === 404}})
                .then(function(res) {
                    if (res.status === 200 && res.data.phone) {
                        verifyUrl = '/verifycodes'
                        $('#dynamic-form').removeAttr('onsubmit');
                    } else if (res.status === 404) {
                        verifyUrl = '/verifycodes/register'
                        $('#dynamic-form').attr('onsubmit', 'return dynamicSignup("'+ phone +'")')
                    } else return Promise.reject()
                    verifyUrl && axios.post(TS.API + verifyUrl, { phone: phone })
                        .then(function() {
                            noticebox('发送验证码成功')
                            verifyCodeTime = 60
                            $('#J-get-verify-code').html("<span class='gray'>" + verifyCodeTime + 'S后重发</span>')
                            countDown()
                        })
                        .catch(function(err) {
                            noticebox('发送验证码失败', 0)
                        })
                }).catch(function() {
                    noticebox('获取用户信息失败', 0)
                })
        })

        var beforeSubmit = function() {
            return noticebox('请先获取验证码', 0), false;
        }

        var dynamicSignup = function(phone) {
            var code = $('[name="verifiable_code"]').val()
            if (code.length < 4 || code.length > 6) return noticebox('请输入正确的验证码'), false;
            var name = '用户' + Math.random().toString(36).substr(2, 6);

            axios.post(TS.API + '/users', {phone: phone, verifiable_type: 'sms', verifiable_code: code, name: name})
                .then(function (res) {
                    axios.post('/passport/token', {token: res.data.token})
                      .then(function () {
                        noticebox('注册成功，跳转中...', 1, '/passport/perfect');
                      })
                      .catch(function (error) {
                        showError(error.response.data);
                      });
                })
                .catch(function(err) {
                    noticebox('注册失败', 0)
                })
            return false
        }

        var countDown = function () {
            var timer = setInterval(function() {
                verifyCodeTime--;
                $('#J-get-verify-code').html("<span class='gray'>" + verifyCodeTime + 'S后重发</span>')
                if (verifyCodeTime <= 0) {
                    verifyCodeTime = 0
                    $('#J-get-verify-code').html('获取验证码')
                    clearInterval(timer)
                }
            }, 1000)
        }
    </script>
@endsection
