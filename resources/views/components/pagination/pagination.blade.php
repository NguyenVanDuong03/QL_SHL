@if ($paginate['last_page'] > 1)
    @php
        $currentPage = $paginate['current_page'];
        $lastPage = $paginate['last_page'];
        $start = max($currentPage - 1, 1);
        $end = min($currentPage + 1, $lastPage);

        $queryString = request()->except('page');
    @endphp

    <div class="d-flex justify-content-end justify-content-md-between align-items-center mt-3 mx-2">
        <div class="d-none d-md-block">
            <span>
                {{ $paginate['total'] ?? 0 }} kết quả – Trang {{ $currentPage }} / {{ $lastPage }}
            </span>
        </div>
        <nav>
            <ul class="pagination pagination-sm justify-content-center">

                {{-- Previous Page --}}
                <li class="page-item {{ $currentPage === 1 ? 'disabled' : '' }}">
                    <a class="page-link"
                       href="{{ $paginate['prev_page_url'] ? $paginate['path'] . '?' . http_build_query(array_merge($queryString, ['page' => $currentPage - 1])) : '#' }}"
                       rel="prev">&laquo;</a>
                </li>

                {{-- First Page --}}
                @if ($start > 1)
                    <li class="page-item">
                        <a class="page-link"
                           href="{{ $paginate['path'] . '?' . http_build_query(array_merge($queryString, ['page' => 1])) }}">1</a>
                    </li>
                    @if ($start > 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endif

                {{-- Page Numbers --}}
                @for ($page = $start; $page <= $end; $page++)
                    <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                        <a class="page-link"
                           href="{{ $paginate['path'] . '?' . http_build_query(array_merge($queryString, ['page' => $page])) }}">
                            {{ $page }}
                        </a>
                    </li>
                @endfor

                {{-- Last Page --}}
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item">
                        <a class="page-link"
                           href="{{ $paginate['path'] . '?' . http_build_query(array_merge($queryString, ['page' => $lastPage])) }}">
                            {{ $lastPage }}
                        </a>
                    </li>
                @endif

                {{-- Next Page --}}
                <li class="page-item {{ $currentPage === $lastPage ? 'disabled' : '' }}">
                    <a class="page-link"
                       href="{{ $paginate['next_page_url'] ? $paginate['path'] . '?' . http_build_query(array_merge($queryString, ['page' => $currentPage + 1])) : '#' }}"
                       rel="next">&raquo;</a>
                </li>
            </ul>
        </nav>
    </div>
@endif
