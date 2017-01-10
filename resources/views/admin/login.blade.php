@extends('admin.layouts.app')

@section('title')
登录
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="{{ elixir('css/admin-login.css') }}">
@endsection

@section('body')
<div id="app"></div>
<script type="text/javascript" src="{{ elixir('js/admin-login.js') }}"></script>
@endsection
