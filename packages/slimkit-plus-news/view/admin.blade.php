@extends('layouts.bootstrap')

@section('title', '资讯')

@section('head')
  <meta name="api-token" content="{{ $token }}">
  <meta name="api-basename" content="{{ url('/api/v2') }}">
  <meta name="domain" content="{{ config('app.url') }}" />
  <meta name="admin-api-basename" content="{{ url('/news/admin') }}">
  
  <link rel="stylesheet" href="{{mix('css/index.css', 'assets/news/assets')}}">

    @parent

@endsection

@section('body')
  <div id="app"></div>
    @parent
<script src="{{ mix('js/manifest.js', 'assets/news/assets') }}"></script>
<script src="{{ mix('js/vendor.js', 'assets/news/assets') }}"></script>
<script src="{{ mix('js/index.js', 'assets/news/assets') }}"></script>
@endsection
