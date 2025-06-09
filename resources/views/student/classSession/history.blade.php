@extends('layouts.student')

@section('title', 'Lịch sử sinh hoạt lớp')

@push('styles')
    <style>
        :root {
            --teal-50: #f0fdfa;
            --teal-100: #ccfbf1;
            --teal-600: #2D8AEFFF;
            --teal-700: #0f766e;
            --primary-btn: #2b82df;
            --primary-btn-hover: #0f4976;
        }

        body {
            background-color: #f8fafc;
            /*font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;*/
        }

        .feature-icon i {
            color: var(--teal-600);
            font-size: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--teal-50);
            color: var(--teal-700);
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-color: #e2e8f0;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: var(--teal-50);
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .btn-action {
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-details {
            background-color: var(--teal-50);
            color: var(--teal-600);
            border: 1px solid var(--teal-100);
        }

        .btn-details:hover {
            background-color: var(--teal-100);
            color: var(--teal-700);
        }

        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 12px;
            }

            .btn-action {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="px-4 pt-2">
            <div class="mb-2">
                <a href="{{ route('student.class-session.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
            <div class="d-flex justify-content-end align-items-end mb-3">
                <form method="GET" action="{{ route('student.class-session.history') }}"
                      class="input-group" style="max-width: 300px; margin-left: auto;">
                    <input type="text" class="form-control" placeholder="Tìm kiếm lớp học" name="search"
                           value="{{ request('search') }}"
                           aria-label="Search class" aria-describedby="search-addon">
                    <button class="btn btn-outline-secondary" id="search-addon">
                        <i class="fas fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Sinh hoạt lớp</th>
                                <th class="d-none d-md-table-cell">Giáo viên</th>
                                <th>Thời gian</th>
                                <th>Lớp</th>
                                <th>Tiều đề</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data['classSessionRequests']) && $data['classSessionRequests']['total'] == 0)
                                <p>Không có yêu cầu nào.</p>
                            @else
                                @foreach($data['classSessionRequests']['data'] as $item)
                                    <tr>
                                        <td>
                                            <div
                                                class="fw-semibold">{{ $item['type'] == 0 ? 'Cố định' : 'Linh hoạt' }}</div>
                                            <small
                                                class="text-muted d-md-none">GV: {{ $item['lecturer']['user']['name'] }}</small>
                                        </td>
                                        <td class="d-none d-md-table-cell">{{ $item['lecturer']['user']['name'] }}</td>
                                        <td>
                                            <div
                                                class="fw-semibold">{{ \Carbon\Carbon::parse($item['proposed_at'])->format('H:i') }}</div>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($item['proposed_at'])->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                                <span
                                                    class="badge bg-light text-dark">{{ $item['study_class']['name'] }}</span>
                                        </td>
                                        <td>
                                            <span class="title_cut">
                                                {{ $item['title'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div
                                                class="action-buttons d-flex flex-column flex-md-row gap-2 justify-content-center">
                                                <a href="{{ route('student.class-session.detailClassSession', ['study-class-id' => $item['study_class']['id'], 'session-request-id' => $item['id']]) }}" class="btn btn-action btn-details" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <x-pagination.pagination :paginate="$data['classSessionRequests']"/>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".title_cut").each(function() {
                var text = $(this).text().trim();
                if (text.length > 20) {
                    $(this).attr("title", text);
                    $(this).text(text.substring(0, 20) + '...');
                }
            });
        });
    </script>
@endpush
