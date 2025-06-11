@extends('layouts.teacher')

@section('title', 'Thống kê')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Thống kê']]"/>
@endsection

@push('styles')
    <style>
        /* Custom styles */
        .stat-card {
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .class-card {
            transition: all 0.2s;
        }

        .class-card:hover {
            border-color: #007bff;
        }

        .warning-card {
            border-left: 4px solid;
        }

        .warning-card.danger {
            border-left-color: #dc3545;
        }

        .warning-card.warning {
            border-left-color: #ffc107;
        }

        .warning-card.info {
            border-left-color: #17a2b8;
        }

        /* Mobile optimizations */
        @media (max-width: 576px) {
            .mobile-full {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }

            .mobile-scroll {
                overflow-x: auto;
            }

            .mobile-scroll table {
                min-width: 800px;
            }

            .mobile-card {
                margin-bottom: 1rem;
            }

            .mobile-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        .nav-tabs .nav-link {
            color: #000;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }

        .nav-tabs .nav-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endpush

@section('main')
    <div class="bg-light min-vh-100">
        <!-- Header -->
        <div class="bg-white shadow-sm border-bottom">
            <div class="container py-3">
                <div class="row align-items-center">
                    <div class="col-12 mb-3 mb-md-0">
                        <h4 class="h3 mb-1">Thống kê</h4>
                    </div>
                </div>
            </div>
        </div>

        @if (isset($data['semesters']))
            <div class="container py-4">
                <!-- Overall Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-muted mb-0">Tổng số lớp</h6>
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <h2 class="mb-0">{{ $data['countStudyClassBySemester'] }}</h2>
                                <small class="text-muted">Lớp chủ nhiệm</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-muted mb-0">Tổng sinh viên</h6>
                                    <i class="fas fa-users text-success"></i>
                                </div>
                                <h2 class="mb-0">{{ $data['getTotalStudentsByLecturer'] }}</h2>
                                <small class="text-muted">Tất cả các lớp</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-muted mb-0">Buổi SHL</h6>
                                    <i class="fas fa-calendar text-info"></i>
                                </div>
                                <h2 class="mb-0">{{ $data['getTotalDoneSessionsByLecturer'] }}
                                    /{{ $data['getTotalSessionsByLecturer'] }}</h2>
                                <small class="text-muted">Đã hoàn thành</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-muted mb-0">Tỷ lệ tham gia TB</h6>
                                    <i class="fas fa-chart-line text-warning"></i>
                                </div>
                                <h2 class="mb-0">{{ $data['participationRate']->attendance_rate }}%</h2>
                                <small class="text-muted">Trung bình tất cả lớp</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end mb-4">
                    <form method="GET" class="input-group" action="{{ route('teacher.statistical.index') }}"
                          style="max-width: 280px;">
                        <select class="form-select" id="semesterSelect" name="semester_id">
                            @forelse($data['semesters'] ?? [] as $semester)
                                <option
                                    value="{{ $semester['id'] }}" {{ request()->get('semester_id') == $semester['id'] ? 'selected' : '' }}>
                                    {{ $semester['name'] }} - {{ $semester['school_year'] }}
                                </option>
                            @empty
                                <option value="" disabled>Không có học kỳ nào</option>
                            @endforelse
                        </select>
                        <button class="btn btn-primary" id="filterButton" type="submit">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                    </form>
                </div>
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4 mobile-tabs gap-1" id="lecturerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                data-bs-target="#overview" type="button" role="tab" aria-controls="overview"
                                aria-selected="true">Tổng quan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes"
                                type="button" role="tab" aria-controls="classes" aria-selected="false">Danh sách lớp
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities"
                                type="button" role="tab" aria-controls="activities" aria-selected="false">Hoạt động
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks"
                                type="button" role="tab" aria-controls="tasks" aria-selected="false">Công việc
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="lecturerTabsContent">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                        <div class="row">
                            <!-- Class Overview Cards -->
                            <div class="col-lg-7 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-white">
                                        <h5 class="mb-0">Tình hình các lớp</h5>
                                        <small class="text-muted">Thống kê nhanh từng lớp chủ nhiệm</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Class Card 1 -->
                                            @forelse($data['listStatisticsByLecturerId'] ?? [] as $item)
                                                <div class="col-12 mb-3">
                                                    <div class="card border class-card">
                                                        <div class="card-body">
                                                            <div
                                                                class="d-flex justify-content-between align-items-start mb-3">
                                                                <div>
                                                                    <h5 class="mb-1">{{ $item['class_name'] }}</h5>
                                                                    <p class="text-muted small mb-0">{{ $item['department_name'] }}</p>
                                                                </div>
                                                                <span class="badge bg-secondary">{{ $item['total_students'] }} sinh viên</span>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6 col-md-3 mb-2">
                                                                    <small class="text-muted d-block">SHL cố
                                                                        định:</small>
                                                                    <span class="fw-medium">{{ $item['fixed_sessions'] }}</span>
                                                                </div>
                                                                <div class="col-6 col-md-3 mb-2">
                                                                    <small class="text-muted d-block">SHL linh
                                                                        hoạt:</small>
                                                                    <span class="fw-medium text-warning">{{ $item['flexible_sessions'] }}</span>
                                                                </div>
                                                                <div class="col-6 col-md-3 mb-2">
                                                                    <small class="text-muted d-block">ĐRL xuất
                                                                        sắc:</small>
                                                                    <span class="fw-medium">{{ $item['high_conduct_students'] }}</span>
                                                                </div>
                                                                <div class="col-6 col-md-3 mb-2">
                                                                    <small class="text-muted d-block">Cảnh báo
                                                                        HV:</small>
                                                                    <span class="fw-medium text-danger">{{ $item['warned_students'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-info text-center" role="alert">
                                                        <strong>Chưa có dữ liệu thống kê cho lớp nào.</strong>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Warnings Summary -->
                            <div class="col-lg-5 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-white d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        <div>
                                            <h5 class="mb-0">Cảnh báo và vấn đề cần chú ý</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3 p-3 bg-danger bg-opacity-10 rounded warning-card danger">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-danger mb-1">Cảnh báo học vụ</h6>
                                                    <p class="text-danger small mb-0">Tổng cộng 6 sinh viên</p>
                                                </div>
                                                <span class="badge bg-danger">6</span>
                                            </div>
                                        </div>

                                        <div class="mb-3 p-3 bg-warning bg-opacity-10 rounded warning-card warning">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-warning mb-1">Tỷ lệ tham gia thấp</h6>
                                                    <p class="text-warning small mb-0">KTPM2021C (84.2%)</p>
                                                </div>
                                                <span class="badge bg-warning text-dark">Cần cải thiện</span>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-info bg-opacity-10 rounded warning-card info">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-info mb-1">Buổi SHL chưa hoàn thành</h6>
                                                    <p class="text-info small mb-0">6 buổi còn lại trong kỳ</p>
                                                </div>
                                                <span class="badge bg-info">6</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Classes Tab -->
                    <div class="tab-pane fade" id="classes" role="tabpanel" aria-labelledby="classes-tab">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Danh sách lớp chủ nhiệm</h5>
                                <small class="text-muted">Chi tiết thông tin các lớp đang quản lý</small>
                            </div>
                            <div class="card-body">
                                <!-- Desktop Table View -->
                                <div class="d-none d-md-block mobile-scroll">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Tên lớp</th>
                                            <th>Ngành học</th>
                                            <th>Niên khóa</th>
                                            <th>Số SV</th>
                                            <th>SHL</th>
                                            <th>Tỷ lệ tham gia</th>
                                            <th>Cảnh báo HV</th>
                                            <th>Hành động</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="fw-medium">CNTT2021A</td>
                                            <td>Công nghệ thông tin</td>
                                            <td>2021-2025</td>
                                            <td>45</td>
                                            <td class="fw-medium">8/10</td>
                                            <td><span class="fw-medium text-warning">87.5%</span></td>
                                            <td><span class="badge bg-danger">2</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">CNTT2022B</td>
                                            <td>Công nghệ thông tin</td>
                                            <td>2022-2026</td>
                                            <td>42</td>
                                            <td class="fw-medium">9/10</td>
                                            <td><span class="fw-medium text-success">92.3%</span></td>
                                            <td><span class="badge bg-danger">1</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">KTPM2021C</td>
                                            <td>Kỹ thuật phần mềm</td>
                                            <td>2021-2025</td>
                                            <td>38</td>
                                            <td class="fw-medium">7/10</td>
                                            <td><span class="fw-medium text-warning">84.2%</span></td>
                                            <td><span class="badge bg-danger">3</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mobile Card View -->
                                <div class="d-md-none">
                                    <!-- Class Card 1 -->
                                    <div class="card mb-3 mobile-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h5 class="mb-0">CNTT2021A</h5>
                                                <span class="badge bg-secondary">45 SV</span>
                                            </div>
                                            <p class="text-muted small mb-3">Công nghệ thông tin - 2021-2025</p>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">SHL:</small>
                                                    <span class="fw-medium">8/10</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Tham gia:</small>
                                                    <span class="fw-medium text-warning">87.5%</span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Cảnh báo HV:</small>
                                                    <span class="badge bg-danger">2</span>
                                                </div>
                                            </div>

                                            <button class="btn btn-outline-primary btn-sm w-100">Chi tiết</button>
                                        </div>
                                    </div>

                                    <!-- Class Card 2 -->
                                    <div class="card mb-3 mobile-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h5 class="mb-0">CNTT2022B</h5>
                                                <span class="badge bg-secondary">42 SV</span>
                                            </div>
                                            <p class="text-muted small mb-3">Công nghệ thông tin - 2022-2026</p>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">SHL:</small>
                                                    <span class="fw-medium">9/10</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Tham gia:</small>
                                                    <span class="fw-medium text-success">92.3%</span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Cảnh báo HV:</small>
                                                    <span class="badge bg-danger">1</span>
                                                </div>
                                            </div>

                                            <button class="btn btn-outline-primary btn-sm w-100">Chi tiết</button>
                                        </div>
                                    </div>

                                    <!-- Class Card 3 -->
                                    <div class="card mobile-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h5 class="mb-0">KTPM2021C</h5>
                                                <span class="badge bg-secondary">38 SV</span>
                                            </div>
                                            <p class="text-muted small mb-3">Kỹ thuật phần mềm - 2021-2025</p>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">SHL:</small>
                                                    <span class="fw-medium">7/10</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Tham gia:</small>
                                                    <span class="fw-medium text-warning">84.2%</span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Cảnh báo HV:</small>
                                                    <span class="badge bg-danger">3</span>
                                                </div>
                                            </div>

                                            <button class="btn btn-outline-primary btn-sm w-100">Chi tiết</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activities Tab -->
                    <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Hoạt động gần đây</h5>
                                <small class="text-muted">Các buổi sinh hoạt lớp và hoạt động mới nhất</small>
                            </div>
                            <div class="card-body">
                                <!-- Desktop Table View -->
                                <div class="d-none d-md-block mobile-scroll">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Lớp</th>
                                            <th>Hoạt động</th>
                                            <th>Ngày</th>
                                            <th>Hình thức</th>
                                            <th>Tỷ lệ tham gia</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><span class="badge bg-light text-dark">CNTT2021A</span></td>
                                            <td class="fw-medium">Sinh hoạt lớp tháng 11</td>
                                            <td>15/11/2024</td>
                                            <td><span class="badge bg-primary">Trực tiếp</span></td>
                                            <td class="fw-medium">40/45</td>
                                            <td><span class="badge bg-success">Đã hoàn thành</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-light text-dark">CNTT2022B</span></td>
                                            <td class="fw-medium">Họp lớp giữa kỳ</td>
                                            <td>10/11/2024</td>
                                            <td><span class="badge bg-info text-white">Trực tuyến</span></td>
                                            <td class="fw-medium">39/42</td>
                                            <td><span class="badge bg-success">Đã hoàn thành</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-light text-dark">KTPM2021C</span></td>
                                            <td class="fw-medium">Sinh hoạt đầu tháng</td>
                                            <td>05/11/2024</td>
                                            <td><span class="badge bg-primary">Trực tiếp</span></td>
                                            <td class="fw-medium">32/38</td>
                                            <td><span class="badge bg-success">Đã hoàn thành</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mobile Card View -->
                                <div class="d-md-none">
                                    <!-- Activity Card 1 -->
                                    <div class="card mb-3 mobile-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-light text-dark">CNTT2021A</span>
                                                <span class="badge bg-primary">Trực tiếp</span>
                                            </div>
                                            <h5 class="mb-3">Sinh hoạt lớp tháng 11</h5>
                                            <div class="d-flex justify-content-between mb-3">
                                                <small class="text-muted">15/11/2024</small>
                                                <span class="fw-medium">40/45</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="badge bg-success">Đã hoàn thành</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Activity Card 2 -->
                                    <div class="card mb-3 mobile-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-light text-dark">CNTT2022B</span>
                                                <span class="badge bg-info text-white">Trực tuyến</span>
                                            </div>
                                            <h5 class="mb-3">Họp lớp giữa kỳ</h5>
                                            <div class="d-flex justify-content-between mb-3">
                                                <small class="text-muted">10/11/2024</small>
                                                <span class="fw-medium">39/42</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="badge bg-success">Đã hoàn thành</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Activity Card 3 -->
                                    <div class="card mobile-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-light text-dark">KTPM2021C</span>
                                                <span class="badge bg-primary">Trực tiếp</span>
                                            </div>
                                            <h5 class="mb-3">Sinh hoạt đầu tháng</h5>
                                            <div class="d-flex justify-content-between mb-3">
                                                <small class="text-muted">05/11/2024</small>
                                                <span class="fw-medium">32/38</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="badge bg-success">Đã hoàn thành</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Tab -->
                    <div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Công việc cần hoàn thành</h5>
                                <small class="text-muted">Danh sách nhiệm vụ và deadline quan trọng</small>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <!-- Task 1 -->
                                    <div class="list-group-item border rounded mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="mb-1">Đánh giá điểm rèn luyện học kỳ 1</h5>
                                                <p class="text-muted small mb-0">Hạn: 15/12/2024</p>
                                            </div>
                                            <span class="badge bg-danger">Cao</span>
                                        </div>
                                        <div class="mt-3">
                                            <span class="badge bg-secondary me-1">CNTT2021A</span>
                                            <span class="badge bg-secondary me-1">CNTT2022B</span>
                                            <span class="badge bg-secondary">KTPM2021C</span>
                                        </div>
                                    </div>

                                    <!-- Task 2 -->
                                    <div class="list-group-item border rounded mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="mb-1">Lập kế hoạch sinh hoạt lớp tháng 12</h5>
                                                <p class="text-muted small mb-0">Hạn: 30/11/2024</p>
                                            </div>
                                            <span class="badge bg-warning text-dark">Trung bình</span>
                                        </div>
                                        <div class="mt-3">
                                            <span class="badge bg-secondary me-1">CNTT2021A</span>
                                            <span class="badge bg-secondary">CNTT2022B</span>
                                        </div>
                                    </div>

                                    <!-- Task 3 -->
                                    <div class="list-group-item border rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="mb-1">Báo cáo tình hình học tập cuối kỳ</h5>
                                                <p class="text-muted small mb-0">Hạn: 20/12/2024</p>
                                            </div>
                                            <span class="badge bg-danger">Cao</span>
                                        </div>
                                        <div class="mt-3">
                                            <span class="badge bg-secondary">KTPM2021C</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4">
                    <button class="btn btn-primary mobile-full mb-2 mb-sm-0">
                        <i class="fas fa-file-export me-2"></i>Xuất báo cáo tổng hợp
                    </button>
                    <button class="btn btn-outline-primary mobile-full mb-2 mb-sm-0">
                        <i class="fas fa-calendar-plus me-2"></i>Lên lịch sinh hoạt lớp
                    </button>
                    <button class="btn btn-outline-primary mobile-full mb-2 mb-sm-0">
                        <i class="fas fa-award me-2"></i>Đánh giá rèn luyện
                    </button>
                    <button class="btn btn-outline-primary mobile-full">
                        <i class="fas fa-user-graduate me-2"></i>Quản lý sinh viên
                    </button>
                </div>
            </div>
        @else
            <div class="container my-5">
                <div class="alert alert-warning text-center" role="alert">
                    <h4 class="alert-heading">Chưa có dữ liệu</h4>
                    <p>Vui lòng chọn học kỳ hoặc quay lại sau.</p>
                    <hr>
                    <p class="mb-0">Nếu bạn cần trợ giúp, hãy liên hệ với quản trị viên.</p>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Handle tab switching
            $('#lecturerTabs button').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Handle semester change
            // $('#semesterSelect').change(function() {
            //     // Add AJAX request to reload data based on selected semester
            //     const selectedSemester = $(this).val();
            //     // Example: window.location.href = `/lecturer/dashboard?semester=${selectedSemester}`;
            //
            //     // For demo purposes, just show a loading indicator
            //     // showLoading();
            //
            //     // Simulate loading delay
            //     setTimeout(function() {
            //         hideLoading();
            //     }, 800);
            // });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Mobile optimizations
            if (window.innerWidth < 576) {
                // Collapse some sections by default on mobile
            }

            // Function to show loading state
            // function showLoading() {
            //     $('body').append('<div class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75" style="z-index: 9999;" id="loadingOverlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // }
            //
            // // Function to hide loading state
            // function hideLoading() {
            //     $('#loadingOverlay').remove();
            // }
        });
    </script>
@endpush
