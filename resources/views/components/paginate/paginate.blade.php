@if ($paginate->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $paginate->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginate->previousPageUrl() }}" rel="prev">&laquo;</a>
            </li>

            @php
                $start = max($paginate->currentPage() - 2, 1);
                $end = min($paginate->currentPage() + 2, $paginate->lastPage());
            @endphp

            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginate->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $paginate->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginate->url($page) }}">{{ $page }}</a>
                </li>
            @endfor

            @if ($end < $paginate->lastPage())
                @if ($end < $paginate->lastPage() - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item"><a class="page-link"
                        href="{{ $paginate->url($paginate->lastPage()) }}">{{ $paginate->lastPage() }}</a>
                </li>
            @endif

            <li class="page-item {{ $paginate->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginate->nextPageUrl() }}" rel="next">&raquo;</a>
            </li>
        </ul>
    </nav>
@endif
