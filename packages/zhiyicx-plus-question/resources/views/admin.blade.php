@extends('layouts.bootstrap')

@section('title', '问答')

@section('head')
    
    <meta name="admin-api-basename" content="{{ url('/question-admin') }}">
    <meta name="api-basename" content="{{ url('/api/v2') }}">
    <meta name="api-token" content="{{ $api_token }}">
    @parent

@endsection

@section('body')

    <div id="app"></div>
    @parent
    <script src="{{ mix('admin.js', 'assets/question-answer') }}"></script>

@endsection
