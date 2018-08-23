
@section('title') {{ $group->name }}-圈子资料 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-editgroup p-addgroup p-group">
    <div class="g-bd f-cb">
        <div class="g-sd">
            <ul>
                <a href="{{ route('pc:groupedit', ['group_id'=>$group->id]) }}"><li class="cur">圈子资料</li></a>
                @if ($group->joined->role == 'founder')
                    <a href="{{ route('pc:groupbankroll', ['group_id'=>$group->id]) }}"><li>圈子收益</li></a>
                @endif
                <a href="{{ route('pc:groupmember', ['group_id'=>$group->id]) }}"><li>成员管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group->id]) }}"><li>举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <div class="m-nav">圈子资料</div>
            @if ($group->joined->role == 'founder')
            <div class="m-form">
                <div class="formitm">
                    <input class="chools-cover" id="J-upload-cover" type="file" name="file">
                    <img class="cover" id="J-preview-cover" src="{{ $group->avatar or asset('assets/pc/images/default_group_cover.png') }}">
                    <button class="J-upload-cover-btn change-cover" onclick="$('#J-upload-cover').click();">更改圈子头像</button>
                </div>
                <div class="formitm">
                    <label class="lab">圈子名称</label>
                    <input class="ipt" name="name" type="text"  placeholder="最多 20 个字" value="{{$group->name}}" />
                </div>
                <div class="formitm">
                    <label class="lab">圈子简介</label>
                    <textarea class="txt" name="summary" rows="4" placeholder="最多 255 个字">{{$group->summary}}</textarea>
                </div>
                <div class="formitm">
                    <label class="lab">圈子分类</label>
                    <div data-value="{{$group->category_id}}" class="zy_select t_c gap12" id="categrey">
                        <span>
                            @foreach ($cates as $cate)
                                @if ($group->category_id == $cate->id) {{$cate->name}} @endif
                            @endforeach
                        </span>
                        <ul>
                            @foreach ($cates as $cate)
                                <li data-value="{{$cate->id}}">{{$cate->name}}</li>
                            @endforeach
                        </ul>
                        <i></i>
                        <input id="cate" type="hidden" value="user" />
                    </div>
                </div>
                <div class="formitm">
                    <label class="lab">圈子标签</label>
                    <div class="tags-box ipt">
                        @if (!$group->tags->isEmpty())
                            @foreach ($group->tags as $taged)
                                <span class="tid{{ $taged->id }}" data-id="{{ $taged->id }}">{{ $taged->name }}</span>
                            @endforeach
                        @endif

                        <div class="choos-tags" id="J-tag-box">
                            @foreach ($tags as $tag)
                                <dl>
                                    <dt>{{ $tag->name }}</dt>
                                    @foreach ($tag->tags as $item)
                                        <dd data-id="{{$item->id}}">{{$item->name}}</dd>
                                    @endforeach
                                </dl>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="formitm">
                    <label class="lab">订阅模式</label>
                    <div class="form-control f-ib" id="J-submodel">
                        <input class="regular-radio f-dn" id="radio-free" name="modes" type="radio" value="1" @if (in_array($group->mode, ['public','private']))
                            checked
                        @endif />
                        <label class="radio" for="radio-free"></label>免费&nbsp;&nbsp;
                        <input class="regular-radio f-dn" id="radio-pay" name="modes" type="radio" value="2" @if ($group->mode == 'paid')
                            checked
                        @endif/>
                        <label class="radio" for="radio-pay"></label>付费
                    </div>
                </div>
                <div class="formitm auth-box">
                    <div class="j-sub0 @if (!in_array($group->mode, ['public','private'])) f-dn @endif">
                        <div class="form-control f-mb20">
                            <input class="regular-radio f-dn" id="radio-open" name="mode" type="radio" value="public"
                            @if ($group->mode == 'public' || $group->mode == 'paid') checked @endif />
                            <label class="radio" for="radio-open"></label>公开圈子<span class="f-ml20 s-fc4">加入圈子即可发帖</span>
                        </div>
                        <div class="form-control">
                            <input class="regular-radio f-dn" id="radio-private" name="mode" type="radio" value="private"
                            @if ($group->mode == 'private') checked @endif />
                            <label class="radio" for="radio-private"></label>封闭圈子<span class="f-ml20 s-fc4">未通过加入申请的人不能进入圈子</span>
                        </div>
                    </div>
                    <div class="form-control @if ($group->mode != 'paid') f-dn @endif j-sub1">
                        <label class="lab">设置入圈金额</label>
                        <input min="1" oninput="value=moneyLimit(value)" class="iptline f-mr10" name="money" type="text" value="{{$group->money}}" /><span class="s-fc4">积分</span>
                    </div>
                </div>
                <div class="formitm">
                    <label class="lab">圈子位置</label>
                    <input class="ipt" name="location" type="text" placeholder="输入所在地区" value="{{$group->location}}" />
                </div>
                <div class="formitm">
                    <label class="lab">圈子公告</label>
                    <textarea class="txt" name="notice" rows="6" placeholder="编辑自己的圈子公告或规则（选填）">{{$group->notice}}</textarea>
                </div>
                <div class="formitm">
                    <label class="lab">发帖权限</label>
                    <span class="f-mr20">
                        <input class="regular-radio f-dn" id="radio-qz" name="permissions" type="radio" value="0" @if ($group->permissions == 'founder') checked @endif/>
                        <label class="radio" for="radio-qz"></label>仅圈主
                    </span>
                    <span class="f-mr20">
                        <input class="regular-radio f-dn" id="radio-tt" name="permissions" type="radio" value="1" @if ($group->permissions == 'administrator,founder') checked @endif/>
                        <label class="radio" for="radio-tt"></label>圈主和管理员
                    </span>
                    <span class="f-mr20">
                        <input class="regular-radio f-dn" id="radio-all" name="permissions" type="radio" value="2" @if ($group->permissions == 'member,administrator,founder') checked @endif/>
                        <label class="radio" for="radio-all"></label>全体成员
                    </span>
                </div>
                <div class="formitm">
                    <label class="lab">分享设置</label>
                    <span class="f-mr20">
                        <input class="regular-radio f-dn" id="radio-yes" name="allow_feed" type="radio" value="1"
                        @if ($group->allow_feed) checked @endif />
                        <label class="radio" for="radio-yes"></label>帖子可分享至动态
                    </span>
                    <span class="f-mr20">
                        <input class="regular-radio f-dn" id="radio-no" name="allow_feed" type="radio" value="0"
                        @if (!$group->allow_feed) checked @endif/>
                        <label class="radio" for="radio-no"></label>帖子不可分享至动态
                    </span>
                </div>
                <div class="f-tac">
                    <input type="hidden" name="latitude" value="{{$group->latitude}}" />
                    <input type="hidden" name="longitude" value="{{$group->longitude}}" />
                    <input type="hidden" name="geo_hash" value="{{$group->geo_hash}}" />
                    <button class="btn btn-primary btn-lg f-mt20" id="J-create-group" type="button">提 交</button>
                </div>
            </div>
            @else
            <div class="m-form">
                <div class="formitm f-mt20">
                    <label class="lab">圈子简介</label>
                    <textarea class="txt" name="summary" rows="4" placeholder="最多 255 个字">{{$group->summary}}</textarea>
                </div>
                <div class="formitm">
                    <label class="lab">圈子公告</label>
                    <textarea class="txt" name="notice" rows="6" placeholder="编辑自己的圈子公告或规则（选填）">{{$group->notice}}</textarea>
                </div>
                <div class="f-tac">
                    <button class="btn btn-primary btn-lg f-mt20" id="J-create-group-manager" type="button">提 交</button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<script src='//webapi.amap.com/maps?v=1.4.2&key=e710c0acaf316f2daf2c1c4fd46390e3'></script>
<script src="//webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>
@endsection
<script src="{{ asset('assets/pc/js/geohash.js')}}"></script>
<script src="{{ asset('assets/pc/js/md5.min.js')}}"></script>
@section('scripts')
<script>

$('#J-submodel label').on('click', function(e){
    var val = $('#'+$(this).attr('for')).val();
    if ('{{$group->mode}}' == 'paid') {
        e.preventDefault();
        noticebox('收费圈子不能改成公开和私有圈子', 0);return;
    }
    if (val == '2') {
        $('.j-sub0').hide();
        $('.j-sub1').show();
    } else {
        $('.j-sub0').show();
        $('.j-sub1').hide();
    }
});

$('.j-sub0 label').on('click', function(e){
    var mode = $(this).attr('for');
    if ('{{$group->mode}}' == 'private' && mode == 'radio-open') {
        e.preventDefault();
        noticebox('私有圈子不能改成公开圈子', 0);return;
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

$('#J-upload-cover').on('change', function(e){
    var file = e.target.files[0];
    var a = new FileReader();
    a.onload = function(e) {
        var data = e.target.result;
        $('#J-preview-cover').attr('src', data);
    };
    a.readAsDataURL(file);
});

$('#J-create-group').on('click', function(){
    var name = $('[name="name"]').val();
    var modeType = $('[name="modes"]:checked').val();
    var POST_URL = '/api/v2/plus-group/groups/{{$group->id}}';
    var group = [[ 'founder'], ['administrator', 'founder'], ['member','administrator','founder']];
    var permissions = group[$('[name="permissions"]:checked').val()];
    var formData = new FormData();
        var attrs = {
            summary: $('[name="summary"]').val(),
            notice: $('[name="notice"]').val(),
            location: $('[name="location"]').val(),
            latitude: $('[name="latitude"]').val(),
            longitude: $('[name="longitude"]').val(),
            geo_hash: $('[name="geo_hash"]').val(),
            allow_feed: $('[name="allow_feed"]:checked').val(),
        };
        if ('{{$group->name}}' != name) {
            if (!name || name.length > 20) {
                noticebox('圈子名称长度为1 - 20个字', 0);return;
            }
            formData.append('name', name);
        }
        if (attrs.summary.length > 255) {
            noticebox('圈子简介不能大于255个字', 0);return;
        }
        if (attrs.notice.length > 2000) {
            noticebox('圈子公告不能大于2000个字', 0);return;
        }
        if ($('.tags-box span').length < 1) {
            noticebox('请选择圈子标签', 0);return;
        }
        if (!attrs.location || !attrs.latitude || !attrs.longitude) {
            noticebox('请选择圈子位置', 0);return;
        }
        _.forEach(attrs, function(v, k) {
            formData.append(k, v);
        });
        _.forEach(permissions, function(v, k) {
            formData.append('permissions[]', v);
        });
        if ($('#J-upload-cover')[0].files[0] !== undefined) {
            formData.append('avatar', $('#J-upload-cover')[0].files[0]);
        }
        if (modeType == '1') {
            formData.append('mode', $('[name="mode"]:checked').val());
        } else {
            formData.append('mode', 'paid');
            formData.append('money', $('[name="money"]').val());
        }
        $('.tags-box span').each(function(){
            formData.append('tags[][id]', $(this).data('id'));
        });
        axios.post(POST_URL, formData)
        .then(function (response) {
            noticebox('修改成功~', 1);
        })
        .catch(function (error) {
            showError(error.response.data);
        });
});
$('#J-create-group-manager').on('click', function(){
    var POST_URL = '/api/v2/plus-group/groups/{{$group->id}}';
    var formData = new FormData();
    var attrs = {
        summary: $('[name="summary"]').val(),
        notice: $('[name="notice"]').val(),
    };
    if (attrs.summary.length > 255) {
        noticebox('圈子简介不能大于255个字', 0);
        return;
    }
    if (attrs.notice.length > 2000) {
        noticebox('圈子公告不能大于2000个字', 0);
        return;
    }
    _.forEach(attrs, function(v, k) {
        formData.append(k, v);
    });

    axios.post(POST_URL, formData)
    .then(function (response) {
        noticebox('修改成功~', 1);
    })
    .catch(function (error) {
        showError(error.response.data);
    });
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
</script>
@endsection