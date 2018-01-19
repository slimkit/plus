<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}">
    <title>Test Group Worker | {{ config('app.name') }}</title>
    <script type="text/javascript">
        window.user = {!! $user->toJson() !!};
        window.accessToken = '{{ $accessToken }}';
        window.apiBaseUrl = '{{ route('api:test-group-worker') }}';
        window.logoutUrl = '{{ route('logout') }}';
        window.githubBasicToken = '{{ $githubBasicToken }}';
    </script>
</head>
<body>
<div id="root"></div>
<script src="{{ mix('app.js', 'assets/test-group-worker') }}"></script>
</body>
</html>
