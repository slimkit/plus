@section('title')
圈子
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-idxgroup">
    <div class="left_container g-mn">
        <div class="group_container">
            <div class="group_navbar">
                <a class="active" href="javascript:;" type="all">全部圈子</a>
                <a href="javascript:;" type="nearby">附近圈子</a>
                @if(!empty($TS))
                <a href="javascript:;" type="join">我加入的</a>
                @endif
            </div>
            <div class="m-chip">
                @foreach ($cates as $cate)
                    <span class="u-chip" rel="{{$cate->id}}">{{$cate->name}}</span>
                @endforeach
                @if ($cates->count() > 10)
                    <a class="u-chip cur" id="J-show">查看更多</a>
                @endif
            </div>
            <div class="m-search-area f-dn">
                <input class="search-ipt" id="location" type="text" name="search_key" placeholder="输入关键字搜索">
                <div class="area-searching font14 hide"></div>
                <a class="search-icon" id="J-search-area">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-search"></use></svg>
                </a>
            </div>
            <div class="group_list clearfix" id="group_box">
            </div>
        </div>
    </div>

    <div class="right_container g-side">
        <div class="g-sidec g-sidec-publish f-mb30">
            <a href="javascript:;" id="create-group">
                <div class="u-btn f-mb20">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-label"></use></svg><span>创建圈子</span>
                </div>
            </a>
            <a
                @if (isset($my_group) && !$my_group->isEmpty())
                    href="{{route('pc:postcreate', ['type'=>'outside'])}}"
                @else
                    href="javascript:;" onclick="noticebox('未加入圈子，不能进行发帖', 0)"
                @endif
            >
                <div class="u-btn">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-writing"></use></svg>
                    <span>发帖</span>
                </div>
            </a>
        </div>
        <div class="g-sidec f-mb30">
            <p>共有 <span class="s-fc3 f-fs5">{{ $groups_count }}</span> 个兴趣圈子，等待你的加入！</p>
        </div>
        <!-- 热门圈子 -->
        @include('pcview::widgets.hotgroups')
    </div>
</div>
<div class="f-dn" id="iCenter"></div>
@endsection
@section('scripts')
<script src='//webapi.amap.com/maps?v=1.4.2&key=e710c0acaf316f2daf2c1c4fd46390e3'></script>
<script src="{{ asset('assets/pc/js/module.group.js')}}"></script>
<script>
    $(function(){
        // 是否筛选圈子分类
        var cid = {{ $category_id }};
        if (cid > 0) {
            var chip = $('.u-chip');
            var obj;
            for(let i = 0;i < chip.length; i ++) {
                if ($(chip[i]).attr('rel') == cid) {
                    obj = $(chip[i]);
                    break;
                }
            }
            obj && getList(cid, obj);
        } else {
            loader.init({
                container: '#group_box',
                loading: '.group_container',
                paramtype: 1,
                url: '/groups',
                params: {limit: 10}
            });
        }
        // 创建圈子权限判断
        $('#create-group').on('click', function () {
            checkLogin();
            if (TS.BOOT['group:create'].need_verified && TS.USER.verified == null) {
                ly.confirm(formatConfirm('创建提示', '成功通过平台认证的用户才能创建圈子，是否去认证？'), '去认证' , '', function(){
                    window.location.href = "{{ route('pc:authenticate') }}";
                });
            } else {
                window.location.href = "{{ route('pc:groupcreate') }}";
            }
            return false;
        });
    });

    $('.group_navbar a').on('click', function() {
        var type = $(this).attr('type');
        var params = {type: type, limit: 10};
        (type == 'all') ? $('.m-chip').show() : $('.m-chip').hide();
        $('#group_box').html('');
        if (type == 'nearby') {
            mapObj = new AMap.Map('iCenter');
            mapObj.plugin('AMap.Geolocation', function () {
                geolocation = new AMap.Geolocation();
                mapObj.addControl(geolocation);
                geolocation.getCurrentPosition();
                AMap.event.addListener(geolocation, 'complete', function(poi){
                    params.longitude = poi.position.lng;
                    params.latitude = poi.position.lat;
                    loader.init({
                        container: '#group_box',
                        loading: '.group_container',
                        url: '/groups',
                        paramtype: 1,
                        params: params
                    });
                });
                AMap.event.addListener(geolocation, 'error', function(error){
                    console.log(error)
                    noticebox('定位失败', 0);
                });//返回定位出错信息
            });
        } else {
            loader.init({
                container: '#group_box',
                loading: '.group_container',
                url: '/groups',
                paramtype: 1,
                params: params
            });
        }

        $('.group_navbar a').removeClass('active');
        $(this).addClass('active');
    });

    $("#location").keyup(function(event){
        last = event.timeStamp;
        setTimeout(function(){
            if(last - event.timeStamp == 0){
                location_search();
            }
        }, 500);
    })

    $('.area-searching').on('click', 'a', function() {
        $('#location').val($(this).text());
        $('.area-searching').hide();
    })

    function location_search(event)
    {
        var val = $.trim($("#location").val());
        var area_searching = $(".area-searching");
        area_searching.html('').hide();
        if (val != "") {
            $.ajax({
                type: "GET",
                url: '/api/v2/locations/search',
                data: {
                    name: val
                },
                success: function(res) {
                    if (res.length > 0) {
                        $.each(res, function(key, value) {
                            if (key < 3) {
                                var text = tree(value.tree);
                                var html = '<a>' + text + '</a>';
                                area_searching.append(html);
                            }
                        });
                        area_searching.show();
                    }
                }
            });
        }
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

    // 圈子分类筛选
    $('.m-chip span').on('click', function() {
        var cateid = $(this).attr('rel');
        getList(cateid, $(this));
    });

    function getList(cateid, obj)
    {
        $('#group_box').html('');
        loader.init({
            container: '#group_box',
            loading: '.group_container',
            url: '/groups',
            paramtype: 1,
            params: {category_id: cateid, limit: 10}
        });
        $('.m-chip span').removeClass('cur');
        obj.addClass('cur');
    }
</script>
@endsection