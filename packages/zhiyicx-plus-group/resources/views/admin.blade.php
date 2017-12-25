@extends('layouts.bootstrap')

@section('title', '圈子管理')

@section('head')
    
    <meta name="admin-api-basename" content="{{ url('/group-admin') }}">
    <meta name="api-basename" content="{{ url('/api/v2') }}">
    <meta name="api-token" content="{{ $api_token }}">
    @parent

@endsection

@section('body')

    <div id="app">
    </div>
    @parent

    <script src="{{ mix('admin.js', 'assets/plus-group') }}"></script>
@endsection
