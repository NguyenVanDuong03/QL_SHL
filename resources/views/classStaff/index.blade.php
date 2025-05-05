@extends('layouts.classStaff')

@section('title', 'Trang chủ Cán sự lớp')

@section('main')
    <div>
        <div class="container-fluid wave-bg">
            <div class="container h-100">
                <div class="row g-4 d-flex justify-content-start align-items-end h-100 pb-5">
                    <div class="col-md-6 col-lg-3 card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0 fw-bold">Điểm rèn luyện</h5>
                                <button class="btn btn-square btn-primary text-white">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <p class="card-text text-muted">Chưa mở</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main content -->
        <div class="container mt-4">
            <div class="row g-4">
                <div class="card p-4 col-lg-12">
                    <h1 class="text-center text-primary fw-bold mb-4">Lịch sinh hoạt lớp</h1>

                    <div class="row">
                        <!-- Left column -->
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-3">Tiêu đề</h2>

                            <div class="mb-4">
                                <p>Giáo viên: Kiều Tuấn Dũng</p>
                                <p>Thời gian: 20:30 - 20/04/2025</p>
                                <p>Nội dung chính:</p>
                            </div>

                            <div class="mb-4">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit expedita magni hic
                                    exercitationem asperiores sed dolore nulla ratione distinctio, sequi doloribus ex
                                    facilis ad veniam architecto nostrum ipsam voluptas unde!</p>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="col-md-4">
                            <div class="text-end mb-4">
                                <p>Sinh hoạt lớp cố định</p>
                                <p>Lớp: 63KTPM2</p>
                            </div>

                            <div class="text-end">
                                <p>Xác nhận tham gia: <span class="text-danger">Chưa tham gia</span></p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="text-end">
                                <button class="btn btn-primary">Tham gia</button>
                                <button class="btn btn-danger">Xin vắng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
