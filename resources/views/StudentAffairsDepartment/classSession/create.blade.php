@extends('layouts.studentAffairsDepartment')

@section('title', 'Tạo lịch sinh hoạt lớp')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'student-affairs-department.class-session.index'],
        ['label' => 'Tạo lịch sinh hoạt lớp'],
    ]" />
@endsection

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="p-4">
            <div class="mb-4">
                <a href="{{ route('student-affairs-department.class-session.index') }}">
                    <i class="fas fa-arrow-left-long"></i>
                </a>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body position-relative">
                            <h5 class="card-title">Học kỳ: 1_2024_2025</h5>
                            <div class="mt-3">
                                <p class="mb-1">Thời gian bắt đầu: 20:00 20/04/2025</p>
                                <p class="mb-1">Thời gian kết thúc: 20:00 15/04/2025</p>
                            </div>
                            <button class="btn btn-primary position-absolute"
                                style="top: 10px; right: 10px; border-radius: 5px;">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
                {{--  --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body position-relative">
                            <h5 class="card-title">64KTPM1</h5>
                            <div class="mt-3">
                                <p class="mb-1">Thời gian: 20:00 20/04/2025</p>
                                <p class="mb-1">Hình thức: Trực tiếp tại trường</p>
                            </div>
                            <button class="btn btn-primary position-absolute"
                                style="top: 10px; right: 10px; border-radius: 5px;">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
                {{--  --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body position-relative">
                            <h5 class="card-title">64KTPM2</h5>
                            <div class="mt-3">
                                <p class="mb-1">Thời gian: 20:00 20/04/2025</p>
                                <p class="mb-1">Hình thức: Dã</p>
                            </div>
                            <button class="btn btn-primary position-absolute"
                                style="top: 10px; right: 10px; border-radius: 5px;">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
