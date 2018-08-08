@extends('layouts.bootstrap')

@section('title', '附近的人')

@section('head')
    @parent
    <style type="text/css">
        #app {
            padding-top: 70px;
        }
    </style>
@endsection

@section('body')

    <div id="app" class="container">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" aria-expanded="false" data-target="#plus-around-amap-collapse">
                      <span class="sr-only">切换导航</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                    <span class="navbar-brand">附近的人</span>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="plus-around-amap-collapse">

                    <ul class="nav navbar-nav">

                        <li class="active">
                            <a href="{{ route('around-amap:admin-home') }}">基础设置</a>
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <div class="panel panel-default">
            <div class="panel-heading">设置高德地图信息</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('around-amap:admin-save') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('amap-key') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">高德 Web 服务 Key</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="amap-key" value="{{ old('amap-key', $key) }}"  required autofocus>

                            @if ($errors->has('amap-key'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amap-key') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('amap-sig') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">应用密钥</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="amap-sig" value="{{ old('amap-sig', $sig) }}" required>

                            @if ($errors->has('amap-sig'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amap-sig') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('amap-tableid') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">自定义地图 Table ID</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="amap-tableid" value="{{ old('amap-tableid', $tableid) }}" required>

                            @if ($errors->has('amap-tableid'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amap-tableid') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                提交
                            </button>
                        </div>
                    </div>

                </form>

                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                        {{ session('status') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    @parent
@endsection
