@section('title')
安全设置
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="account_container">

    @include('pcview::account.sidebar')

    <div class="account_r">
        <div class="account_c_c">
            <div class="account_tab" id="J-input">
                <div class="perfect_title">
                    <p>{{ $showPassword ? '修改密码' : '设置密码' }}</p>
                </div>
                @if($showPassword)
                    <div class="account_form_row">
                        <label class="w80 required" for="old_password"><font color="red">*</font>原密码</label>
                        <input id="old_password" name="old_password" type="password">
                    </div>
                @endif
                <div class="account_form_row">
                    <label class="w80 required" for="password"><font color="red">*</font>设置新密码</label>
                    <input id="password" name="password" type="password">
                </div>
                <div class="account_form_row">
                    <label class="w80 required" for="password_confirmation"><font color="red">*</font>确认新密码</label>
                    <input id="password_confirmation" name="password_confirmation" type="password">
                </div>
                <div class="perfect_btns">
                    <a class="perfect_btn save" id="J-user-security" href="javascript:;">保存</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$('#J-user-security').on('click', function(){
	var getArgs = function() {
        var inp = $('#J-input input').toArray();
        var sel;
        for (var i in inp) {
            sel = $(inp[i]);
            args.set(sel.attr('name'), sel.val());
        };
        return args.get();
    };
     var data = getArgs();
     if (('{{ $showPassword }}' == '1') && data.old_password.length < 1) {
         noticebox('请输入原密码', 0);
         $('input[name="old_password"]').focus();
         return false;
     }
    if (data.password.length < 6 || data.password.length > 15) {
        noticebox('密码长度必须在6-15个字符', 0);
        $('input[name="password"]').focus();
        return false;
    }
    if (data.password != data.password_confirmation) {
        noticebox('两次密码输入不一致', 0);
        $('input[name="password_confirmation"]').focus();
        return false;
    }

    axios.put('/api/v2/user/password', data)
      .then(function (response) {
        noticebox('密码修改成功', 1, 'refresh');
      })
      .catch(function (error) {
        showError(error.response.data);
      });
});
</script>
@endsection