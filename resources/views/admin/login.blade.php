@extends('admin.layouts.app')

@section('title')
登录
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="{{ elixir('css/admin.css') }}">
@endsection

@section('body')
    
<div class="container" id="app">
    <form class="form-signin" role="form">
        <h2 class="form-signin-heading text-center">登录后台</h2>
        <input type="email" class="form-control" placeholder="Email address" required autofocus>
        <input type="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
</div>
<script type="text/javascript" src="{{ elixir('js/admin/login.js') }}"></script>
@endsection
