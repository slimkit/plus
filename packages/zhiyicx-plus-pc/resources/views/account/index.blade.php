@section('title')
基本资料
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@extends('pcview::layouts.default')

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
                    <p>基本资料</p>
                </div>
                <div class="perfect_row mb30">
                    <div class="account_heder">
                        <div class="header">
                            <img id="J-image-preview" src="{{ getAvatar($user, 100) }}">
                            <input id="task_id" name="storage_task_id" type="hidden"/>
                        </div>
                        <a class="perfect_btn" id="J-file-upload-btn" href="javascript:;">更改头像</a>
                    </div>
                </div>
                <div class="perfect_row mb30">
                    <form action="#">
                        <div class="account_form_row">
                            <label for="name">昵称</label>
                            <input id="name" name="name" type="text" value="{{$user['name'] }}" maxlength="8" />
                        </div>
                        <div class="account_form_row">
                            <label for="bio">简介</label>
                            <input id="bio" name="bio" type="text" value="{{$user['bio'] ?? ''}}" />
                        </div>
                        <div class="account_form_row">
                            <label>性别</label>
                            <div class="input">
                            <span>
                                <input @if($user['sex'] == 1) checked="checked" @endif id="male" name="sex" type="radio" value="1" />
                                <label for="male">男</label>
                            </span>
                            <span>
                                <input @if($user['sex'] == 2) checked="checked" @endif id="female" name="sex" type="radio" value="2" />
                                <label for="female">女</label>
                            </span>
                            <span>
                                <input @if($user['sex'] == 0) checked="checked" @endif id="secret" name="sex" type="radio" value="0" />
                                <label for="secret">不方便透露</label>
                            </span>
                            </div>
                        </div>
                        <div class="account_form_row" style="position:relative">
                            <label for="area">地区</label>
                            <input id="location" name="location" type="text" value="{{$user['location'] ?? ''}}" placeholder="请输入地区搜索（例如：成都）" />
                            <div class="area_searching">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="perfect_btns">
                    <a href="javascript:;" class="perfect_btn save" id="J-user-info">保存</a>
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
    // 地区搜索
    var last;
    $("#location").keyup(function(event){
        //利用event的timeStamp来标记时间，这样每次的keyup事件都会修改last的值
        last = event.timeStamp;
        setTimeout(function(){
            if(last - event.timeStamp == 0){
                location_search();
            }
        }, 500);
    });

    $("#location").focus(function() {
        var val = $.trim($("#location").val());
        if (val.length >= 1) {
            location_search();
        }
    });

    $('.area_searching').on('click', 'a', function() {
        $('#location').val($(this).text());
        $('.area_searching').hide();
    });

    function location_search(event)
    {
        var val = $.trim($("#location").val());
        var area_searching = $(".area_searching");
        area_searching.html('').hide();
        if (!val || val == "") { return; }

        axios.get('/api/v2/locations/search', { params: {name:val} })
          .then(function (response) {
            if (response.data.length > 0) {
                $.each(response.data, function(key, value) {
                    if (key < 3) {
                        var text = tree(value.tree);
                        var html = '<div><a>' + text + '</a></div>';
                        area_searching.append(html);
                    }
                });
                area_searching.show();
            }
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    }

    function tree(obj)
    {
        var text = '';
        if (obj.parent != null) {
            text = tree(obj.parent) + ' ' +  obj.name;
        } else {
            text = obj.name;
        }
        return text;
    }


    var username = "{{$user['name'] }}";
    var locate = "{{$user['location'] }}";
    $('#J-image-preview, #J-file-upload-btn').on('click',function(){
        var html = '<div id="model">'
            + '<div class="avatar-container" id="crop-avatar">'
            + '<div class="avatar-upload">'
            + '<input type="hidden" class="avatar-src" name="avatar_src">'
            + '<input type="file" class="avatar-input" id="avatarInput" name="avatar_file">'
            + '<label class="avatar-file" for="avatarInput">选择图片</label>'
            + '</div>'
            + '<div class="avatar-wrapper"></div>'
            + '<div class="save-btn"><span>上传完成记得点击保存按钮</span><button type="button" class="btn btn-primary avatar-save">完成</button></div>'
            + '</div></div>';
        ly.loadHtml(html, '上传头像', '600px', '500px;');
        $(function () {
            'use strict';
            var console = window.console || { log: function () {} };
            function CropAvatar($element) {
                this.$container = $element;
                this.$avatarInput = this.$container.find('.avatar-input');
                this.$avatarWrapper = this.$container.find('.avatar-wrapper');
                this.$avatarPreview = this.$container.find('.avatar-preview');
                this.$avatarSave = this.$container.find('.avatar-save');
                this.init();
            }
            // base64
            function dataURLtoBlob(dataurl) {
                var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                    bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                while(n--){
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new Blob([u8arr], {type:mime});
            }
            // get round avater
            function getRoundedCanvas(sourceCanvas) {
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                var width = sourceCanvas.width;
                var height = sourceCanvas.height;

                canvas.width = width;
                canvas.height = height;
                context.beginPath();
                context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI);
                context.strokeStyle = 'rgba(0,0,0,0)';
                context.stroke();
                context.clip();
                context.drawImage(sourceCanvas, 0, 0, width, height);

                return canvas;
            }
            CropAvatar.prototype = {
                constructor: CropAvatar,
                support: {
                    fileList: !!$('<input type="file">').prop('files'),
                    blobURLs: !!window.URL && URL.createObjectURL,
                    formData: !!window.FormData
                },
                init: function () {
                    this.support.datauri = this.support.fileList && this.support.blobURLs;
                    this.fileUpload = {};
                    this.addListener();
                },
                addListener: function () {
                    this.$avatarInput.on('change', $.proxy(this.change, this));
                    this.$avatarSave.on('click', $.proxy(this.click, this));
                },
                click: function () {
                    if (this.fileUpload.mime_type) {
                        // 默认宽高 160
                        var croppedCanvas = this.$img.cropper('getCroppedCanvas');
                        var roundedCanvas = getRoundedCanvas(croppedCanvas); // 获取圆形头像
                        var dataurl = roundedCanvas.toDataURL('image/png');
                        var blob = dataURLtoBlob(dataurl);
                        /*blob.name = this.fileUpload.origin_filename;
                        this.$avatarSave.text('上传中...');
                        fileUpload.init(blob, updateImg);*/
                        this.upload(blob, dataurl);
                    } else {
                        ly.error('请选择上传文件', false);
                    }
                },
                upload: function(blob, url) {
                    var _this = this;

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var base64 = e.target.result;
                        var hash = md5(base64);

                        _this.$avatarSave.text('上传中...');
                        var params = {
                            filename: md5(_this.fileUpload.origin_filename) + '.' + _this.fileUpload.origin_filename.split('.').splice(-1),
                            hash: hash,
                            size: blob.size,
                            mime_type: 'image/png',
                            storage: { channel: 'public' },
                        }
                        axios.post('/api/v2/storage', params).then(function(res) {
                            var result = res.data;
                            var node = result.node;
                            var instance = axios.create();
                            instance.request({
                                method: result.method,
                                url: result.uri,
                                headers: result.headers,
                                data: blob,
                            }).then(function(res) {
                                // 头像上传成功后，更新用户头像
                                axios.patch('/api/v2/user', {avatar: node}).then(function () {
                                    _this.insert(url);
                                }).catch(function (error) {
                                    showError(error.response.data);
                                })
                            }).catch(function (error) {
                                showError(error.response.data);
                            })
                        }).catch(function (error) {
                            showError(error.response.data);
                        })
                    }
                    var file = new File([blob], _this.fileUpload.origin_filename, {
                        type: 'image/png',
                        lastModified: new Date()
                    })
                    reader.readAsArrayBuffer(file);
                },
                insert: function(src) {
                    $('#J-image-preview').attr('src', src);
                    layer.closeAll();
                    noticebox('修改头像成功', 1);
                },
                change: function () {
                    var files,file;
                    if (this.support.datauri) {
                        files = this.$avatarInput.prop('files');
                        if (files.length > 0) {
                            file = files[0];
                            this.fileUpload.mime_type = file.type;
                            this.fileUpload.origin_filename = file.name;
                            if (this.isImageFile(file)) {
                                if (this.url) {
                                    URL.revokeObjectURL(this.url); // Revoke the old one
                                }
                                this.url = URL.createObjectURL(file);
                                this.startCropper();
                            }
                        }
                    } else {
                        file = this.$avatarInput.val();
                        if (this.isImageFile(file)) {
                            this.syncUpload();
                        }
                    }
                },
                startCropper: function () {
                    var _this = this;
                    if (this.active) {
                        this.$img.cropper('replace', this.url);
                    } else {
                        this.$img = $('<img src="' + this.url + '">');
                        this.$avatarWrapper.empty().html(this.$img);
                        this.$img.cropper({
                            aspectRatio: 1, //设置剪裁容器的比例
                            viewMode: 1,
                            preview: this.$avatarPreview.selector, //添加额外的元素（容器）的预览
                        });
                        this.active = true;
                    }
                },
                stopCropper: function () {
                    if (this.active) {
                        this.$img.cropper('destroy');
                        this.$img.remove();
                        this.active = false;
                    }
                },
                isImageFile: function (file) {
                    if (file.type) {
                        return /^image\/\w+$/.test(file.type);
                    } else {
                        return /\.(jpg|jpeg|png|gif)$/.test(file);
                    }
                }
            };
            return new CropAvatar($('#crop-avatar'));
        });
    });
</script>
@endsection
