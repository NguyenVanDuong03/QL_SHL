@extends('layouts.teacher')

@section('title', 'Sinh hoạt lớp cố định')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'teacher.class-session.index'],
        ['label' => 'Sinh hoạt lớp cố định'],
    ]"/>
@endsection

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <div class="mb-2">
                        <a href="{{ route('teacher.class-session.index') }}">
                            <i class="fas fa-arrow-left-long"></i>
                        </a>
                    </div>
                    <h4>{{ $data['getCSRSemesterInfo']->name }} -
                        {{ $data['getCSRSemesterInfo']->school_year }}</h4>
                    <p class="p-0 m-0">Ngày bắt đầu:
                        {{ \Carbon\Carbon::parse($data['getCSRSemesterInfo']->open_date)->format('H:i d/m/Y') }}
                    </p>
                    <p class="p-0 m-0">Ngày kết thúc:
                        {{ \Carbon\Carbon::parse($data['getCSRSemesterInfo']->end_date)->format('H:i d/m/Y') }}
                    </p>
                </div>
                <div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Tìm kiếm lớp học"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <button class="input-group-text" id="basic-addon2"><i class="fas fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-primary" href="#">
                            Lịch sử
                        </a>
                    </div>
                </div>
            </div>


            <div class="row g-4">
                @if (isset($data['getStudyClassByIds']) && $data['getStudyClassByIds']['total'] > 0)
                    @foreach ($data['getStudyClassByIds']['data'] as $class)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm">
                                <div class="card-body position-relative">
                                    <h5 class="card-title">Lớp: {{ $class['name'] }}</h5>
                                    <div class="mt-3">
                                        <p class="my-0">Ngành:
                                            {{ $class['major']['name'] }}</p>
                                        <p class="my-0">Khoa:
                                            {{ $class['major']['faculty']['department']['name'] }}
                                        </p>
                                    </div>
                                    <a class="btn btn-primary position-absolute" href="{{ route('teacher.class-session.create',  $class['id']) }}"
                                            style="top: 10px; right: 10px; border-radius: 5px;">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-md-12">
                        <x-pagination.pagination :paginate="$data['getStudyClassByIds']"/>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="text-center alert alert-warning" role="alert">
                            Tất cả các lớp đã được đăng ký.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(document).ready(function () {

        });
    </script>

@endsection
