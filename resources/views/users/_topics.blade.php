@if(count($topics))

    <ul class="list-grop">
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
    </ul>
  @else

        <div class="empty-block">暂无数据</div>
    @endif

    {{-- 分页 --}}
    {!! $topics->render() !!}

