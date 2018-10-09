@extends('plus-blog::layout')
@section('title', '创建博客')
@section('container')
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">创建博客</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('blog:me') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- 唯一标识 -->
                        <div class="form-group {{ $errors->has('slug') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">标识</label>
                            <div class="col-sm-10">
                                <input name="slug" type="text" class="form-control" placeholder="博客唯一标识" value="{{ old('slug') }}">
                                <span class="help-block">
                                    {{ 
                                        $errors->has('slug')
                                            ? $errors->first('slug')
                                            : '输入你博客的唯一标识，一旦创建将不再允许修改'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 名称 -->
                        <div class="form-group {{ $errors->has('name') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-10">
                                <input name="name" type="text" class="form-control" placeholder="博客名称" value="{{ old('name') }}">
                                <span class="help-block">
                                    {{ 
                                        $errors->has('name')
                                            ? $errors->first('name')
                                            : '输入博客名称'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 博客描述 -->
                        <div class="form-group {{ $errors->has('desc') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-10">
                                <textarea name="desc" class="form-control" rows="3" placeholder="博客描述">{{ old('desc') }}</textarea>
                                <span class="help-block">
                                    {{ 
                                        $errors->has('desc')
                                            ? $errors->first('desc')
                                            : '请输入你博客的描述，好的描述让你的博客更有魅力哦！'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 博客 Logo -->
                        <div class="form-group {{ $errors->has('logo') ? 'has-error': '' }}">
                            <label class="col-sm-2 control-label">图标</label>
                            <div class="col-sm-10">
                                <input name="logo" type="file" class="form-control" >
                                <span class="help-block">
                                    {{ 
                                        $errors->has('logo')
                                            ? $errors->first('logo')
                                            : '请选择博客所使用的图标'
                                    }}
                                </span>
                            </div>
                        </div>
                        <!-- 提交按钮 -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">创建</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning" role="alert">你还没有创建属于你的博客，你可以在这里创建一个属于你自己的博客，博客创建完成后你可以发布你的文章了！</div>
        </div>
    </div>
@endsection