<!DOCTYPE html>
<html lang="zh-CN">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="{{ url('/favicon.ico') }}">

    <title>后台管理</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ $csrf_token }}">

    <!-- style -->
    <link rel="stylesheet" type="text/css" href="{{ mix('css/admin.css') }}">

    <!-- global config. -->
    <script type="text/javascript">
        window.TS = {!!
            json_encode([
                'csrfToken' => $csrf_token,
                'baseURL' => $base_url,
                'api' => $api,
                'logged' => $logged,
                'user' => $user,
                'menus' => $menus,
            ])
        !!};
    </script>
</head>
<body>
<div id="app"></div>
<!-- script -->
<script type="text/javascript" src="{{ mix('js/manifest.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
</body>
</html>