@extends('layouts.classStaff')

@section('title', 'Trang chủ Cán sự lớp')

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

        .header-section {
            background: linear-gradient(135deg, var(--teal-600), var(--teal-700));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
        }

        .feature-card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--teal-50);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .feature-icon i {
            color: var(--teal-600);
            font-size: 1.5rem;
        }

        .btn-primary-custom {
            background-color: var(--primary-btn);
            border-color: var(--primary-btn);
            color: white;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-btn-hover);
            border-color: var(--primary-btn-hover);
            color: white;
            transform: translateY(-1px);
        }

        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .table-container {
            border-radius: 12px;
            overflow: hidden;
            background: white;
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

        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-joined {
            background-color: var(--teal-100);
            color: var(--teal-700);
        }

        .btn-action {
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-join {
            background-color: var(--primary-btn);
            color: white;
        }

        .btn-join:hover {
            background-color: var(--primary-btn-hover);
            color: white;
        }

        .btn-absent {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-absent:hover {
            background-color: #e2e8f0;
            color: #475569;
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

        .section-title {
            color: var(--teal-700);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .header-section {
                padding: 2rem 0;
            }

            .welcome-card {
                padding: 1.5rem;
            }

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
    <div class="min-vh-100">
        <!-- Header Section -->
        <div class="header-section">
            <div class="container">
                <div class="welcome-card">
                    <h1 class="h2 mb-3">Chào mừng đến với Trang Cán sự lớp</h1>
                    <p class="mb-0 opacity-90">Quản lý và theo dõi các hoạt động lớp học một cách hiệu quả</p>
                </div>
            </div>
        </div>

        <!-- Feature Cards Section -->
        <div class="container mb-5">
            <div class="row g-4">
                <!-- Sinh hoạt lớp -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h5 class="card-title text-center mb-3">Sinh hoạt lớp</h5>
                            <p class="card-text text-muted text-center mb-4">
                                Tham gia và quản lý các buổi sinh hoạt lớp định kỳ
                            </p>
                            <div class="text-center">
                                <a href="#" class="btn btn-primary-custom">Xem lịch</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quản lý lớp học -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="card-title text-center mb-3">Quản lý lớp học</h5>
                            <p class="card-text text-muted text-center mb-4">
                                Theo dõi thông tin và hoạt động của lớp học
                            </p>
                            <div class="text-center">
                                <a href="#" class="btn btn-primary-custom">Quản lý</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Điểm rèn luyện -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h5 class="card-title text-center mb-3">Điểm rèn luyện</h5>
                            <p class="card-text text-muted text-center mb-4">
                                Chấm điểm và đánh giá kết quả rèn luyện
                            </p>
                            <div class="text-center">
                                <a href="#" class="btn btn-primary-custom">Chấm điểm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Table Section -->
        <div class="container mb-5">
            <div class="card content-card">
                <div class="card-body p-4">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-check me-2"></i>
                        Lịch sinh hoạt lớp sắp tới
                    </h3>

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Sinh hoạt lớp</th>
                                    <th class="d-none d-md-table-cell">Giáo viên</th>
                                    <th>Thời gian</th>
                                    <th>Lớp</th>
                                    {{--                                    <th class="d-none d-xl-table-cell">Nội dung</th>--}}
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data['classSessionRequests']->isEmpty())
                                    <p>Không có yêu cầu nào.</p>
                                @else
                                    @foreach($data['classSessionRequests'] as $request)
                                        <tr>
                                            <td>
                                                <div
                                                    class="fw-semibold">{{ $request->type == 0 ? 'Cố định' : 'Linh hoạt' }}</div>
                                                <small
                                                    class="text-muted d-md-none">GV: {{ $request->lecturer->user->name }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">{{ $request->lecturer->user->name }}</td>
                                            <td>
                                                <div
                                                    class="fw-semibold">{{ \Carbon\Carbon::parse($request->proposed_at)->format('H:i') }}</div>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($request->proposed_at)->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-light text-dark">{{ $request->studyClass->name }}</span>
                                            </td>
                                            @php
                                                $statusMap = [
                                                    0 => ['text' => 'Xác nhận', 'class' => 'badge-pending'],
                                                    1 => ['text' => 'Xin vắng', 'class' => 'badge-warning'],
                                                    2 => ['text' => 'Có mặt', 'class' => 'badge-success'],
                                                    3 => ['text' => 'Vắng mặt', 'class' => 'badge-danger'],
                                                    4 => ['text' => 'Chưa xác nhận', 'class' => 'badge-pending'],
                                                ];
                                                if (!isset($data['attendanceStatus'])) {
                                                $status = 4;
                                                } else {
                                                    $status = $data['attendanceStatus']->status;
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
                                                    <a href="{{ route('class-staff.class-session.detailClassSession', ['study-class-id' => $request->studyClass->id, 'session-request-id' => $request->id]) }}" class="btn btn-action btn-details">
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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Handle Join button click
            $('.btn-join').click(function () {
                const meetingId = $(this).data('id');
                const button = $(this);

                // Show loading state
                button.html('<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...');
                button.prop('disabled', true);

                // Simulate API call
                setTimeout(function () {
                    // Update status badge
                    button.closest('tr').find('.badge-status')
                        .removeClass('badge-pending')
                        .addClass('badge-joined')
                        .text('Đã tham gia');

                    // Hide join and absent buttons
                    button.closest('.action-buttons').find('.btn-join, .btn-absent').hide();

                    // Show success message
                    showNotification('Đã xác nhận tham gia buổi sinh hoạt lớp thành công!', 'success');
                }, 1000);
            });

            // Handle Absent button click
            $('.btn-absent').click(function () {
                const meetingId = $(this).data('id');
                const button = $(this);

                // Show confirmation dialog
                if (confirm('Bạn có chắc chắn muốn xin vắng buổi sinh hoạt này?')) {
                    button.html('<i class="fas fa-spinner fa-spin me-1"></i>Đang gửi...');
                    button.prop('disabled', true);

                    // Simulate API call
                    setTimeout(function () {
                        showNotification('Đã gửi yêu cầu xin vắng thành công!', 'info');
                        button.html('<i class="fas fa-check me-1"></i>Đã gửi yêu cầu');
                        button.removeClass('btn-absent').addClass('btn-secondary');
                        button.prop('disabled', true);
                    }, 1000);
                }
            });

            // Notification function
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' :
                    type === 'info' ? 'alert-info' : 'alert-warning';

                const notification = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                         style="top: 20px; right: 20px; z-index: 1050; min-width: 300px;">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                $('body').append(notification);

                // Auto remove after 5 seconds
                setTimeout(function () {
                    notification.alert('close');
                }, 5000);
            }
        });
    </script>
@endpush
