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
    <div class="overlayer" onclick="layer.alert(buyTSInfo)"></div>
    <div class="left_container g-mn">
        <div class="group_container">
            <div class="group_navbar">
                <a class="active" href="javascript:;">全部圈子</a>
                <a href="javascript:;">附近圈子</a>
                @if(!empty($TS))
                <a href="javascript:;">我加入的</a>
                @endif
            </div>
            <div class="m-chip">
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
                @if (isset($my_group) && !empty($my_group))
                    href="{{route('pc:postcreate', ['type'=>'outside'])}}"
                @else
                    href="javascript:;"
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
