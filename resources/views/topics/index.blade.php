@extends('layouts.app')

@section('title',isset($category) ? $category->name : '话题列表')

@section('content')

    <div class="row">
        <div class="col-lg-9 col-md-9 topic-list">

            @if(isset($category))

                <div class="alert alert-info" role="alert">

                    {{ $category->name }} : {{ $category->description }}

                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-pills">
                        {{-- Request::url() 获取的是当前请求的 URL --}}
                        <li role="presentation" class="{{ active_class(!if_query('order','recent')) }}"><a href="{{ Request::url() }}?order=default">最后回复</a></li>
                        <li role="presentation" class="{{ active_class(if_query('order','recent')) }}"><a href="{{ Request::url() }}?order=recent">最新发布</a></li>

                    </ul>

                <div class="panel-body">
                    {{--话题列表 --}}
                    @include('topics._topic_list',['topics' => $topics])

                    {{-- 分页 --}}
                    {{-- 将会对列表增加排序功能，排序功能使用了 URL 传参来实现，这里使用分页中 appends() 方法可以使 URI 中的请求参数得到继承。--}}
                    {!! $topics->appends(Request::except('page'))->render() !!}
                </div>
             </div>
         </div>
            <div class="col-lg-3 col-md-3 sidebar">

                 @include('topics._sidebar')

             </div>
     </div>
</div>

@endsection