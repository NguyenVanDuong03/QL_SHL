@extends('layouts.teacher')

@section('title', 'Trang chủ Giáo viên')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Trang chủ']]" />
@endsection

@section('main')
    <!-- Page Content -->
    <div class="container-fluid py-4">
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">TỔNG SỐ LỚP CHỦ NHIỆM</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalClasses'] }} lớp</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">SINH VIÊN ĐĂNG BÁO HỌC VỤ</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalStudentWarning'] }} sinh viên</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">ĐIỂM ĐÁNH KỲ 2_2024_2025</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">100% sinh viên đã điểm danh</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">ĐIỂM RÈN LUYỆN KỲ 2_2024_2025</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">100% sinh viên đã đánh giá</div>
                </div>
            </div>
        </div>

        <!-- Notifications and Chart -->
        <div class="row g-4">
            <!-- Notifications -->
            <div class="col-lg-8">
                <div class="notification-card">
                    <h5 class="mb-4">Thông báo</h5>

                    <!-- Notification 1 -->
                    <div class="notification-item">
                        <div class="notification-header">
                            <div class="notification-title">Phòng Công tác và Chính trị</div>
                            <a href="#" class="text-primary">Xem chi tiết</a>
                        </div>
                        <div class="notification-date">19:00 16/04/2023</div>
                        <div class="notification-content">đã phê duyệt sinh hoạt lớp 63KTPM2</div>
                    </div>

                    <!-- Notification 2 -->
                    <div class="notification-item">
                        <div class="notification-header">
                            <div class="notification-title">Phòng Công tác và Chính trị</div>
                            <a href="#" class="text-primary">Xem chi tiết</a>
                        </div>
                        <div class="notification-date">19:00 16/04/2023</div>
                        <div class="notification-content">đã phê duyệt sinh hoạt lớp 63KTPM1</div>
                    </div>

                    <!-- Notification 3 -->
                    <div class="notification-item">
                        <div class="notification-header">
                            <div class="notification-title">Phòng Công tác và Chính trị</div>
                            <a href="#" class="text-primary">Xem chi tiết</a>
                        </div>
                        <div class="notification-date">19:00 16/04/2023</div>
                        <div class="notification-content">
                            <p class="mb-1">lịch sinh hoạt lớp học kỳ 2_2024_2025</p>
                            <p class="mb-1">Thời gian bắt đầu đăng ký: 20:00 12/04/2025</p>
                            <p class="mb-0">Thời gian kết thúc đăng ký: 20:00 15/04/2025</p>
                        </div>
                    </div>

                    <!-- View All Button -->
                    <div class="text-center mt-4">
                        <button class="btn btn-primary view-all-btn">Xem tất cả</button>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Điểm rèn luyện</div>
                    </div>
                    <div class="chart-subtitle">
                        <div class="chart-label">TỶ LỆ</div>
                        <div class="chart-class">63KTPM2</div>
                    </div>

                    <!-- Bar Chart -->
                    <div class="chart-container">
                        <div class="chart-bar" style="height: 70%;"></div>
                        <div class="chart-bar" style="height: 40%;"></div>
                        <div class="chart-bar" style="height: 85%;"></div>
                        <div class="chart-bar" style="height: 60%;"></div>
                        <div class="chart-bar" style="height: 35%;"></div>
                        <div class="chart-bar" style="height: 80%;"></div>
                    </div>

                    <!-- Chart Labels -->
                    <div class="chart-labels">
                        <div>Kém</div>
                        <div>Yếu</div>
                        <div>Trung bình</div>
                        <div>Khá</div>
                        <div>Tốt</div>
                        <div>Xuất sắc</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('common.updateLecturer')
@endsection
