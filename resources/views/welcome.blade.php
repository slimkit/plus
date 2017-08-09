<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}">
    <title>Welcome to {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <style type="text/css">
        html, body {
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }
        .g-bg-color {
            background: #59b6d7;
            background: -moz-linear-gradient(135deg, #7262d1, #48d7e4);
            background: -webkit-gradient(linear, left top, right bottom, color-stop(0%, #7262d1), color-stop(100%, #48d7e4));
            background: -webkit-linear-gradient(0deg, #7262d1, #48d7e4);
            background: -o-linear-gradient(135deg, #7262d1, #48d7e4);
            background: -ms-linear-gradient(135deg, #7262d1, #48d7e4);
            background: linear-gradient(135deg, #7262d1, #48d7e4);
        }
        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
            color: #fff;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body class="g-bg-color">
<div class="flex-center position-ref full-height g-bg-color">
    <div class="content">
        <img src="{{ asset('/plus.png') }}" align="{{ config('app.name') }}">

        <div class="title m-b-md">
            {{ config('app.name') }}
        </div>

        <div class="links">
            <a href="{{ url('/admin') }}">Administration</a>
            <a href="https://github.com/slimkit/thinksns-plus/tree/master/docs">Documentation</a>
            <a href="https://github.com/slimkit/thinksns-plus">GitHub</a>
        </div>
    </div>
</div>
</body>
</html>
