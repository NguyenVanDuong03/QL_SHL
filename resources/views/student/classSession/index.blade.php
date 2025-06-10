@extends('layouts.student')

@section('title', 'Sinh hoạt lớp')

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

        .section-title {
            color: var(--teal-700);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="px-4 pt-2">
                <div class="d-flex justify-content-end align-items-end mb-3">
                    <a class="btn btn-primary" href="{{ route('student.class-session.history') }}">Lịch sử</a>
                </div>

            <h3 class="section-title text-lg-center mb-4">
                <i class="fas fa-calendar-check me-2"></i>
                Lịch sinh hoạt lớp
            </h3>

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
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data['classSessionRequests']) && $data['classSessionRequests']['total'] == 0)
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="alert alert-info mb-0" role="alert">
                                            <i class="fas fa-info-circle me-2"></i> Hiện tại không có sinh hoạt lớp nào.
                                        </div>
                                    </td>
                                </tr>
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
                                        @php
                                            $statusMap = [
                                                0 => ['text' => 'Xác nhận', 'class' => 'bg-primary'],
                                                1 => ['text' => 'Xin vắng', 'class' => 'badge-warning'],
                                                2 => ['text' => 'Có mặt', 'class' => 'bg-success'],
                                                3 => ['text' => 'Vắng mặt', 'class' => 'bg-danger'],
                                                4 => ['text' => 'Chưa xác nhận', 'class' => 'bg-secondary'],
                                            ];
                                            if (!isset($data['attendanceStatus'])) {
                                            $status = 4;
                                            } else {
                                                $status = $data['attendanceStatus'];
                                            }
                                        @endphp
                                        <td>
                                                <span class="badge badge-status {{ $statusMap[$status]['class'] }}">
                                                    {{ $statusMap[$status]['text'] }}
                                                </span>
                                        </td>
                                        <td>
                                            <div
                                                class="action-buttons d-flex flex-column flex-md-row gap-2 justify-content-center">
                                                <a href="{{ route('student.class-session.detailClassSession', ['study-class-id' => $item['study_class']['id'], 'session-request-id' => $item['id']]) }}" class="btn btn-action btn-details">
                                                    <i class="fas fa-eye me-1"></i>Chi tiết
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
        $(document).ready(function () {

        });
    </script>
@endpush
