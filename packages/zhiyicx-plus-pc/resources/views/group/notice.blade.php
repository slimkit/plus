@section('title')公告详情@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-notice">
    <div class="g-mn">
        <div class="g-hd f-mb30">
            <span class="f-fs5">圈子公告</span>
            <a class="f-fr s-fc" href="javascript:history.go(-1);">返回</a>
        </div>
        <div class="g-bd">
            <p class="s-fc2">{{$group->notice}}</p>
        </div>
    </div>
</div>
@endsection