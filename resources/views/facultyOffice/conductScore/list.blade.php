@extends('layouts.facultyOffice')

@section('title', 'Danh sách lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Điểm rèn luyện', 'url' => 'faculty-office.conduct-score.index'],
        ['label' => 'Danh sách lớp học'],
    ]" />
@endsection

@push('styles')
    <style>

    </style>
@endpush

@section('main')
    <div class="container-fluid mt-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-end justify-content-md-between align-items-center">
                    <a href="{{ route('faculty-office.conduct-score.index') }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('faculty-office.conduct-score.infoConductScore') }}" class="position-relative">
                            <input type="hidden" name="conduct_evaluation_period_id" value="{{ $data['conduct_evaluation_period_id'] }}">
                            <div class="input-group me-2" style="width: 250px;">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm..."
                                       id="search">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="text-center mb-4 d-none d-md-block">Danh sách lớp học</h4>
        <!-- Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Lớp học</th>
                                    <th scope="col" class="d-none d-md-table-cell">Ngành/Bộ môn</th>
                                    <th scope="col">Tổng sinh viên</th>
                                    <th scope="col">Đã đánh giá</th>
                                    <th scope="col">Chưa đánh giá</th>
                                    <th scope="col">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Hardcoded data -->
                                @forelse($data['getStudyClassList']['data'] ?? [] as $item)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>{{ $item['study_class_name'] }}</td>
                                        <td class="d-none d-md-table-cell">
                                            {{ $item['major_name'] }} <br>
                                            <small class="text-muted">{{ $item['faculty_name'] }}</small>
                                        </td>
                                        <td>{{ $item['total_students'] }}</td>
                                        <td class="text-center">{{ $item['has_evaluated'] }}</td>
                                        <td class="text-center">
                                            {{ $item['not_evaluated'] }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('faculty-office.conduct-score.list', ['study_class_id' =>$item['class_id'], 'conduct_evaluation_period_id' => $data['conduct_evaluation_period_id']]) }}" class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
{{--                                                <a href="#" class="btn btn-outline-success btn-sm" title="Xuất Excel">--}}
{{--                                                    <i class="fas fa-file-excel"></i>--}}
{{--                                                </a>--}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <x-pagination.pagination :paginate="$data['getStudyClassList']" />
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush

