@extends('list')
@section('nav')
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="javascript:">音乐</a></li>
        <li role="presentation"><a href="{{ route('music:special') }}">专辑</a></li>
        <li role="presentation"><a href="{{ route('music:singers') }}">歌手</a></li>
        <li role="presentation"><a href="{{ route('music:all:comments') }}">评论</a></li>
    </ul>
@endsection
@section('content')
	<div class="panel panel-default">
        <div class="panel-heading">
            音乐检索
            <a href="javascript:;" class="btn btn-link pull-right btn-xs" role="button">
                <span class="glyphicon glyphicon-plus"></span>
                添加歌曲
            </a>
        </div>
    </div>
    <form class="form-horizontal" method="get" action="{{ $base_url }}">
        <h4 class="h4">音乐筛选</h4>
        {{ csrf_field() }}
        <div class="form-group">
            <label for="exampleInputEmail1" class="col-sm-2 control-label">歌曲名称</label>
            <div class="col-sm-6">
                <input type="text" name="name" class="form-control" value="{{ $name }}" placeholder="歌曲名称">
            </div>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1" class="col-sm-2 control-label">专辑名称</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="album" value="{{ $album }}" id="album" placeholder="专辑名称">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-2">
                <button type="submit" class="btn btn-default btn-primary">搜索</button>
            </div>
        </div>
    </form>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>歌曲ID</th>
                <th>歌曲名</th>
                <th>播放数量</th>
                <th>分享数量</th>
                <th>评论数量</th>
                <th>专辑名</th>
                <th>是否付费</th>
                <th>排序权重</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody> </tbody>
    </table>
    <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>
@endsection
