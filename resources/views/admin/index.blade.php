@extends('admin.layouts.app')

@section('title')
后台管理
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="{{ mix('css/admin.css') }}">
@endsection

@section('body')
<div id="app"></div>
<script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
@endsection
