<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>音乐管理 - ThinkSNS+</title>
    <!-- style -->
    <link rel="stylesheet" type="text/css" href="/assets/music/css/bootstrap.css">
    <style type="text/css">
        html, body, * {
            margin: 0;
            padding: 0;
            font-family: Roboto, sans-serif, serif, Microsoft Yahei, "微软雅黑";
        }
        .panel {
            margin-top: 16px;
        }
        .alert {
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
        </div>
        @yield('nav')
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('success-message'))
            <div class="alert alert-success"> {{Session::get('success-message')}} </div> 
        @endif
        @show
        <script type="text/javascript" src="{{ mix('js/bootstrap.js', 'assets') }}"></script>
        @yield('content')
        
    </div>
</body>
</html>