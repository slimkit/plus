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
        @if ($type === 'music') 
			歌曲《{{$music->title}}》 的评论
        @else 
			专辑 《{{ $special->title }}》 的评论
        @endif
        <a href="javascript:history.back();" class="btn btn-link pull-right btn-xs" role="button">
            <span class="glyphicon glyphicon-plus"></span>
            返回上一页
        </a>
    </div>
</div>
@if ($type === 'music')
<form class="form-horizontal" method="get" action="{{ route('music:comments', ['music' => $music->id]) }}">
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
                <th>所属@if($type === 'music') 歌曲 @else 专辑 @endif</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
                <tr id="comment_{{$comment->id}}">
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->body }}</td>
                    <td>{{ $comment->user_id  }}</td>
                    <td>
                      《@if($type === 'music') {{$music->title}} @else {{$special->title}} @endif》
                    </td>
                    <td>{{ $comment->created_at }}</td>
                    <td>
                        <!-- 删除 -->
                        <form method="post"  action="@if($type === 'music') {{ route('music:comments:delete', ['music' => $music->id, 'comment' => $comment->id]) }} @else {{ route('special:comments:delete', ['special' => $special->id, 'comment' => $comment->id]) }} @endif" style="display: initial;">
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