@extends('layouts.app')

@section('title',$topic->title)

@section('description',$topic->excerpt)

@section('content')

<div class="row">
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center">
                    作者：{{ $topic->user->name }}
                </div>
                <hr>
                <div class="media">
                    <div align="center">
                        <a href="{{ route('users.show',$topic->user->id) }}">

                            <img class="thumbnail img-responsive" src="{{ $topic->user->avatar }}" width="300px" height="300px">

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 topic-content">
        <div class="panel panel-default">
            <div class="panel-body">
                <h1 class="text-center">
                    {{ $topic->title }}
                </h1>

                <div class="article-meta text-center">
                        {{ $topic->created_at->diffForHumans() }}
                        ⋅
                        <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                        {{ $topic->reply_count }}
                </div>

                <div class="topic-body">
                    {{-- Blade 的 {!! !!} 语法是直接数据，不会对数据做任何处理。在我们这种场景下，因为业务逻辑的特殊性，第二种方法不适用，我们将使用第一种方法，对用户提交的数据进行过滤来避免 XSS 攻击。 --}}
                    {!! $topic->body !!}
                </div>

                @can('update',$topic)

                <div class="operate">
                    <hr>
                    <a href="{{ route('topics.edit',$topic->id) }}" class="btn btn-default btn-xs pull-left" role="button">
                        <i class="glyphicon glyphicon-edit"></i>编辑
                    </a>

                    <form action="{{ route('topics.destroy',$topic->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-default btn-xs pull-left" style="margin-left: 6px">
                            <i class="glyphicon glyphicon-trash"></i>
                            删除
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>

        {{-- 用户回复列表 --}}
        <div class="panel panel-default topic-reply">
            <div class="panel-body">
                {{-- 话题回复功能我们只允许登录用户使用，未登录用户不显示即可。Laravel Blade 模板提供了一个『视条件加载子模板』的语法： --}}
                @includeWhen(Auth::check(),'topics._reply_box',['topic' => $topic])
                {{-- 注意读取回复列表时需使用懒加载来避免 N+1 问题 --}}
                @include('topics._reply_list',['replies' => $topic->replies()->with('user')->get()])
            </div>

        </div>
    </div>
</div>

@stop
