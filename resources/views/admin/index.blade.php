@extends('admin.layouts.app')

@section('title')
后台管理
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="{{ elixir('css/admin.css') }}">
@endsection

@section('body')
<div id="app"></div>
<script type="text/javascript" src="{{ elixir('js/admin.js') }}"></script>
@endsection
