@extends('layouts.teacher')

@section('title', 'Sinh hoạt lớp')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Sinh hoạt lớp']]"/>
@endsection

@push('styles')
    <style>
        @media screen and (max-width: 768px) {
            .class-flexible-m {
                margin-top: 20px;
            }
        }

        .title-class-session {
            margin-right: 40px;
        }
    </style>
@endpush

@section('main')
    <!-- Content -->
    <div class="p-4">
        <!-- Cards -->
        <div class="row mb-4">
            <div class="col-md-6" style="height: 130px">
                <div class="card shadow-sm h-100">
                    <div class="card-body position-relative">
                        <h5 class="card-title title-class-session">ĐĂNG KÝ SINH HOẠT LỚP CỐ ĐỊNH</h5>
                        @if ($data['checkClassSessionRegistration'])
                            <p class="text-muted m-0">Ngày bắt đầu:
                                {{ \Carbon\Carbon::parse($data['getCSRSemesterInfo']->open_date)->format('H:i d/m/Y') }}</p>
                            <p class="text-muted m-0">Ngày kết thúc:
                                {{ \Carbon\Carbon::parse($data['getCSRSemesterInfo']->end_date)->format('H:i d/m/Y') }}</p>
                        @else
                            <p class="text-muted m-0">Chưa mở</p>
                        @endif
                        <a class="btn btn-primary position-absolute {{ $data['checkClassSessionRegistration'] && isset($data['getClassSessionRegistration']->open_date) && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($data['getClassSessionRegistration']->open_date)) ? '' : 'disabled' }}"
                           href="{{ route('teacher.class-session.fixed-class-activitie') }}"
                           style="top: 10px; right: 10px; width: 40px; height: 40px; border-radius: 5px;">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 class-flexible-m" style="height: 130px">
                <div class="card shadow-sm h-100">
                    <div class="card-body position-relative">
                        <h5 class="card-title title-class-session">LỊCH SINH HOẠT LỚP LINH HOẠT</h5>
                        @if ($data['countFlexibleClassSessionRequest'] > 0)
                            <p class="text-muted m-0">Có {{ $data['countFlexibleClassSessionRequest'] }} lịch họp</p>
                        @else
                            <p class="text-muted m-0">Không có lịch họp</p>
                        @endif
                        <a class="btn btn-primary position-absolute"
                           href="{{ route('teacher.class-session.flexible-class-activitie') }}"
                           style="top: 10px; right: 10px; width: 40px; height: 40px; border-radius: 5px;">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 class-flexible-m mt-3" style="height: 130px">
                <div class="card shadow-sm h-100">
                    <div class="card-body position-relative">
                        <h5 class="card-title title-class-session">LỊCH SINH HOẠT CỐ ĐỊNH</h5>
                        @if ($data['countFixeClassSessionRequest'] > 0)
                            <p class="text-muted m-0">Có {{ $data['countFixeClassSessionRequest'] }} lịch họp</p>
                        @else
                            <p class="text-muted m-0">Không có lịch họp</p>
                        @endif
                        <a class="btn btn-primary position-absolute {{ $data['countFixeClassSessionRequest'] > 0 ? '' : 'disabled' }}"
                           href="{{ route('teacher.class-session.detailFixedClassActivitie') }}"
                           style="top: 10px; right: 10px; width: 40px; height: 40px; border-radius: 5px;">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

        });
    </script>

@endsection
