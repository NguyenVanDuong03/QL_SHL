@extends('layouts.app')

@section('title', 'Trang chủ - Hệ thống Quản lý Sinh hoạt Lớp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endpush

@section('content')
    <div class="d-flex flex-column min-vh-100">
        <!-- Header -->
        <header class="sticky-top border-bottom">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="navbar-brand d-flex align-items-center">
                        <img src="{{asset('images/LogoTlu.png')}}" alt="Logo Trường Đại học Thủy lợi" width="32" height="32" class="rounded me-2">
                    </div>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link smooth-scroll" href="#features">Tính năng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link smooth-scroll" href="#roles">Vai trò</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link smooth-scroll" href="#about">Giới thiệu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link smooth-scroll" href="#contact">Liên hệ</a>
                            </li>
                        </ul>
                        <div class="d-flex mt-2 mt-md-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Đăng nhập</a>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow-1">
            <!-- Hero Section -->
            <section class="hero-gradient pt-5">
                <div class="container py-3">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <h1 class="display-4 fw-bold mb-3">
                                    Hệ thống Quản lý Sinh hoạt Lớp
                                </h1>
                                <p class="lead text-muted mb-4">
                                    Nền tảng quản lý sinh hoạt lớp hiệu quả cho Trường Đại học Thủy lợi, kết nối giữa sinh viên, cán bộ
                                    lớp, giáo viên chủ nhiệm và phòng công tác sinh viên.
                                </p>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="{{ route('login') }}" class="btn btn-teal btn-lg">
                                    Đăng nhập ngay
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                                <a href="#features" class="btn btn-outline-secondary btn-lg smooth-scroll">Tìm hiểu thêm</a>
                            </div>
                        </div>
                        <div class="col-lg-6 text-center">
                            <img src="{{ asset('images/webtsthumb.jpg') }}" alt="Hệ thống Quản lý Sinh hoạt Lớp" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="pt-5">
                <div class="container py-3">
                    <div class="text-center mb-5">
                        <span class="badge bg-teal-100 text-teal-700 mb-3">Tính năng</span>
                        <h2 class="display-5 fw-bold mb-3">Quản lý sinh hoạt lớp hiệu quả</h2>
                        <p class="lead text-muted">
                            Hệ thống cung cấp đầy đủ các công cụ để quản lý sinh hoạt lớp, điểm danh và đánh giá điểm rèn luyện.
                        </p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-calendar-days feature-icon me-3"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Lịch sinh hoạt lớp</h5>
                                            <p class="card-text text-muted small mb-0">Đăng ký và quản lý lịch sinh hoạt lớp</p>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted">
                                        Đăng ký thời gian, địa điểm và hình thức sinh hoạt lớp (online, offline, dã ngoại).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-check-circle feature-icon me-3"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Điểm danh</h5>
                                            <p class="card-text text-muted small mb-0">Quản lý điểm danh sinh viên</p>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted">
                                        Điểm danh sinh viên tham gia sinh hoạt lớp, thống kê tỷ lệ tham gia.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-clipboard-check feature-icon me-3"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Đánh giá điểm rèn luyện</h5>
                                            <p class="card-text text-muted small mb-0">Quản lý đánh giá điểm rèn luyện</p>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted">
                                        Sinh viên tự đánh giá điểm rèn luyện, GVCN phê duyệt đánh giá.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-file-excel feature-icon me-3"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Báo cáo thống kê</h5>
                                            <p class="card-text text-muted small mb-0">Xuất báo cáo và thống kê</p>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted">
                                        Xuất báo cáo Excel về điểm danh và điểm rèn luyện, thống kê số buổi họp.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-users feature-icon me-3"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Quản lý người dùng</h5>
                                            <p class="card-text text-muted small mb-0">Quản lý tài khoản người dùng</p>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted">
                                        Thêm, sửa, xóa tài khoản GVCN và sinh viên, phân quyền người dùng.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-exclamation-triangle feature-icon me-3"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Quản lý cảnh báo học vụ</h5>
                                            <p class="card-text text-muted small mb-0">Quản lý sinh viên bị cảnh báo học vụ</p>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted">
                                        Thêm, sửa, xóa danh sách sinh viên bị cảnh báo học vụ vào hệ thống.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Roles Section -->
            <section id="roles" class="pt-5 bg-light">
                <div class="container py-3">
                    <div class="text-center mb-5">
                        <span class="badge bg-teal-100 text-teal-700 mb-3">Vai trò</span>
                        <h2 class="display-5 fw-bold mb-3">Phân quyền người dùng</h2>
                        <p class="lead text-muted">
                            Hệ thống phân quyền rõ ràng cho từng đối tượng người dùng
                        </p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <ul class="nav nav-pills nav-justified mb-4" id="rolesTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="ctsv-tab" data-bs-toggle="pill" data-bs-target="#ctsv" type="button" role="tab">
                                        Phòng CTSV
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="gvcn-tab" data-bs-toggle="pill" data-bs-target="#gvcn" type="button" role="tab">
                                        GVCN
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="canbo-tab" data-bs-toggle="pill" data-bs-target="#canbo" type="button" role="tab">
                                        Cán bộ lớp
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="sinhvien-tab" data-bs-toggle="pill" data-bs-target="#sinhvien" type="button" role="tab">
                                        Sinh viên
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="rolesTabContent">
                                <div class="tab-pane fade show active" id="ctsv" role="tabpanel" style="height: 325px;">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h3 class="h4 mb-4">Phòng Chính trị và Công tác Sinh viên</h3>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Phê duyệt thời gian mở đăng ký sinh hoạt lớp (SHL)</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Phê duyệt hình thức SHL (họp online, offline tại trường hoặc dã ngoại)</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Phê duyệt thời gian mở đánh giá điểm rèn luyện (ĐRL)</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Nhận báo cáo tình hình sinh hoạt lớp từ cán bộ lớp</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Quản lý danh sách sinh viên bị cảnh báo học vụ vào hệ thống</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Quản lý tài khoản GVCN và tài khoản sinh viên</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="gvcn" role="tabpanel" style="height: 325px;">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h3 class="h4 mb-4">Giáo viên chủ nhiệm</h3>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Đăng ký thời gian, phương thức SHL (online/offline/dã ngoại)</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Chuẩn bị nội dung cuộc họp, điểm danh</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Phê duyệt đánh giá ĐRL</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Thống kê số buổi họp đã tổ chức, tỷ lệ tham gia</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Xuất báo cáo (Excel) về điểm danh và ĐRL</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="canbo" role="tabpanel" style="height: 325px;">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h3 class="h4 mb-4">Cán sự lớp</h3>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Xác nhận tham gia SHL</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Điểm danh và tự đánh giá ĐRL</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Quản lý báo cáo tình hình sinh hoạt lớp sau mỗi buổi SHL</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="sinhvien" role="tabpanel" style="height: 325px;">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h3 class="h4 mb-4">Sinh viên</h3>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Xác nhận tham gia SHL</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Đánh giá ĐRL</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section id="about" class="pt-5 mt-4">
                <div class="container py-3">
                    <div class="text-center mb-5">
                        <span class="badge bg-teal-100 text-teal-700 mb-3">Giới thiệu</span>
                        <h2 class="display-5 fw-bold mb-3">Về Trường Đại học Thủy lợi</h2>
                        <p class="lead text-muted">
                            Trường Đại học Thủy lợi - Học tập vì ngày mai lập nghiệp
                        </p>
                    </div>

                    <div class="row g-5 align-items-center">
                        <div class="col-lg-6">
                            <img src="{{ asset('images/bannertlu.jpg') }}" alt="Trường Đại học Thủy lợi" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <h3 class="h4 fw-bold mb-3">Lịch sử hình thành</h3>
                                <p class="text-muted mb-4">
                                    Trường Đại học Thủy lợi được thành lập năm 1959, là một trong những trường đại học kỹ thuật hàng đầu Việt Nam
                                    trong lĩnh vực thủy lợi, thủy điện và môi trường. Với hơn 60 năm xây dựng và phát triển, trường đã đào tạo
                                    hàng chục nghìn kỹ sư, cử nhân chất lượng cao.
                                </p>
                            </div>

                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-award text-teal-600 fs-3"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">60+ năm</h5>
                                            <p class="text-muted mb-0">Kinh nghiệm đào tạo</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-users text-teal-600 fs-3"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">20,000+</h5>
                                            <p class="text-muted mb-0">Sinh viên</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-graduation-cap text-teal-600 fs-3"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">40+</h5>
                                            <p class="text-muted mb-0">Chuyên ngành đào tạo</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-globe text-teal-600 fs-3"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">100+</h5>
                                            <p class="text-muted mb-0">Đối tác quốc tế</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-bullseye text-teal-600 fs-1 mb-3"></i>
                                    <h5 class="card-title">Sứ mệnh</h5>
                                    <p class="card-text text-muted">
                                        Đào tạo nguồn nhân lực, nghiên cứu, chuyển giao khoa học
                                        công nghệ chất lượng cao trong các lĩnh vực kỹ thuật, công nghệ, kinh tế đóng góp vào sự phát triển bền vững của đất nước.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-eye text-teal-600 fs-1 mb-3"></i>
                                    <h5 class="card-title">Tầm nhìn</h5>
                                    <p class="card-text text-muted">
                                        Đến năm 2050, là cơ sở giáo dục đại học đa ngành, định hướng nghiên cứu, một trong 10 trường hàng đầu ở Việt Nam trong các lĩnh vực kỹ thuật, công nghệ và kinh tế; giữ vững vị trí số một trong lĩnh vực thuỷ lợi, thủy điện (năng lượng tái tạo), tài nguyên, môi trường, phòng chống và giảm nhẹ thiên tai.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-heart text-teal-600 fs-1 mb-3"></i>
                                    <h5 class="card-title">Giá trị cốt lõi</h5>
                                    <p class="card-text text-muted">
                                        Chất lượng, Sáng tạo, Trách nhiệm, Hợp tác và Phát triển bền vững
                                        là những giá trị cốt lõi của trường.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="pt-5 bg-light">
                <div class="container py-3">
                    <div class="text-center mb-5">
                        <span class="badge bg-teal-100 text-teal-700 mb-3">Liên hệ</span>
                        <h2 class="display-5 fw-bold mb-3">Thông tin liên hệ</h2>
                        <p class="lead text-muted">
                            Liên hệ với chúng tôi để được hỗ trợ và giải đáp thắc mắc
                        </p>
                    </div>

                    <div class="row g-5">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow">
                                <div class="card-body p-4">
                                    <h4 class="card-title mb-4">Gửi tin nhắn cho chúng tôi</h4>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="contactName" class="form-label">Họ và tên</label>
                                                <input type="text" class="form-control" id="contactName" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contactEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="contactEmail" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contactPhone" class="form-label">Số điện thoại</label>
                                                <input type="tel" class="form-control" id="contactPhone">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contactSubject" class="form-label">Chủ đề</label>
                                                <select class="form-select" id="contactSubject" required>
                                                    <option value="">Chọn chủ đề</option>
                                                    <option value="support">Hỗ trợ kỹ thuật</option>
                                                    <option value="account">Vấn đề tài khoản</option>
                                                    <option value="feature">Đề xuất tính năng</option>
                                                    <option value="other">Khác</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label for="contactMessage" class="form-label">Nội dung tin nhắn</label>
                                                <textarea class="form-control" id="contactMessage" rows="6" required></textarea>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-teal">
                                                    <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="card border-0 shadow h-100">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-location-dot text-teal-600 fs-2 mb-3"></i>
                                            <h5 class="card-title">Địa chỉ</h5>
                                            <p class="card-text text-muted mb-0">
                                                175 Tây Sơn, Đống Đa, Hà Nội<br>
                                                Việt Nam
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 shadow h-100">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-phone-alt text-teal-600 fs-2 mb-3"></i>
                                            <h5 class="card-title">Điện thoại</h5>
                                            <p class="card-text text-muted mb-0">
                                                <a href="tel:+842438522201" class="text-decoration-none text-muted">
                                                    (024) 3852 2201
                                                </a><br>
                                                <a href="tel:+842438522202" class="text-decoration-none text-muted">
                                                    (024) 3852 2202
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 shadow h-100">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-envelope text-teal-600 fs-2 mb-3"></i>
                                            <h5 class="card-title">Email</h5>
                                            <p class="card-text text-muted mb-0">
                                                <a href="mailto:info@tlu.edu.vn" class="text-decoration-none text-muted">
                                                    info@tlu.edu.vn
                                                </a><br>
                                                <a href="mailto:support@tlu.edu.vn" class="text-decoration-none text-muted">
                                                    support@tlu.edu.vn
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 shadow h-100">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-clock text-teal-600 fs-2 mb-3"></i>
                                            <h5 class="card-title">Giờ làm việc</h5>
                                            <p class="card-text text-muted mb-0">
                                                Thứ 2 - Thứ 6: 8:00 - 17:00<br>
                                                Thứ 7: 8:00 - 12:00<br>
                                                Chủ nhật: Nghỉ
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-0">
                                    <div class="ratio ratio-21x9">
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.6962917394973!2d105.82117831533216!3d21.006379986013447!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac76ccbcace1%3A0x3419db7d6b20b367!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBUaOG7p3kgbOG7o2k!5e0!3m2!1svi!2s!4v1635123456789!5m2!1svi!2s"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section id="login" class="pt-5 bg-teal-50">
                <div class="container py-3">
                    <div class="text-center">
                        <h2 class="display-5 fw-bold mb-3">Sẵn sàng sử dụng?</h2>
                        <p class="lead text-muted mb-4">
                            Đăng nhập vào hệ thống để bắt đầu quản lý sinh hoạt lớp
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-teal btn-lg">Đăng nhập ngay</a>
                    </div>
                </div>
            </section>
        </main>
        <!-- Footer -->
        <x-footer.footer />
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('a.smooth-scroll, a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    var headerOffset = 80; // chiều cao header cố định
                    var offsetPosition = target.offset().top - headerOffset;
                    $('html, body').animate({
                        scrollTop: offsetPosition
                    }, 600); // 600ms cho hiệu ứng mượt mà
                }
            });

            var sections = $('section[id]');
            var navLinks = $('.navbar-nav .nav-link[href^="#"]');

            function updateActiveNavLink() {
                var current = '';
                sections.each(function() {
                    var sectionTop = $(this).offset().top - $(window).scrollTop();
                    if (sectionTop <= 100) {
                        current = $(this).attr('id');
                    }
                });

                navLinks.removeClass('active');
                navLinks.each(function() {
                    if ($(this).attr('href') === '#' + current) {
                        $(this).addClass('active');
                    }
                });
            }

            $(window).on('scroll', updateActiveNavLink);

            // Gọi lần đầu để thiết lập đúng trạng thái active khi load trang
            updateActiveNavLink();
        });
    </script>
@endpush
