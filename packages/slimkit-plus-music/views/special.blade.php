@extends('list')
@section('nav')
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="{{ route('music:list') }}">音乐</a></li>
        <li role="presentation" class="active"><a href="javascript:">专辑</a></li>
        <li role="presentation"><a href="{{ route('music:singers') }}">歌手</a></li>
        <li role="presentation"><a href="{{ route('music:all:comments') }}">评论</a></li>
    </ul>
@endsection
@section('content')
	<div class="panel panel-default">
        <div class="panel-heading">
            专辑检索
            <a href="{{ route('special:add') }}" class="btn btn-link pull-right btn-xs" role="button">
                <span class="glyphicon glyphicon-plus"></span>
                添加专辑
            </a>
        </div>
    </div>
    <form class="form-horizontal" method="get" action="{{ $base_url }}">
        <h4 class="h4">专辑筛选</h4>

        <div class="form-group">
            <label for="exampleInputEmail1" class="col-sm-2 control-label">专辑名</label>
            <div class="col-sm-6">
                <input type="text" name="name" class="form-control" value="{{ $name }}" placeholder="专辑名">
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
                <th>专辑ID</th>
                <th>专辑名</th>
                <th>歌曲数量</th>
                <th>是否付费</th>
                <th>评论数量</th>
                <th>权重值</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($special as $s)
                <tr id="special_{{$s->id}}">
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->title }}</td>
                    <td>{{ $s->musics_count  }}</td>
                    <td>
                    	@if ($s->paidNode)
                    		{{ $s->paidNode->amount / 100 }} 元
                    	@else
                    		免费
                    	@endif
                    </td>
                    <td><a href="{{ route('special:comments', ['special' => $s->id]) }}">{{ $s->comment_count }}条评论</a></td>
                    <td>
                      {{ $s->sort }}
                    </td>
                    <td>{{ $s->created_at }}</td>
                    <td>
                        <!-- 编辑 -->
                        <a href="{{ route('special:detail', ['special' => $s->id]) }}" class="btn btn-primary btn-sm" role="button">编辑</a>
                        <!-- 删除 -->
                        <form method="post"  action="{{ route('special:delete', ['special' => $s->id]) }}" style="display: initial;">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}
                            <button type="submit" id="delete" data-loading-text="处理中" class="btn btn-danger" autocomplete="off">
                                禁用
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
    {{ $page }}
@endsection