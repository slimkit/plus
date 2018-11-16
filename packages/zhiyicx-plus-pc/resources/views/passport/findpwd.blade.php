@section('title')
找回密码
@endsection

@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="reg_cont" style="height:580px;">
    <ul class="reg_menu">
        <li><a href="{{ route('pc:findpassword') }}" @if($type == 'phone') class="current" @endif>手机找回</a></li>
        <li><a href="{{ route('pc:findpassword', ['type'=>'e-mail']) }}" @if($type == 'e-mail') class="current" @endif>邮箱找回</a></li>
    </ul>

    @if($type == 'phone')
    <div class="reg_form">
        <form method="POST" id="findpwd_form">
            <div class="reg_input">
                <label>手机号</label>
                <span class="input_span" id="phone"><input type="text" placeholder="输入11位手机号码" name="phone" maxlength="11"/></span>
            </div>
            <div class="reg_input">
                <label>图形验证码</label>
                <span class="input_span w_280"><input type="text" placeholder="输入图形验证码" name="captchacode" maxlength="6" autocomplete="off"/></span>
                <img onclick="re_captcha()" src="{{ route('pc:captcha', ['tmp'=>1]) }}"  alt="验证码" title="刷新图片" id="captchacode" class="captcha">
            </div>
            <div class="reg_input">
                <label>手机验证码</label>
                <span class="input_span w_280"><input type="text" placeholder="输入手机验证码" name="verifiable_code"autocomplete="off"/></span>
                <span class="get_code" id="smscode" type="findpwd">获取验证码</span>
            </div>
            <div class="reg_input">
                <label>新密码</label>
                <span class="input_span" id="password"><input type="password" placeholder="限6-15个字符，区分大小写" name="password" autocomplete="off"/></span>
            </div>
            <div class="reg_input">
                <label>确认密码</label>
                <span class="input_span" id="repassword"><input type="password" placeholder="再次输入密码" name="repassword" autocomplete="off"/></span>
            </div>
            <input type="hidden" name="verifiable_type" value="sms">

            <a id="findpwd_btn" class="reg_btn">找回</a>
        </form>
    </div>
    @else
    <div class="reg_form">
        <form method="POST" id="findpwd_form">
            <div class="reg_input">
                <label>邮箱</label>
                <span class="input_span" id="email"><input type="text" placeholder="输入邮箱" name="email"/></span>
            </div>
            <div class="reg_input">
                <label>图形验证码</label>
                <span class="input_span w_280"><input type="text" placeholder="输入图形验证码" name="captchacode" maxlength="6" autocomplete="off"/></span>
                <img onclick="re_captcha()" src="{{ route('pc:captcha', ['tmp'=>1]) }}"  alt="验证码" title="刷新图片" id="captchacode" class="captcha">
            </div>
            <div class="reg_input">
                <label>邮箱验证码</label>
                <span class="input_span w_280"><input type="text" placeholder="输入邮箱验证码" name="verifiable_code"autocomplete="off"/></span>
                <span class="get_code" id="smscode" type="findpwd">获取验证码</span>
            </div>
            <div class="reg_input">
                <label>新密码</label>
                <span class="input_span" id="password"><input type="password" placeholder="限6-15个字符，区分大小写" name="password" autocomplete="off"/></span>
            </div>
            <div class="reg_input">
                <label>确认密码</label>
                <span class="input_span" id="repassword"><input type="password" placeholder="再次输入密码" name="repassword" autocomplete="off"/></span>
            </div>
            <input type="hidden" name="verifiable_type" value="mail">

            <a id="findpwd_btn" class="reg_btn">找回</a>
        </form>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/jquery.form.js') }} "></script>
<script src="{{ asset('assets/pc/js/module.passport.js') }} "></script>
@endsection
