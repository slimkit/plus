@extends('layouts.bootstrap')

@section('title', trans('plus-checkin::app.name'))

@section('body')
    
    <div class="container">
        <h1>
            {{ trans('plus-checkin::app.name') }}
        </h1>

        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans('plus-checkin::app.setting') }}
            </div>

            <div class="panel-body">
                <form class="form-horizontal" action="{{ route('checkin:store-config') }}" method="POST">

                    {{ method_field('put') }}
                    {{ csrf_field() }}

                    <!-- Switch -->
                    <div class="form-group">
                        <label for="checkin-switch" class="col-sm-2 control-label">
                            {{ trans('plus-checkin::admin.switch') }}
                        </label>
                        <div class="col-sm-4">

                            <label class="radio-inline">
                                <input id="checkin-switch" type="radio" name="switch" value="1" {{ old('switch', $switch) ? 'checked' : '' }}>
                                {{ trans('plus-checkin::admin.open') }}
                            </label>

                            <label class="radio-inline">
                                <input id="checkin-switch" type="radio" name="switch" value="0" {{ old('switch', $switch) ? '' : 'checked' }}>
                                {{ trans('plus-checkin::admin.close') }}
                            </label>

                        </div>
                        <div class="col-sm-6">
                            <span class="help-block">
                                {{ trans('plus-checkin::admin.switch-desc') }}
                            </span>
                        </div>
                    </div>

                    <!-- Set attack balance -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            {{ trans('plus-checkin::admin.balance') }}
                        </label>
                        <div class="col-sm-4">
                            
                            <input type="number" name="balance" class="form-control" min="1" value="{{ old('balance', $balance) }}">

                        </div>
                        <div class="col-sm-6">
                            <span class="help-block">
                                {{ trans('plus-checkin::admin.balance-desc') }}
                            </span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">提交</button>
                        </div>
                    </div>

                </form>

                @if (session('message'))
                    <div class="alert alert-success alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        {{ session('message') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        {{ $errors->first() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
    
    @parent

@endsection
