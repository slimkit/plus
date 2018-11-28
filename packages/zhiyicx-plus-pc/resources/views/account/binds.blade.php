@section('title')
    账号管理
@endsection

@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/account.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/cropper/cropper.min.css')}}">
@endsection

@section('content')
    <div class="account_container">

        @include('pcview::account.sidebar')

        <div class="account_r">
            <div class="account_c_c">
                <div class="account_tab" id="J-input">
                    <div class="perfect_title">
                        <p>账号管理</p>
                    </div>

                    <div class="bind-box">
                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定手机</div>
                            @if($phone)
                                <a class="bind-right unbind" data-type="phone">已绑定</a>
                            @else
                                <a class="bind-right blue to_bind" data-type="phone">去绑定</a>
                            @endif

                            <form class="bind-content" autocomplete="off">
                                <div class="bind_form_row">
                                    <label for="phone">手机号</label>
                                    <input id="phone" name="phone" type="text" value="{{ $TS['phone'] }}" autocomplete="off" maxlength="11">
                                    <a data-type="phone" class="send_code @if($TS['phone']) blue-color @endif" href="javascript:">获取验证码</a>
                                </div>
                                <div class="bind_form_row">
                                    <label for="verifiable_code">验证码</label>
                                    <input id="verifiable_code" name="verifiable_code" type="text" value="" autocomplete="off">
                                </div>
                                <div class="bind_form_row form-password">
                                    <label for="password">密码</label>
                                    <input id="password" name="password" type="password" value="" autocomplete="off">
                                </div>
                                <a class="bind-submit" href="javascript:">确定</a>
                            </form>
                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定邮箱</div>

                            @if($email)
                                <a class="bind-right unbind" data-type="email">已绑定</a>
                            @else
                                <a class="bind-right blue to_bind" data-type="email">去绑定</a>
                            @endif

                            <form class="bind-content" autocomplete="off">
                                <div class="bind_form_row">
                                    <label for="email">邮箱账号</label>
                                    <input id="email" name="email" type="text" value="{{ $TS['email'] }}" autocomplete="off">
                                    <a data-type="email" class="send_code @if($TS['email']) blue-color @endif" href="javascript:">获取验证码</a>
                                </div>
                                <div class="bind_form_row">
                                    <label for="verifiable_code">验证码</label>
                                    <input id="verifiable_code" name="verifiable_code" type="text" value="" autocomplete="off">
                                </div>
                                <div class="bind_form_row form-password">
                                    <label for="password">密码</label>
                                    <input id="password" name="password" type="password" value="" autocomplete="off">
                                </div>
                                <a class="bind-submit" href="javascript:">确定</a>
                            </form>
                            {{--<div class="bind-right {{$email ?: 'blue'}}">{{$email ? '已绑定' : '去绑定'}}</div>--}}
                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定QQ</div>
                            @if($qq)
                                <a class="bind-right remove" data-type="qq" data-bind="1">已绑定</a>
                            @else
                                <a class="bind-right blue" href="{{route('pc:socialitebind').'/qq/bind'}}">去绑定</a>
                            @endif
                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定微信</div>
                            @if($wechat)
                                <a class="bind-right remove" data-type="wechat" data-bind="1">已绑定</a>
                            @else
                                <a class="bind-right blue" href="{{route('pc:socialitebind').'/wechat/bind'}}">去绑定</a>
                            @endif
                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定微博</div>
                            @if($weibo)
                                <a class="bind-right remove" data-type="weibo" data-bind="1">已绑定</a>
                            @else
                                <a class="bind-right blue" href="{{route('pc:socialitebind').'/weibo/bind'}}">去绑定</a>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/cropper/cropper.min.js')}}"></script>
    <script src="{{ asset('assets/pc/js/module.account.js')}}"></script>
    <script src="{{ asset('assets/pc/js/md5.min.js')}}"></script>
    <script>
        $('#phone').on('keyup', function () {
            if((/^1(3|4|5|7|8)\d{9}$/.test($(this).val()))){
                $(this).siblings('.send_code').addClass('blue-color');
            } else {
                $(this).siblings('.send_code').hasClass('blue-color') && $('.send_code').removeClass('blue-color');
            }
        });
        $('#email').on('keyup', function () {
            if(/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test($(this).val())){
                $(this).siblings('.send_code').addClass('blue-color');
            } else {
                $(this).siblings('.send_code').hasClass('blue-color') && $('.send_code').removeClass('blue-color');
            }
        });
        // 绑定页面 (手机、邮箱)
        $('.bind-box').on('click', 'a.to_bind', function () {
            var initial_password = "{{ ($TS['password']) ? true : false }}";
            if (!initial_password) {
                ly.confirm(formatConfirm('绑定提示', '绑定手机号码前需先填写密码，是否去设置密码？'), '' , '去设置', function(){
                    window.location.href = '/settings/security?showPassword=-1';
                });

                return false;
            }

            var _this = $(this);
            var type = _this.data('type');
            toBind(_this, type);

            return false;
        });

        // 取消绑定页面 (手机、邮箱)
        $('.bind-box').on('click', 'a.unbind', function () {
            var _this = $(this);
            var type = _this.data('type');
            var isPhone = "{{ $phone || false }}";
            var isEmail = "{{ $email || false }}";
            if (type == 'phone') {
                if (!isEmail) {
                    noticebox('你必须绑定邮箱才能解绑手机哦', 0);
                    return false;
                }
            } else if (type == 'email') {
                if (!isPhone) {
                    noticebox('你必须绑定手机才能解绑邮箱哦', 0);
                    return false;
                }
            }

            unBind(_this, type);
        });

        // 绑定操作 (手机、邮箱)
        function toBind(obj, type) {
            obj.siblings('.bind-content').show('fast');
            obj.addClass('hide');
            obj.siblings('.bind-content').find('.form-password').hide();
            var title = '';
            obj.siblings('.bind-content').find('.bind-submit').on('click', function () {
                var data = getFormJson(obj.siblings('.bind-content'));
                if (type == 'phone') {
                    if(!(/^1(3|4|5|7|8)\d{9}$/.test(data.phone))) {

                        noticebox('请填写正确的手机号', 0);
                        return false;
                    }
                    title = '手机号码';
                } else if(type == 'email') {
                    if(!(/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(data.email))) {

                        noticebox('请填写邮箱账号', 0);
                        return false;
                    }
                    title = '邮箱';
                }
                if (data.verifiable_code.length < 4 || data.verifiable_code.length > 6) {

                    noticebox('验证码不正确', 0);
                    return false;
                }

                axios.put('/api/v2/user', data)
                  .then(function (response) {
                    noticebox('已成功绑定' + title, 1, '/settings/binds');
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            });
        }

        // 取消绑定操作 (手机、邮箱)
        function unBind(obj, type) {
            var bindContent = obj.siblings('.bind-content');
            bindContent.show('fast');
            obj.addClass('hide');
            var title = '';
            bindContent.find('.bind-submit').on('click', function () {
                var data = getFormJson(bindContent);
                if (type == 'phone') {
                    if(!(/^1(3|4|5|7|8)\d{9}$/.test(data.phone))) {

                        noticebox('请填写正确的手机号', 0);
                        return false;
                    }
                    title = '手机号码';
                } else if(type == 'email') {
                    if(!(/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(data.email))) {

                        noticebox('请填写邮箱账号', 0);
                        return false;
                    }
                    title = '邮箱';
                }

                if (data.verifiable_code.length < 4 || data.verifiable_code.length > 6) {

                    noticebox('请输入4-6位验证码', 0);
                    return false;
                }
                if (data.password.length < 1) {

                    noticebox('请填写密码', 0);
                    return false;
                }

                axios.delete('/api/v2/user/'+type, { params: data })
                  .then(function (response) {
                    noticebox('已取消'+title+'绑定', 1, '/settings/binds');
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            })
        }

        // 发送验证码
        $('.send_code').on('click', function () {
            var _this = $(this);
            if (!_this.hasClass('blue-color'))
                return false;
            var type = _this.data('type');
            var bind = _this.parents('.bind-content').siblings('a');
            var url = '';
            var data = {};
            if (bind.hasClass('to_bind')) {
                url = '/api/v2/verifycodes/register';
            } else if (bind.hasClass('unbind')) {
                url = '/api/v2/verifycodes';
            }
            if (type == 'phone') {
                data.phone = $('#phone').val();
                if (!data.phone) {
                    noticebox('请填写手机号', 0);
                    return false;
                }
            } else if (type == 'email') {
                data.email = $('#email').val();
                if (!data.email) {
                    noticebox('请填写邮箱账号', 0);
                    return false;
                }
            }
            axios.post(url, data)
              .then(function (response) {
                var str = '等待<span id="passsec">60</span>秒';
                _this.html(str);
                timeDown(60);
                $('#code').val('');
                noticebox('验证码发送成功', 1);
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        });

        // 验证码倒计时
        var downTimeHandler = null;
        var timeDown = function(timeLeft) {
            clearInterval(downTimeHandler);
            if (timeLeft <= 0) return;
            $('.send_code').removeClass('blue-color');
            $('#passsec').html(timeLeft);
            downTimeHandler = setInterval(function() {
                timeLeft--;
                $('#passsec').html(timeLeft);
                if (timeLeft <= -1) {
                    clearInterval(downTimeHandler);
                    $('.send_code').html('获取验证码').addClass('blue-color');
                }
            }, 1000);
        };

        // 移除三方绑定信息 qq/wechat/weibo
        $('.bind-box').on('click', 'a.remove', function () {
            var _this = $(this);
            var type = _this.data('type');

            if (_this.data('bind') == 1) {
                var url = '/api/v2/user/socialite/'+type;
                axios.delete(url)
                  .then(function (response) {
                    _this.removeClass('remove').addClass('blue').text('去绑定');
                    _this.removeAttr('data-type').removeAttr('data-bind');
                    _this.attr('href', TS.SITE_URL + '/socialite/'+type+'/bind');
                    noticebox('操作成功', 1);
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            }
        });

        function getFormJson(form) {
            var o = {};
            var a = $(form).serializeArray();
            $.each(a, function () {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        }

    </script>
@endsection
