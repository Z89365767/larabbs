@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            
            <div class="panel-heading">
                <h1>
                    <i class="glyphicon glyphicon-edit"></i> 话题 /
                    @if($topic->id)
                        Edit #{{$topic->id}}
                    @else
                        新建
                    @endif
                </h1>
            </div>

            @include('common.error')

            <div class="panel-body">
                @if($topic->id)
                    <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="_method" value="PUT">
                @else
                    <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">
                @endif

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    
                <div class="form-group">
                	<input class="form-control" type="text" name="title" id="title-field" value="{{ old('title', $topic->title ) }}" placeholder="请填写标题" required />
                </div>

                <div class="form-group">
                    <select class="form-control" name="category_id" required>
                            <option value="" hidden disabled selected>请选择分类</option>
                            @foreach($categorys as $value)

                                <option value="{{ $value->id }}">{{ $value->name }}</option>

                            @endforeach
                    </select>
                </div>

                <div class="form-group">
                	<label for="body-field">内容</label>
                	<textarea name="body" id="editor" class="form-control" rows="3" placeholder="请输入大于三个字的内容">{{ old('body', $topic->body ) }}</textarea>
                </div>
                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary">提交</button>
                        <a class="btn btn-link pull-right" href="{{ route('topics.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
    {{-- asset()方法用于引入 CSS/JavaScript/images 等文件,文件必须存放在public文件目录下。url()方法生成一个完整的网址。--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">

@stop

@section('scripts')

    <script type="text/javascript"  src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>

    <script>

        $(document).ready(function(){

            var editor = new Simditor({

                textarea: $('#editor'),
            });
        });
    </script>

@stop