@extends('layouts.classStaff')

@section('title', 'Trang chủ Cán sự lớp')

@push('styles')
    <style>
        .wave-bg {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white;
            border-radius: 0 0 20px 20px;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th, .table td {
            padding: 15px;
            vertical-align: middle;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table td:nth-child(5) {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        @media (max-width: 767.98px) {
            .table th, .table td {
                font-size: 0.85rem;
                padding: 10px;
            }
            .d-flex.flex-column {
                gap: 10px;
            }
            .btn-sm {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid bg-light min-vh-100">
        <!-- Header Section -->
        <div class="container py-5 wave-bg">
            <h1 class="text-center text-white fw-bold mb-4">Trang chủ Cán sự lớp</h1>
            <div class="row g-4 justify-content-center">
                <!-- Card for Class Meetings -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-2x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Sinh hoạt lớp</h5>
                            <p class="card-text text-muted">Tham gia và quản lý các buổi sinh hoạt lớp.</p>
                            <a href="#" class="btn btn-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                <!-- Card for Class Management -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Quản lý lớp học</h5>
                            <p class="card-text text-muted">Theo dõi và quản lý thông tin lớp học.</p>
                            <a href="#" class="btn btn-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                <!-- Card for Training Scores -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-star fa-2x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Điểm rèn luyện</h5>
                            <p class="card-text text-muted">Chấm và quản lý điểm rèn luyện.</p>
                            <a href="#" class="btn btn-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Meeting Table Section -->
        <div class="container mt-5">
            <div class="card shadow-sm border-0 p-4">
                <h2 class="text-primary fw-bold mb-4">Lịch sinh hoạt lớp</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-primary">
                        <tr>
                            <th scope="col">Lớp</th>
                            <th scope="col" class="d-none d-md-table-cell">Giáo viên</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Học kỳ</th>
                            <th scope="col">Hình thức</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Sinh hoạt lớp định kỳ</td>
                            <td  class="d-none d-md-table-cell">Kiều Tuấn Dũng</td>
                            <td>20:30 - 20/04/2025</td>
                            <td>63KTPM2</td>
                            <td>Lorem ipsum dolor sit amet consectetur adipisicing elit...</td>
                            <td><span class="badge bg-danger">Chưa tham gia</span></td>
                            <td>
                                <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                                    <button class="btn btn-primary btn-sm btn-join" data-id="1">Tham gia</button>
                                    <button class="btn btn-outline-danger btn-sm btn-absent" data-id="1">Xin vắng</button>
                                    <a href="#" class="btn btn-info btn-sm btn-details">Xem chi tiết</a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
{{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>--}}


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle Join button click
            $('.btn-join').click(function() {
                const meetingId = $(this).data('id');
                alert('Đã xác nhận tham gia buổi sinh hoạt lớp ID: ' + meetingId);
                // Add AJAX call here to update participation status
            });

            // Handle Absent button click
            $('.btn-absent').click(function() {
                const meetingId = $(this).data('id');
                alert('Đã gửi yêu cầu xin vắng cho buổi sinh hoạt lớp ID: ' + meetingId);
                // Add AJAX call here to submit absence request
            });
        });
    </script>
@endpush
