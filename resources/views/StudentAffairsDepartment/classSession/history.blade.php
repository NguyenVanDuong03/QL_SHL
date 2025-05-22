@extends('layouts.studentAffairsDepartment')

@section('title', 'Lịch sử sinh hoạt lớp')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'student-affairs-department.class-session.index'],
        ['label' => 'Lịch sử'],
    ]" />
@endsection

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <a href="{{ route('student-affairs-department.class-session.index') }}">
                        <i class="fas fa-arrow-left-long"></i>
                    </a>
                </div>
                <div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Tìm kiếm Lớp học"
                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <button class="input-group-text" id="basic-addon2"><i class="fas fa-magnifying-glass"></i></button>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                    @if (isset($data['data']) && $data['total'] > 0)
                        @foreach ($data['data'] as $class)
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-body position-relative">
                                        <h5 class="card-title">{{ $class['name'] }}</h5>
                                        <div class="mt-3">
                                            <p class="mb-1">Thời gian:
                                                {{ \Carbon\Carbon::parse($class['proposed_at'])->format('H:i d/m/Y') }}</p>
                                            <p class="mb-1">Hình thức:
                                                @if ($class['position'] == 0)
                                                    Trực tiếp tại trường
                                                @elseif ($class['position'] == 1)
                                                    Trực tuyến
                                                @elseif ($class['position'] == 2)
                                                    Dã ngoại
                                                @endif
                                            </p>
                                        </div>
                                        <button class="btn btn-primary position-absolute"
                                            style="top: 10px; right: 10px; border-radius: 5px;" data-bs-toggle="modal"
                                            data-bs-target="#onlineModal" data-id="#">
                                            Xem chi tiết
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-md-12">
                            <div class="d-flex justify-content-center mt-4">
                                @include('components.pagination.pagination', [
                                    'paginate' => $data,
                                ])
                            </div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="text-center alert alert-warning" role="alert">
                                Chưa có lớp nào đăng ký.
                            </div>
                        </div>
                    @endif
                </div>
        </div>
    </div>
@endsection
