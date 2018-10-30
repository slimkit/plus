<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>音乐管理 - ThinkSNS+</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ $csrf_token }}">
    <link rel="stylesheet" href="/assets/music/css/bootstrap.css" />
    <style type="text/css">
        html, body, * {
            margin: 0;
            padding: 0;
            font-family: Roboto, sans-serif, serif, Microsoft Yahei, "微软雅黑";
        }
        .panel {
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        @section('header')
        @endsection
        @section('content')
            <div class="panel panel-default">
                <div class="panel-heading">
                    音乐检索
                </div>
            </div>
            <form class="form-horizontal" method="get" onsubmit="return false;">
                <h4 class="h4">音乐筛选</h4>

                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">歌曲名称</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" value="{{ $name }}" placeholder="歌曲名称">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-sm-3 control-label">专辑名称</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="album" value="{{ $album }}" id="album" placeholder="专辑名称">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="submit" class="btn btn-default btn-primary">搜索</button>
                    </div>
                </div>
            </form>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>歌曲ID</th>
                        <th>歌曲名</th>
                        <!-- <th>歌曲封面</th> -->
                        <th>播放数量</th>
                        <th>分享数量</th>
                        <th>评论数量</th>
                        <th>专辑名</th>
                        <th>是否付费</th>
                        <!-- <th>权重值</th> -->
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody> </tbody>
                <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>
            </table>
        @endsection
    </div>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/music/js/bootstrap.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

</body>
</html>
