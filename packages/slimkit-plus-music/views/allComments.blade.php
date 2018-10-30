@extends('list')

@section('nav')
<ul class="nav nav-tabs">
    <li role="presentation"><a href="{{ route('music:list') }}">音乐</a></li>
    <li role="presentation"><a href="{{ route('music:special') }}">专辑</a></li>
    <li role="presentation"><a href="{{ route('music:singers') }}">歌手</a></li>
    <li role="presentation" class="active"><a href="#">评论</a></li>
</ul>
@endsection

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        所有评论
        <a href="javascript:history.back();" class="btn btn-link pull-right btn-xs" role="button">
            返回上一页
        </a>
    </div>
</div>
@if ($type === 'music')
<form class="form-horizontal" method="get" onsubmit="return false;">
    <h4 class="h4">简单搜索</h4>

    <div class="form-group">
        <label for="exampleInputEmail1" class="col-sm-2 control-label">关键字</label>
        <div class="col-sm-6">
            <input type="text" name="keyword" class="form-control" value="{{ $keyword }}" placeholder="关键字">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-2">
            <button type="submit" class="btn btn-default btn-primary">搜索</button>
        </div>
    </div>
</form>
@endif
<table class="table table-hover">
        <thead>
            <tr>
                <th>评论ID</th>
                <th>内容</th>
                <th>评论用户</th>
                <th>所属歌曲/专辑</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody> </tbody>
    </table>
    <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>
@endsection
