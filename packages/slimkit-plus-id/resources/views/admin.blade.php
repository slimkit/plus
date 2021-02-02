@extends('layouts.bootstrap')

@section('title', 'Plus ID')

@section('head')

    <meta name="admin-api-basename" content="{{ url('/slimkit/plus-id') }}">
    @parent

@endsection

@section('body')

    <div id="app"></div>
    @parent
    <script src="https://cdn.bootcss.com/babel-polyfill/7.4.4/polyfill.min.js?id=20190520" integrity="sha384-Nh3J/XXlxyM3rjLEs3jwkHg5DP/zDvV7p86vEhCCFnYlYrlY7mGzUxRKm+oProPB" crossorigin="anonymous"></script>
    <script src="{{ mix('app.js', 'assets/plus-id') }}"></script>

@endsection
