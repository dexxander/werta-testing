@if($paginator->hasPages())
<nav class="community-pagination" aria-label="{{ __('community::community.pagination') }}">
    <span>@if($paginator->previousPageUrl())<a href="{{ $paginator->previousPageUrl() }}">&larr; {{ __('community::community.previous') }}</a>@endif</span>
    <span>{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
    <span>@if($paginator->nextPageUrl())<a href="{{ $paginator->nextPageUrl() }}">{{ __('community::community.next') }} &rarr;</a>@endif</span>
</nav>
@endif
