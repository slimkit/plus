<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>登陆 - {{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <style type="text/css">
        html, body {
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            margin: 0;
            padding: 0;
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
        .root {
            display: flex;
            position: fixed;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .form-group {
            margin-top: 15px;
            width: 240px;
        }
        .form-buttom-group {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        .form-control {
            display: block;
            width: 100%;
            height: 36px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.6;
            color: #fff;
            background-color: rgba(0, 0, 0, 0);
            background-image: none;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            outline: none;
            box-sizing: border-box;
        }
        .has-error .form-control {
            border-color: #a94442;
        }
        .has-error .help-block {
            display: block;
            margin-top: 5px;
            margin-bottom: 10px;
            color: #a94442;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form-submit {
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 4px;
            background: rgba(0, 0, 0, 0);
            width: 100px;
            height: 36px;
            color: #fff;
            outline: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="g-bg-color root">
    <img src="{{ asset('/plus.png') }}" width="84px" />
    <form role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <input type="hidden" name="redirect" value="{{ request()->input('redirect') }}">

        <div class="form-group {{ $errors->has($errorUsername) ? 'has-error' : '' }} ">

            <input class="form-control" type="text" name="login" placeholder="登录名 / 邮箱 / 手机号码" value="{{ $login }}" required autofocus />

            @if ($errors->has($errorUsername))
                <span class="help-block">
                    {{ $errors->first($errorUsername) }}
                </span>
            @endif

        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }} ">
            <input class="form-control" type="password" name="password" placeholder="请输入密码" required />

            @if ($errors->has('password'))
                <span class="help-block">
                    {{ $errors->first('password') }}
                </span>
            @endif

        </div>

        <div class="form-group form-buttom-group">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} /> 记住我
            </label>
            <button class="form-submit" type="submit">登陆</button>
        </div>

    </form>
</div>
</body>
</html>