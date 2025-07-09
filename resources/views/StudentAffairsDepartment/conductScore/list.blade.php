@extends('layouts.studentAffairsDepartment')

@section('title', 'Danh sách điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Điểm rèn luyện', 'url' => 'student-affairs-department.conduct-score.index'],
        ['label' => 'Danh sách điểm rèn luyện'],
    ]"/>
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
                <a href="{{ route('student-affairs-department.conduct-score.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" id="searchForm" action="{{ route('student-affairs-department.conduct-score.infoConductScore', ['conduct_evaluation_period_id' => request('conduct_evaluation_period_id')]) }}">
                            <input type="hidden" name="conduct_evaluation_period_id" value="{{ request('conduct_evaluation_period_id') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="department_filter" class="form-label">Khoa</label>
                                    <select class="form-select" id="department_filter" name="department_id">
                                        <option value="">Tất cả khoa</option>
                                        @forelse($data['departments'] ?? [] as $item)
                                            <option value="{{ $item['id'] }}"
                                                {{ request('department_id') == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['name'] }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Không có khoa nào</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="cohort_filter" class="form-label">Niên khóa</label>
                                    <select class="form-select" id="cohort_filter" name="cohort_id">
                                        <option value="">Tất cả niên khóa</option>
                                        @forelse($data['cohorts'] ?? [] as $item)
                                            <option value="{{ $item['id'] }}"
                                                {{ request('cohort_id') == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['name'] }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Không có niên khóa nào</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-4 d-md-flex align-items-md-end justify-content-md-end">

                                    <div class="input-group me-2">
                                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm..."
                                               id="search">
                                        <button class="btn btn-primary" id="filterBtn">
                                            <i class="fas fa-filter me-2"></i>Lọc
                                        </button>
                                    </div>
                                </div>
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
                                    <th scope="col" class="d-none d-md-table-cell">Ngành/Khoa</th>
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
                                            <small class="text-muted">{{ $item['department_name'] }}</small>
                                        </td>
                                        <td>{{ $item['total_students'] }}</td>
                                        <td class="text-center">{{ $item['has_evaluated'] }}</td>
                                        <td class="text-center">
                                            {{ $item['not_evaluated'] }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group gap-2" role="group">
                                                <button class="btn btn-outline-primary btn-sm btn-detail"
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="modal" data-bs-target="#detailModal"
                                                   data-study-class-name="{{ $item['study_class_name'] }}"
                                                   data-major-name="{{ $item['major_name'] }}"
                                                   data-department-name="{{ $item['department_name'] }}"
                                                   data-cohort-name="{{ $item['cohort_name'] }}"
                                                   data-lecturer-name="{{ $item['lecturer_name'] }}"
                                                   data-total-students="{{ $item['total_students'] }}"
                                                   data-total-has-evaluated="{{ $item['has_evaluated'] }}"
                                                   data-total-not-evaluated="{{ $item['not_evaluated'] }}"
                                                   data-outstanding="{{ $item['outstanding'] }}"
                                                   data-good="{{ $item['good'] }}"
                                                   data-fair="{{ $item['fair'] }}"
                                                   data-average="{{ $item['average'] }}"
                                                   data-poor="{{ $item['poor'] }}"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('student-affairs-department.conduct-score.exportConductScore', ['semester_id' => $data['semesterId'], 'study_class_id' => $item['class_id'], 'study_class_name' => $item['study_class_name']]) }}" target="_blank" class="btn btn-outline-success btn-sm" title="Xuất Excel">
                                                    <i class="fas fa-file-excel"></i>
                                                </a>
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
        <x-pagination.pagination :paginate="$data['getStudyClassList']"/>

        <!-- Modal Xem Chi Tiết -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="detailModalLabel">Thông tin chi tiết</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Tên lớp:</label>
                                    <p class="study_class_name"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Ngành:</label>
                                    <p class="major_name"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Khoa:</label>
                                    <p class="department_name"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Niên khóa:</label>
                                    <p class="cohort_name"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Giáo viên chủ nhiệm:</label>
                                    <p class="lecturer_name"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Tổng sinh viên:</label>
                                    <p class="total_students"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold">Đánh giá ĐRL:</label>
                                    <p class="total_conduct_score"></p>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3">Thống kê điểm rèn luyện</h6>
                        <div class="row mb-4 g-3">
                            <div class="col-md-2 col-6">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0 outstanding"></h3>
                                        <p class="mb-0">Xuất sắc</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0 good"></h3>
                                        <p class="mb-0">Tốt</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0 fair"></h3>
                                        <p class="mb-0">Khá</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="card bg-warning text-dark h-100">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0 average"></h3>
                                        <p class="mb-0">Trung bình</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0 poor"></h3>
                                        <p class="mb-0">Kém</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @push('scripts')
            <script>
                $(document).ready(function () {
                    $('.btn-detail').on('click', function () {
                        const studyClassName = $(this).data('study-class-name');
                        const majorName = $(this).data('major-name');
                        const departmentName = $(this).data('department-name');
                        const cohortName = $(this).data('cohort-name');
                        const lecturerName = $(this).data('lecturer-name');
                        const totalStudents = $(this).data('total-students');
                        const totalHasEvaluated = $(this).data('total-has-evaluated');
                        const totalNotEvaluated = $(this).data('total-not-evaluated');
                        const outstanding = $(this).data('outstanding');
                        const good = $(this).data('good');
                        const fair = $(this).data('fair');
                        const average = $(this).data('average');
                        const poor = $(this).data('poor');

                        $('.study_class_name').text(studyClassName);
                        $('.major_name').text(majorName);
                        $('.department_name').text(departmentName);
                        $('.cohort_name').text(cohortName);
                        $('.lecturer_name').text(lecturerName);
                        $('.total_students').text(totalStudents);
                        $('.total_conduct_score').text(totalHasEvaluated + ' / ' + totalStudents);
                        $('.outstanding').text(outstanding);
                        $('.good').text(good);
                        $('.fair').text(fair);
                        $('.average').text(average);
                        $('.poor').text(poor);
                    });
                });
            </script>
    @endpush

