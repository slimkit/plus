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

    <script src="{{ mix('js/admin.js', 'assets') }}"></script>

@endsection
