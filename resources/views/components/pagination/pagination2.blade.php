@if ($paginate['last_page'] > 1)
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Previous Page --}}
            <li class="page-item {{ $paginate['current_page'] === 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginate['prev_page_url'] ?? '#' }}" rel="prev">&laquo;</a>
            </li>

            @php
                $currentPage = $paginate['current_page'];
                $lastPage = $paginate['last_page'];
                $start = max($currentPage - 2, 1);
                $end = min($currentPage + 2, $lastPage);
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginate['first_page_url'] }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginate['path'] }}?page={{ $page }}">{{ $page }}</a>
                </li>
            @endfor

            {{-- Last Page --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginate['path'] }}?page={{ $lastPage }}">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next Page --}}
            <li class="page-item {{ $paginate['next_page_url'] ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginate['next_page_url'] ?? '#' }}" rel="next">&raquo;</a>
            </li>
        </ul>
    </nav>
@endif

