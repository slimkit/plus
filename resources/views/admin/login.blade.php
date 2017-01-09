@extends('admin.layouts.app')

@section('title')
登录
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="{{ elixir('css/admin.css') }}">
@endsection

@section('body')
<div class="container" id="app"></div>
<script type="text/javascript" src="{{ elixir('js/admin.js') }}"></script>
@endsection
