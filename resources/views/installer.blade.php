<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="logo" content="{{ asset('/assets/installer/logo.png') }}">
    <meta name="version" content="{{ app()->version() }}">
    <meta name="api-base" content="{{ url('/installer') }}">
    <title>Installer - {{ config('app.name') }}</title>
</head>
<body>
<div id="app"></div>
<script src="{{ mix('js/installer.js', 'assets') }}"></script>
</body>
</html>
