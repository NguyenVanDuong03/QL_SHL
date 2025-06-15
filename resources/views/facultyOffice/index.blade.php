@extends('layouts.facultyOffice')

@section('title', 'Trang chủ')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Trang chủ']]" />
@endsection

@section('main')
    <!-- Page Content -->
    <div class="container-fluid py-4">
        <!-- Stats Cards -->
        <!-- Notifications and Chart -->
        <div class="row g-4">
            <!-- Notifications -->
            <div class="col-lg-12">
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
        </div>
    </div>
@endsection
