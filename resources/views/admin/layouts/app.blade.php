<!DOCTYPE html>
<html lang="zh-cn">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- global config. -->
    <script type="text/javascript">
        window.TS = {!!
            json_encode([
                'baseRUL' => url('admin'),
                'auth' => false,
            ])
        !!};
    </script>

    @yield('head')
</head>
<body>
    @yield('body')
</body>
</html>