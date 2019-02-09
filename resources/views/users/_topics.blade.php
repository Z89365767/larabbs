@if(count($topics))

    <ul class="list-grop">

        @if(if_query('tab','replies'))
            @foreach($topics as $topic)

                <li class="list-grop-item">

                    <a href="{{ $topic->topic->link() }}">
                        {{ $topic->topic->title }}

                    </a>
                    <span class="meta pull-right">

                        {{ $topic->topic->reply_count }} 回复
                        <span> ⋅ </span>
                        {{ $topic->topic->created_at->diffForHumans() }}
                    </span>
                </li>
            @endforeach
       @else
            @foreach($topics as $topic)

                <li class="list-grop-item">

                    <a href="{{ $topic->link() }}">
                        {{ $topic->title }}

                    </a>
                    <span class="meta pull-right">

                        {{ $topic->reply_count }} 回复
                        <span> ⋅ </span>
                        {{ $topic->created_at->diffForHumans() }}
                    </span>
                </li>
            @endforeach
       @endif
    </ul>
  @else

        <div class="empty-block">暂无数据</div>
    @endif

    {{-- 分页 --}}
    {!! $topics->render() !!}

