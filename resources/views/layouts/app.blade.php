<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ url('/favicon.ico') }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @yield('head')
</head>
<body>
@yield('body')
</body>
</html>