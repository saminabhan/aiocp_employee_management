<style>
/* ===== إصلاح تام للباجينيشن ===== */
nav[role="navigation"] {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-align: center !important;
    width: 100%;
    overflow: hidden; /* يمنع أي سكرول */
}

.pagination-info {
    margin-bottom: 8px !important;
    color: #6c757d !important;
    font-size: 14px !important;
    text-align: center !important;
}

/* جعل القائمة أفقية بالإجبار */
.pagination {
    display: flex !important;
    flex-direction: row !important;
    justify-content: center !important;
    align-items: center !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    gap: 6px !important;
    flex-wrap: wrap !important;
}

.pagination li {
    display: inline-block !important;
    list-style: none !important;
}

.pagination .page-link {
    display: inline-block !important;
    border-radius: 6px !important;
    padding: 6px 10px !important;
    border: 1px solid #dee2e6 !important;
    color: #0C4079 !important;
    text-decoration: none !important;
    transition: all 0.2s !important;
}

.pagination .page-link:hover {
    background-color: #0C4079 !important;
    color: #fff !important;
}

.pagination .page-item.active .page-link {
    background-color: #0C4079 !important;
    color: #fff !important;
    border-color: #0C4079 !important;
}
</style>

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-wrapper text-center mt-3">
        <div class="pagination-info">
            {!! __('Showing') !!} {{ $paginator->firstItem() }} {!! __('to') !!}
            {{ $paginator->lastItem() }} {!! __('of') !!}
            {{ $paginator->total() }} {!! __('results') !!}
        </div>

        <ul class="pagination mb-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
