@if ($paginator->hasPages())
<div class="pagination">
    @if ($paginator->onFirstPage())
        <span class="page-btn page-btn-prev" style="opacity:.4">‹</span>
    @else
        <a class="page-btn page-btn-prev" href="{{ $paginator->previousPageUrl() }}">‹</a>
    @endif

    @if ($paginator->hasMorePages())
        <a class="page-btn" href="{{ $paginator->nextPageUrl() }}">›</a>
    @else
        <span class="page-btn" style="opacity:.4">›</span>
    @endif
</div>
@endif
