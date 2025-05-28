@if ($paginate['last_page'] > 1)
    <div class="d-flex justify-content-end justify-content-md-between align-items-center mt-3">
        <div class="d-none d-md-block">
            <span>
                {{ $paginate['total'] ?? 0 }} kết quả – Trang {{ $paginate['current_page'] ?? 1 }} /
                {{ $paginate['last_page'] ?? 1 }}
            </span>
        </div>
        <nav>
            <ul class="pagination justify-content-center">
                {{-- Previous Page --}}
                <li class="page-item {{ $paginate['current_page'] === 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginate['prev_page_url'] ?? '#' }}" rel="prev">&laquo;</a>
                </li>

                @php
                    $currentPage = $paginate['current_page'];
                    $lastPage = $paginate['last_page'];
                    $start = max($currentPage - 1, 1);
                    $end = min($currentPage + 1, $lastPage);
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
                        <a class="page-link"
                            href="{{ $paginate['path'] }}?page={{ $page }}">{{ $page }}</a>
                    </li>
                @endfor

                {{-- Last Page --}}
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $paginate['path'] }}?page={{ $lastPage }}">{{ $lastPage }}</a>
                    </li>
                @endif

                {{-- Next Page --}}
                <li class="page-item {{ $paginate['next_page_url'] ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $paginate['next_page_url'] ?? '#' }}" rel="next">&raquo;</a>
                </li>
            </ul>
        </nav>
    </div>

@endif
