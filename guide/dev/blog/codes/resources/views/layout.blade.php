@extends('layouts.bootstrap')
@section('head')
    @parent
    <style>
        .blog-container {
            margin-top: 70px;
        }
    </style>
@endsection
@section('body')
    @include('plus-blog::header')
    <main class="container blog-container">
        @yield('container')
    </main>
    @include('plus-blog::footer')
    @parent
    @stack('footer-scripts')
@endsection
