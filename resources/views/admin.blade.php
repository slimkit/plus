@extends('layouts.bootstrap')

@section('title', '后台管理')

@section('head')

    @parent

    <script>
        window.TS = {!!
            json_encode([

                'api'       => $api,
                'baseURL'   => $base_url,
                'csrfToken' => $csrf_token,
                'logged'    => $logged,
                'user'      => $user,
                'token'     => $token,
                'domain'    => config('app.url')
            ])
        !!};
    </script>

@endsection

@section('body')

    <div id="app"></div>

    @parent

   <script src="https://cdn.bootcss.com/babel-polyfill/7.4.4/polyfill.min.js?id=20190520" integrity="sha384-Nh3J/XXlxyM3rjLEs3jwkHg5DP/zDvV7p86vEhCCFnYlYrlY7mGzUxRKm+oProPB" crossorigin="anonymous"></script>
    <script src="{{ mix('js/admin.js', 'assets') }}"></script>

@endsection
