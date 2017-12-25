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
            <a href="{{ route('music:add') }}" class="btn btn-link pull-right btn-xs" role="button">
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
        <tbody>
            @foreach ($music as $m)
                <tr id="music_{{$m->id}}">
                    <td>{{ $m->id }}</td>
                    <td>{{ $m->title }}</td>
                    <td>{{ $m->taste_count  }}</td>
                    <td>{{ $m->share_count  }}</td>
                    <td><a href="{{ route('music:comments', ['music' => $m->id]) }}">{{ $m->comment_count }}条</a></td>
                    <td>
                        @if ($m->musicSpecials)
                            @foreach ($m->musicSpecials as $ms)
                                {{ $ms->title }},
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($m->paidNode !== null)
                            {{ $m->paidNode->amount / 100 }} 元
                        @else
                            免费
                        @endif
                    </td>
                    <td>{{ $m->sort }}</td>
                    <td>{{ $m->created_at }}</td>
                    <td>
                        <!-- 删除 -->
                        <form method="post"  action="{{ route('music:delete', ['music' => $m->id]) }}" style="display: initial;">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}
                            <button type="submit" id="delete" data-loading-text="处理中" class="btn btn-danger" autocomplete="off">
                                删除
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
    {{ $page }}
@endsection