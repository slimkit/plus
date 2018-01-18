@extends('layouts.bootstrap')

@section('head')
    
    @parent

    <style type="text/css">
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 36px;
            padding: 20px;
        }
    </style>

@endsection

@section('body')
    
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title">

                {{ $exception->getMessage() }}

            </div>
        </div>
    </div>

    @parent

@endsection
