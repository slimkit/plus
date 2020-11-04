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
    <script src="{{ mix('admin.js', 'assets/pc') }}"></script>

@endsection
