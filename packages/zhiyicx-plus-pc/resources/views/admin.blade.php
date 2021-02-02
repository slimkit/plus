@extends('layouts.bootstrap')

@section('title', 'PC 后台管理')

@section('head')

    <meta name="admin-api-basename" content="{{ url('/slimkit/plus-id') }}">
    <script type="text/javascript">
      window.PC = {!!
          json_encode([
              'token' => $token,
              'csrfToken' => $csrf_token,
              'baseURL' => $base_url,
              'api' => $api
          ])
      !!};
  </script>
    @parent

@endsection

@section('body')

    <div id="app"></div>
    @parent
    <script src="https://cdn.bootcss.com/babel-polyfill/7.4.4/polyfill.min.js?id=20190520" integrity="sha384-Nh3J/XXlxyM3rjLEs3jwkHg5DP/zDvV7p86vEhCCFnYlYrlY7mGzUxRKm+oProPB" crossorigin="anonymous"></script>
    <script src="{{ mix('admin.js', 'assets/pc') }}"></script>

@endsection
