@section('title')创建圈子@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/cropper/cropper.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-addgroup">
    <div class="g-mn">
        <h1 class="u-tt">创建圈子</h1>
        <div class="m-form">
            <div class="formitm">
                <img class="cover" src="{{ asset('assets/pc/images/default_group_cover.png') }}" id="J-image-preview" />
            </div>

            <div class="formitm">
                <label class="lab">圈子名称</label>
                <input class="ipt" name="name" type="text"  placeholder="最多 20 个字"/>
            </div>
            <div class="formitm">
                <label class="lab">圈子简介</label>
                <textarea class="txt" name="summary" rows="4" placeholder="最多 255 个字"></textarea>
            </div>
            <div class="formitm">
                <label class="lab">圈子分类</label>
                <div data-value="{{ $cates[0]['id'] ?? 0 }}" class="zy_select t_c gap12" id="categrey">
                    <span>{{ $cates[0]['name'] ?? '请先创建分类' }}</span>
                    <ul>
                        @foreach ($cates as $key => $cate)
                            <li @if($key == 0) class="active" @endif data-value="{{$cate['id']}}">{{$cate['name']}}</li>
                        @endforeach
                    </ul>
                    <i></i>
                    <input id="cate" type="hidden" value="user" />
                </div>
            </div>
            <div class="formitm">
                <label class="lab">圈子标签</label>
                <div class="tags-box ipt">
                    <div class="choos-tags" id="J-tag-box">
                        @foreach ($tags as $tag)
                            <dl>
                                <dt>{{ $tag['name'] }}</dt>
                                @foreach ($tag['tags'] as $item)
                                    <dd data-id="{{$item['id']}}">{{$item['name']}}</dd>
                                @endforeach
                            </dl>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="formitm">
                <label class="lab">订阅模式</label>
                <div class="form-control f-ib" id="J-submodel">
                    <input class="regular-radio f-dn" id="radio-free" name="modes" type="radio" value="1" checked />
                    <label class="radio" for="radio-free"></label>免费&nbsp;&nbsp;
                    <input class="regular-radio f-dn" id="radio-pay" name="modes" type="radio" value="2"/>
                    <label class="radio" for="radio-pay"></label>付费
                </div>
            </div>
            <div class="formitm auth-box">
                <div class="j-sub0">
                    <div class="form-control f-mb20">
                        <input class="regular-radio f-dn" id="radio-open" name="mode" type="radio" value="public" checked />
                        <label class="radio" for="radio-open"></label>公开圈子<span class="f-ml20 s-fc4">加入圈子即可发帖</span>
                    </div>
                    <div class="form-control">
                        <input class="regular-radio f-dn" id="radio-private" name="mode" type="radio" value="private" />
                        <label class="radio" for="radio-private"></label>私密圈子<span class="f-ml20 s-fc4">未通过加入申请的人不能进入圈子</span>
                    </div>
                </div>
                <div class="form-control f-dn j-sub1">
                    <label class="lab">设置入圈金额</label>
                    <input min="1" oninput="value=moneyLimit(value)" class="iptline" name="money" type="text"/>&nbsp;&nbsp;<span class="s-fc4">积分</span>
                </div>
            </div>
            <div class="formitm">
                <label class="lab">分享设置</label>
                <span class="f-mr20">
                    <input class="regular-radio f-dn" id="radio-yes" name="allow_feed" type="radio" value="1" checked />
                    <label class="radio" for="radio-yes"></label>帖子可分享至动态
                </span>
                <span class="f-mr20">
                    <input class="regular-radio f-dn" id="radio-no" name="allow_feed" type="radio" value="0" />
                    <label class="radio" for="radio-no"></label>帖子不可分享至动态
                </span>
            </div>
            <div class="formitm">
                <label class="lab">圈子位置</label>
                <input class="ipt" name="location" type="text" placeholder="输入所在地区" />
            </div>
            <div class="formitm">
                <label class="lab">圈子公告</label>
                <textarea class="txt" name="notice" rows="6" placeholder="编辑自己的圈子公告或规则（选填）"></textarea>
            </div>
            <div class="formitm">
                <label class="lab">&nbsp;</label>
                <input class="iptck" type="checkbox" name="protocol" checked><span>我已阅读并遵守ThinkSNS+的圈子创建协议</span>
            </div>
            <p class="tooltips">提交后，我们将在2个工作日内给予反馈，谢谢合作！</p>
            <div class="f-tac">
                <input type="hidden" name="latitude" value="" />
                <input type="hidden" name="longitude" value="" />
                <input type="hidden" name="geo_hash" value="" />
                <button class="btn btn-primary btn-lg" id="J-create-group" type="button">提 交</button>
            </div>
        </div>
    </div>
</div>
@endsection
<script src='//webapi.amap.com/maps?v=1.4.2&key=e710c0acaf316f2daf2c1c4fd46390e3'></script>
<script src="//webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>
@section('scripts')
<script src="{{ asset('assets/pc/cropper/cropper.min.js')}}"></script>
<script src="{{ asset('assets/pc/js/geohash.js')}}"></script>
<script src="{{ asset('assets/pc/js/md5.min.js')}}"></script>
<script>
var avatarBlob = null

$('#J-submodel label').on('click', function(e){
    var val = $('#'+$(this).attr('for')).val();
    if (val == '2') {
        $('.j-sub0').hide();
        $('.j-sub1').show();
    } else {
        $('.j-sub0').show();
        $('.j-sub1').hide();
    }
});

var selBox = $('.tags-box');
$('#J-tag-box dd').on('click', function(e){
    e.stopPropagation();
    var tid = $(this).data('id');
    var name = $(this).text();
    if (selBox.find('span').hasClass('tid'+tid)) {
        noticebox('标签已存在', 0); return;
    }

    if (selBox.find('span').length > 4) {
        noticebox('标签最多五个', 0); return;
    }
    selBox.append('<span class="tid'+tid+'" data-id="'+tid+'">'+name+'</span>');
});
selBox.on('click', 'span', function(){
    $(this).remove();
});

$('#J-create-group').on('click', function(){
    var protocol = $('[name="protocol"]:checked').val();
    var categrey = $('#categrey').data('value');
    var modeType = $('[name="modes"]:checked').val();
    var POST_URL = '/api/v2/plus-group/categories/' + categrey + '/groups';
    var formData = new FormData();
        var attrs = {
            avatar: avatarBlob,
            name: $('[name="name"]').val(),
            summary: $('[name="summary"]').val(),
            notice: $('[name="notice"]').val(),
            location: $('[name="location"]').val(),
            latitude: $('[name="latitude"]').val(),
            longitude: $('[name="longitude"]').val(),
            geo_hash: $('[name="geo_hash"]').val(),
            allow_feed: $('[name="allow_feed"]:checked').val(),
        };
        if (!categrey) {
            noticebox('请选择圈子分类', 0);return;
        }
        if (avatarBlob == null) {
            noticebox('请选上传圈子头像', 0);return;
        }
        if (!attrs.name || attrs.name.length > 20) {
            noticebox('圈子名称长度为1 ~ 20个字', 0)
            ;return;
        }
        if (attrs.summary.length > 255) {
            noticebox('圈子简介不能超过255个字', 0);
            return;
        }
        if (attrs.notice.length > 2000) {
            noticebox('圈子公告不能大于2000个字', 0);
            return;
        }
        if ($('.tags-box span').length < 1) {
            noticebox('请选择圈子标签', 0);
            return;
        }
        if (!attrs.location || !attrs.latitude || !attrs.longitude) {
            noticebox('请选择圈子位置', 0);
            return;
        }
        if (attrs.notice.length > 2000) {
            noticebox('圈子公告不能超过2000字', 0);
            return;
        }
        _.forEach(attrs, function(v, k) {
            formData.append(k, v);
        });
        if (modeType == '1') {
            formData.append('mode', $('[name="mode"]:checked').val());
        } else {
            formData.append('mode', 'paid');
            if (!$('[name="money"]').val()) {
                noticebox('请设置付费金额', 0);return;
            }
            formData.append('money', $('[name="money"]').val());
        }
        $('.tags-box span').each(function(){
            formData.append('tags[][id]', $(this).data('id'));
        });
        if (protocol !== undefined) {
            axios.post(POST_URL, formData)
            .then(function (response) {
                noticebox('发布成功，请等待审核', 1, '/group');
            })
            .catch(function (error) {
                showError(error.response.data);
            });
        } else {
            noticebox('请勾选同意ThinkSNS+的圈子创建协议', 0);
        }
});
$('[name="location"]').on('click', function(){
    var _this = this;
    getMaps(function(poi){
        $('[name="latitude"]').val(poi.location.lat);
        $('[name="longitude"]').val(poi.location.lng);
        $('[name="geo_hash"]').val(encodeGeoHash(poi.location.lat, poi.location.lng));
        $(_this).val(poi.district+poi.address);
    });
})

$('#J-image-preview').on('click',function(){
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
                    $('#J-image-preview').attr('src', croppedCanvas.toDataURL(blob));
                    avatarBlob = blob;
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
