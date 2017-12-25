@extends('list')
@section('nav')
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="{{ route('music:list') }}">音乐</a></li>
        <li role="presentation"><a href="{{ route('music:special') }}">专辑</a></li>
        <li role="presentation" class="active"><a href="javascript:">歌手</a></li>
        <li role="presentation"><a href="{{ route('music:all:comments') }}">评论</a></li>
    </ul>
@endsection
@section('content')
	<div class="panel panel-default">
        <div class="panel-heading">
            歌手检索
            <a href="{{ route('music:singers:add') }}" class="btn btn-link pull-right btn-xs" role="button">
                <span class="glyphicon glyphicon-plus"></span>
                添加歌手
            </a>
        </div>
    </div>
    <form class="form-horizontal" method="get" action="{{ $base_url }}">
        <h4 class="h4">歌手筛选</h4>
        {{ csrf_field() }}
        <div class="form-group">
            <label for="exampleInputEmail1" class="col-sm-2 control-label">歌手名</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" id="typetext" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($type==='all')
                                全部歌手
                            @elseif($type==='on')
                                启用歌手
                            @else
                                禁用歌手
                            @endif
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" onclick="changeType('all')">
                            全部歌手
                            </a></li>
                            <li><a href="#" onclick="changeType('on')">
                            启用歌手
                            </a></li>
                            <li><a href="#" onclick="changeType('down')">
                            禁用歌手
                            </a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="type" value="{{ $type }}" id="type" />
                    <input type="text" name="name" class="form-control" value="{{ $name }}" placeholder="歌手名">
                </div>
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
                <th>歌手ID</th>
                <th>歌手名</th>
                <th>封面</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($singers as $singer)
                <tr id="singer_{{$singer->id}}">
                    <td>{{ $singer->id }}</td>
                    <td>{{ $singer->name }}</td>
                    <td>{{ $singer->cover }}</td>
                    <td>{{ $singer->created_at }}</td>
                    <td>
                        <a href="{{ route('music:singers:detail', ['singer' => $singer->id]) }}" class="btn btn-primary btn-sm" role="button">更新</a>
                        <!-- 删除 -->
                        @if(!$singer->deleted_at)
                            <form method="post"  action="{{ route('music:singers:disabled', ['singer' => $singer->id]) }}" style="display: initial;">
                                <input type="hidden" name="_method" value="delete">
                                {{ csrf_field() }}
                                <button type="submit" id="delete" data-loading-text="处理中" class="btn btn-danger btn-sm" autocomplete="off">
                                    禁用
                                </button>
                            </form>
                        @else
                            <form method="post"  action="{{ route('music:singers:restore', ['singer' => $singer->id]) }}" style="display: initial;">
                                <input type="hidden" name="_method" value="patch">
                                {{ csrf_field() }}
                                <button type="submit" id="delete" data-loading-text="处理中" class="btn btn-success btn-sm" autocomplete="off">
                                    启用
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
    {{ $page }}

    <script>
        // $(function() {
            function changeType (type) {
                var obj = document.getElementById("typetext");
                if (type === 'all') {
                    obj.innerHTML = '全部歌手';
                } else if (type === 'on') {
                    obj.innerHTML = '启用歌手';
                } else {
                    obj.innerHTML = '禁用歌手';
                }

                document.getElementById('type').value = type;
            }
        // });
    </script>
@endsection