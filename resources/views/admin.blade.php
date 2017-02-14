<!DOCTYPE html>
<html lang="zh-CN">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>后台管理</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ $csrf_token }}">

    <!-- style -->
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin.css') }}">

    <!-- global config. -->
    <script type="text/javascript">
        window.TS = {!!
            json_encode([
                'csrf_token' => $csrf_token,
                'base_url' => $base_url,
                'logged' => $logged,
                'user' => $user,
            ])
        !!};
    </script>
</head>
<body>
<div id="app"></div>
<!-- script -->
<script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
</body>
</html>