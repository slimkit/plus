@section('title')创建话题 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/cropper/cropper.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/topic.css') }}">
@endsection

@section('content')
<div class="p-topic-create">
    <div class="g-mn">
        <h1 class="u-tt">创建话题</h1>
        <form class="m-form ev-form-create-topic">
        <div class="formitm cover ev-view-cover">
            <div class="default ev-cover-tips">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-ico_upload"></use></svg>
                <br>上传话题封面
            </div>
            <div class="cover">
                <img class="ev-img-cover" />
            </div>
        </div>
        <input type="file" class="hide ev-ipt-upload-file">
        <input type="text" name="logo" class="hide ev-ipt-logo-id">
        <div class="fomritm title">
            <input type="text" maxlength="10" name="name" placeholder="输入话题标题，10字以内（必填）">
        </div>
        <div class="formitm description">
            <input class="ev-ipt-desc" type="text" maxlength="50" name="desc" placeholder="简单介绍一下话题内容">
        </div>
        <div class="formitm word-count">
            <span><span class="ev-current-word-count">0</span>/50</span>
        </div>
        <p class="formitm tips"> 话题创建成功后,标题不可更改 </p>
        <div class="formitm submit-wrap">
            <button class="cancel-btn ev-btn-cancel" type="button"> 取消 </button>
            <button class="submit-btn ev-btn-submit" type="submit"> 提交 </button>
        </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/cropper/cropper.min.js')}}"></script>
<script src="{{ asset('assets/pc/js/md5.min.js')}}"></script>
<script>
(function() {

    // 事件绑定工厂
    var eventMap = [
        { el: '.ev-ipt-desc', on: 'input', fn: onDescInput },
        { el: '.ev-btn-submit', on: 'click', fn: onSubmit },
        { el: '.ev-btn-cancel', on: 'click', fn: onCancel },
        { el: '.ev-view-cover', on: 'click', fn: onCoverClick },
    ]
    eventMap.forEach(function(event) {
        $('.p-topic-create').on(event.on, event.el, event.fn);
    })

    // 描述字段
    function onDescInput() {
        // 字符长度限制
        var val = $(this).val();
        if (val.length > 50) $(this).val(val.slice(0, 50));

        // 更新字数提示
        var $wordCount = $('.ev-current-word-count');
        $wordCount.text($(this).val().length);
    }

    // 提交表单
    function onSubmit(event) {
        event.preventDefault();
        var formData = $('.ev-form-create-topic').serialize();
        axios.post("{{ url('/api/v2/feed/topics') }}", formData, { validatStatus: s => s === 200 })
            .then(function(res) {
                if (res.data.need_review) return noticebox('创建成功，等待审核', 1, '/topic')
                location.href = res.data.id;
            })
            .catch(function(error) {
                showError(error.response.data);
            })
    }

    // 取消创建
    function onCancel() {
        layer.confirm('确定取消创建话题?', function() {
            history.go(-1);
        })
    }

    // 点击封面上传
    function onCoverClick() {
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
        ly.loadHtml(html, '上传封面', '600px', '500px;');
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
                    var croppedCanvas = this.$img.cropper('getCroppedCanvas');
                    var that = this;
                    that.$avatarSave.text('上传中...');
                    croppedCanvas.toBlob(function(blob) {
                        var size = Math.round(blob.size / 1024);
                        if (size >= TS.FILES.upload_max_size) {
                            layer.msg('超出限制大小（'+TS.FILES.upload_max_size+'）KB' );
                            return false;
                        }
                        // 裁切成功后
                        $('.ev-cover-tips').hide();
                        var imgUrl = croppedCanvas.toDataURL(blob)
                        $('.ev-img-cover').attr('src', imgUrl);
                        uploadBlobImage(new File([blob], 'image.png'));
                        layer.closeAll();
                    }, "image/png");
                },
                change: function () {
                    var files,file;
                    if (this.support.datauri) {
                        files = this.$avatarInput.prop('files');
                        if (files.length > 0) {
                            file = files[0];
                            this.fileUpload.mime_type = file.type;
                            this.fileUpload.origin_filename = file.name;
                            this.fileUpload.hash = '123456'+file.name;
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
                            aspectRatio: 800/350, //设置剪裁容器的比例 w/h
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
    }

    function uploadBlobImage(file) {
        var _this = this;

        var reader = new FileReader();
        reader.onload = function(e) {
            var base64 = e.target.result;
            var hash = md5(base64);

            var params = {
                filename: md5(file.name) + '.' + file.name.split('.').splice(-1),
                hash: hash,
                size: file.size,
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
                    data: file,
                }).then(function(res) {
                    // 头像上传成功后，更新用户头像
                    $('.ev-ipt-logo-id').val(node);
                }).catch(function (error) {
                    $('.ev-cover-tips').show();
                    $('.ev-img-cover').removeAttr('src');
                    showError(error.response.data);
                })
            }).catch(function (error) {
                $('.ev-cover-tips').show();
                $('.ev-img-cover').removeAttr('src');
                showError(error.response.data);
            })
        }

        reader.readAsArrayBuffer(file);
    }
})()
</script>
@endsection
