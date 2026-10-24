@if ($paginator->hasPages())
<nav class="pagination" role="navigation">
    @if ($paginator->onFirstPage())
        <span class="disabled">&lsaquo;</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
    @endif

    @if (isset($elements))
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
    @else
        <span class="disabled">&rsaquo;</span>
    @endif
</nav>
@endif
