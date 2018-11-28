@section('title')
认证
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
            <div class="account_tab">
                {{-- <div class="perfect_title">
                    <select class="J-authenticate-type type" name="type" >
                        <option value="user">个人认证</option>
                        <option value="org">机构认证</option>
                    </select>
                </div> --}}
                <div class="perfect_title">
                    <div data-value="" class="zy_select t_c gap12">
                        <span>个人认证</span>
                        <ul>
                            <li data-value="user" class="active">个人认证</li>
                            <li data-value="org">机构认证</li>
                        </ul>
                        <i></i>
                        <input id="authtype" type="hidden" value="user" />
                    </div>
                </div>

                {{-- 个人认证 --}}
                <div class="user_authenticate" id="J-input-user">
                    <div class="account_form_row">
                        <label class="w80 required" for="realName"><span style="color: red; ">*</span>真实姓名</label>
                        <input id="realName" name="name" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="IDNumber"><span style="color: red; ">*</span>身份证号码</label>
                        <input  id="IDNumber" name="number" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="contact"><span style="color: red; ">*</span>联系方式</label>
                        <input id="contact" name="phone" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="desc"><span style="color: red; ">*</span>认证描述</label>
                        <textarea class="text_box desc" maxlength="200" rows="1" id="desc-user"></textarea>
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="desc"><span style="color: red; ">*</span>认证资料</label>
                        <div class="upload_file">
                            <span class="file_box">
                                <img id="J-image-preview-front" src="{{ asset('assets/pc/images/upload_zm.png')}}" />
                                <input class="J-file-upload front" type="file" name="file-front" />
                            </span>
                            <span  class="file_box">
                                <img id="J-image-preview-behind" src="{{ asset('assets/pc/images/upload_fm.png')}}" />
                                <input class="J-file-upload behind" type="file" name="file-behind" />
                            </span>
                        </div>
                        <input name="front_id" id="front_id" type="hidden" />
                        <input name="behind_id" id="behind_id" type="hidden" />
                    </div>
                    <div class="account_form_row">
                        <div class="cer_format">附件格式：gif, jpg, jpeg, png;附件大小：不超过10M</div>
                    </div>
                    <div class="perfect_btns">
                        <a class="perfect_btn save J-authenticate-btn" href="javascript:;">保存</a>
                    </div>
                </div>

                {{-- 机构认证 --}}
                <div class="org_authenticate" id="J-input-org" style="display: none;">
                    <div class="account_form_row">
                        <label class="w80 required" for="orgName"><span style="color: red; ">*</span>机构名称</label>
                        <input id="orgName" maxlength="20" name="org_name" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="orgAddress"><span style="color: red; ">*</span>机构地址</label>
                        <input  id="orgAddress" maxlength="50" name="org_address" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="ruler"><span style="color: red; ">*</span>负责人</label>
                        <input  id="ruler" name="name" maxlength="8" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="rulerPhone"><span style="color: red; ">*</span>负责人电话</label>
                        <input id="rulerPhone" name="phone" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="license"><span style="color: red; ">*</span>营业执照号</label>
                        <input  id="license" name="number" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="desc"><span style="color: red; ">*</span>认证描述</label>
                        <textarea rows="1" class="text_box desc" maxlength="200" id="desc-org"></textarea>
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="desc"><span style="color: red; ">*</span>认证资料</label>
                        <div class="upload_file">
                            <span class="file_box">
                                <img id="J-image-preview" src="{{ asset('assets/pc/images/upload_jg.png')}}" />
                                <input class="J-file-upload org" type="file" name="file-front" />
                            </span>
                        </div>
                        <input name="license_id" id="license_id" type="hidden" />
                    </div>
                    <div class="perfect_btns">
                        <a class="perfect_btn save J-authenticate-btn" href="javascript:;">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.account.js')}}"></script>
<script src="{{ asset('assets/pc/js/md5.min.js')}}"></script>
<script>
var authType = 'user';
window.autosize(document.querySelectorAll('textarea'));
$(function() {
    $('.zy_select li').click(function(){
        var type = $(this).data('value');
        $('#authtype').val(type);
        if ($(this).data("value") ==  'user') {
            $('.org_authenticate').hide();
            $('.user_authenticate').show();
            authType = 'user';
        } else {
            $('.user_authenticate').hide();
            $('.org_authenticate').show();
            authType = 'org';
        }
    })
});

/*  提交用户认证信息*/
$('.J-authenticate-btn').on('click', function(e) {
    var getArgs = function() {
        var inp = $('#J-input-'+authType+' input, #J-input-'+authType+' select').toArray();
        var sel;
        for (var i in inp) {
            sel = $(inp[i]);
            args.set(sel.attr('name'), sel.val());
        };
        args.set('desc', $('#desc-'+authType).val());
        args.set('type', $('#authtype').val());

        return args.get();
    };
    if (authType == 'user') {
        if (getArgs().name == '') {
            noticebox('请填写真实姓名', 0);return;
        }
        var reg = /(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        if(reg.test(getArgs().number) === false)
        {
            noticebox('身份证输入不合法', 0);return;
        }
        if (getArgs().phone == '') {
            noticebox('请填写联系方式', 0);return;
        }
        if(/^1[3-9]\d{9}$/.test(getArgs().phone) === false)
        {
            noticebox('手机号码不合法', 0);return;
        }
        if (getArgs().desc == '') {
            noticebox('请填写认证描述', 0);return;
        }
        if (!getArgs().front_id || !getArgs().behind_id) {
            noticebox('请上传证件照', 0);return;
        }
        getArgs().files = [getArgs().front_id, getArgs().behind_id];
    }

    if (authType == 'org') {
        if (getArgs().org_name == '') {
            noticebox('请填写机构名称', 0);return;
        }
        if (getArgs().org_address == '') {
            noticebox('请填写机构地址', 0);return;
        }
        if (getArgs().name == '') {
            noticebox('请填写负责人姓名', 0);return;
        }
        if (getArgs().phone == '') {
            noticebox('请填写负责人电话', 0);return;
        }
        if(/^1[3-9]\d{9}$/.test(getArgs().phone) === false)
        {
            noticebox('手机号码不合法', 0);return;
        }
        if (getArgs().number == '') {
            noticebox('请填写营业执照号', 0);return;
        }
        if (getArgs().desc == '') {
            noticebox('请填写认证描述', 0);return;
        }
        if (!getArgs().license_id) {
            noticebox('请上传证件照', 0);return;
        }
        getArgs().files = [getArgs().license_id];
    }
    axios.post('/api/v2/user/certification', getArgs())
      .then(function (response) {
        noticebox(response.data.message, 1, 'refresh');
      })
      .catch(function (error) {
        showError(error.response.data);
      });
});

$('.J-file-upload').on('change', function(e){
    var file = e.target.files[0];
    if ($(this).hasClass('org')) {
        fileUpload.init(file, function(image, f, file_id){
            $('#license_id').val(file_id);
            $('#J-image-preview').attr('src', image.src);
        });
    }
    if ($(this).hasClass('front')) {
        fileUpload.init(file, function(image, f, file_id){
            $('#front_id').val(file_id);
            $('#J-image-preview-front').attr('src', image.src);
            });
    }
    if ($(this).hasClass('behind')) {
        fileUpload.init(file, function(image, f, file_id){
            $('#behind_id').val(file_id);
            $('#J-image-preview-behind').attr('src', image.src);
        });
    }
});
</script>
@endsection