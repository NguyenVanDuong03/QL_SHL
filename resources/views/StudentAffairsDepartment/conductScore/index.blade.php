@extends('layouts.studentAffairsDepartment')

@section('title', 'Điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Điểm rèn luyện']]"/>
@endsection

@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-role-student {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .badge-role-teacher {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .badge-role-office {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .phase-card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
        }

        .phase-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .phase-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .search-container {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-input {
            padding-left: 2.5rem;
        }

        .btn-group-actions {
            display: flex;
            gap: 0.25rem;
        }

        @media (max-width: 768px) {
            .btn-group-actions {
                flex-direction: column;
            }

            .table-responsive {
                font-size: 0.875rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid mt-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h4 class="mb-0">Danh sách đợt chấm điểm rèn luyện</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <form method="GET" action="{{ route('student-affairs-department.conduct-score.index') }}"
                              class="search-container">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm..."
                                       id="searchInput" style="width: 250px;">
                                <button class="btn btn-outline-secondary btn-search-semester" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fas fa-plus me-2"></i>Tạo mới
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="evaluationTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 80px;">STT</th>
                                    <th scope="col">Đợt đánh giá</th>
                                    <th scope="col">Học kỳ</th>
                                    <th scope="col">Ngày tạo</th>
                                    <th scope="col" class="">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @forelse($data['ConductEvaluationPeriods']['data'] ?? [] as $item)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $item['name'] }}</div>
                                        </td>
                                        <td>
                                        <span class="">
                                            {{ $item['semester']['name'] }} <br>
                                            <span class="text-muted">{{ $item['semester']['school_year'] }}</span>
                                        </span>
                                        </td>
                                        <td>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group-actions">
                                                <button class="btn btn-primary btn-sm"
                                                        title="thông tin"
                                                        data-bs-target="#detailModal"
                                                        data-bs-toggle="modal"
                                                        data-name="{{ $item['name'] }}"
                                                        data-semester="{{ $item['semester']['name'] }} - {{ $item['semester']['school_year'] }}"
                                                        data-created="{{ \Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y') }}"
                                                        data-open-date-1="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][0]['open_date'])->format('H:i d/m/Y') ?? '#' }}"
                                                        data-end-date-1="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][0]['end_date'])->format('H:i d/m/Y') ?? '#' }}"
                                                        data-open-date-2="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][1]['open_date'])->format('H:i d/m/Y') ?? '#' }}"
                                                        data-end-date-2="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][1]['end_date'])->format('H:i d/m/Y') ?? '#' }}"
                                                        data-open-date-3="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][2]['open_date'])->format('H:i d/m/Y') ?? '#' }}"
                                                        data-end-date-3="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][2]['end_date'])->format('H:i d/m/Y') ?? '#' }}"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('student-affairs-department.conduct-score.infoConductScore', ['conduct_evaluation_period_id' => $item['id']]) }}" class="btn btn-info btn-sm" title="Chi tiết">
                                                        <i class="fas fa-info-circle"></i>
                                                </a>
                                                <button class="btn btn-warning btn-sm"
                                                        title="Chỉnh sửa"
                                                        data-bs-target="#editModal"
                                                        data-bs-toggle="modal"
                                                        data-name="{{ $item['name'] }}"
                                                        data-semester-id="{{ $item['semester']['id'] }}"
                                                        data-id="{{ $item['id'] }}"
                                                        data-current-page="{{ $data['ConductEvaluationPeriods']['current_page'] }}"
                                                        data-open-date-1="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][0]['open_date'])->format('Y-m-d\TH:i') ?? '' }}"
                                                        data-end-date-1="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][0]['end_date'])->format('Y-m-d\TH:i') ?? '' }}"
                                                        data-open-date-2="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][1]['open_date'])->format('Y-m-d\TH:i') ?? '' }}"
                                                        data-end-date-2="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][1]['end_date'])->format('Y-m-d\TH:i') ?? '' }}"
                                                        data-open-date-3="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][2]['open_date'])->format('Y-m-d\TH:i') ?? '' }}"
                                                        data-end-date-3="{{ \Carbon\Carbon::parse($item['conduct_evaluation_phases'][2]['end_date'])->format('Y-m-d\TH:i') ?? '' }}"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm"
                                                        title="Xóa"
                                                        data-bs-target="#deleteModal"
                                                        data-bs-toggle="modal"
                                                        data-id="{{ $item['id'] }}"
                                                        data-name="{{ $item['name'] }}"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                            Không có dữ liệu
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <x-pagination.pagination :paginate="$data['ConductEvaluationPeriods']" class="mt-3"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createModalLabel">Tạo đợt chấm điểm rèn luyện mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createForm" method="POST"
                          action="{{ route('student-affairs-department.conduct-score.create') }}">
                        @csrf
                        @method('POST')
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="create_name" class="form-label">
                                    Tên đợt đánh giá <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="create_name" name="name"
                                       placeholder="Ví dụ: Đợt 1 HK1 2025" required>
                                <div class="error-message" id="create_name_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="create_semester" class="form-label">
                                    Học kỳ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="create_semester" name="semester_id" required>
                                    <option value="">Chọn học kỳ</option>
                                    @forelse($data['semesters'] ?? [] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                            - {{ $item['school_year'] }}</option>
                                    @empty
                                        <option value="" disabled selected>Không có học kỳ nào</option>
                                    @endforelse
                                </select>
                                <div class="error-message" id="create_semester_error"></div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Giai đoạn chấm điểm theo vai trò</h5>

                        <!-- Student Phase -->
                        <div class="phase-card">
                            <div class="phase-header">
                                <i class="fas fa-graduation-cap phase-icon text-primary"></i>
                                <h6 class="mb-0 fw-semibold">Sinh viên</h6>
                            </div>
                            <div class="row">
                                <input type="hidden" name="phases[0][role]" value="0">
                                <div class="col-md-6">
                                    <label for="create_student_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_student_start" name="phases[0][open_date]"
                                           data-role="0" data-type="start"
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" required>
                                    <div class="error-message" id="create_student_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_student_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_student_end" name="phases[0][end_date]"
                                           data-role="0" data-type="end"
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" required>
                                    <div class="error-message" id="create_student_end_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Phase -->
                        <div class="phase-card">
                            <div class="phase-header">
                                <i class="fas fa-chalkboard-teacher phase-icon text-success"></i>
                                <h6 class="mb-0 fw-semibold">Giáo viên</h6>
                            </div>
                            <div class="row">
                                <input type="hidden" name="phases[1][role]" value="1">
                                <div class="col-md-6">
                                    <label for="create_teacher_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_teacher_start" name="phases[1][open_date]"
                                           data-role="1" data-type="start"
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" required>
                                    <div class="error-message" id="create_teacher_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_teacher_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_teacher_end" name="phases[1][end_date]"
                                           data-role="1" data-type="end"
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" required>
                                    <div class="error-message" id="create_teacher_end_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Office Phase -->
                        <div class="phase-card">
                            <div class="phase-header">
                                <i class="fas fa-building phase-icon text-secondary"></i>
                                <h6 class="mb-0 fw-semibold">Văn phòng khoa</h6>
                            </div>
                            <div class="row">
                                <input type="hidden" name="phases[2][role]" value="2">
                                <div class="col-md-6">
                                    <label for="create_office_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_office_start" name="phases[2][open_date]"
                                           data-role="2" data-type="start"
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" required>
                                    <div class="error-message" id="create_office_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_office_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_office_end" name="phases[2][end_date]"
                                           data-role="2" data-type="end"
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" required>
                                    <div class="error-message" id="create_office_end_error"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="resetCreateForm">
                        <i class="fas fa-redo me-2"></i>Đặt lại
                    </button>
                    <button type="button" class="btn btn-primary" id="submitCreate">
                        <i class="fas fa-save me-2"></i>Tạo mới
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editModalLabel">Chỉnh sửa đợt chấm điểm rèn luyện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="current_page" class="currentPage">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="edit_name" class="form-label">
                                    Tên đợt đánh giá <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                       placeholder="Ví dụ: Đợt 1 HK1 2025" required>
                                <div class="error-message" id="edit_name_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_semester" class="form-label">
                                    Học kỳ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit_semester" name="semester_id" required>
                                    <option value="">Chọn học kỳ</option>
                                    @forelse($data['semesters'] ?? [] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                            - {{ $item['school_year'] }}</option>
                                    @empty
                                        <option value="" disabled selected>Không có học kỳ nào</option>
                                    @endforelse
                                </select>
                                <div class="error-message" id="edit_semester_error"></div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Giai đoạn chấm điểm theo vai trò</h5>

                        <!-- Student Phase -->
                        <div class="phase-card">
                            <div class="phase-header">
                                <i class="fas fa-graduation-cap phase-icon text-primary"></i>
                                <h6 class="mb-0 fw-semibold">Sinh viên</h6>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_student_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime-edit"
                                           id="edit_student_start" name="phases[0][open_date]"
                                           data-role="0" data-type="start" required>
                                    <div class="error-message" id="edit_student_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_student_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime-edit"
                                           id="edit_student_end" name="phases[0][end_date]"
                                           data-role="0" data-type="end" required>
                                    <div class="error-message" id="edit_student_end_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Phase -->
                        <div class="phase-card">
                            <div class="phase-header">
                                <i class="fas fa-chalkboard-teacher phase-icon text-success"></i>
                                <h6 class="mb-0 fw-semibold">Giáo viên</h6>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_teacher_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime-edit"
                                           id="edit_teacher_start" name="phases[1][open_date]"
                                           data-role="1" data-type="start" required>
                                    <div class="error-message" id="edit_teacher_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_teacher_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime-edit"
                                           id="edit_teacher_end" name="phases[1][end_date]"
                                           data-role="1" data-type="end" required>
                                    <div class="error-message" id="edit_teacher_end_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Office Phase -->
                        <div class="phase-card">
                            <div class="phase-header">
                                <i class="fas fa-building phase-icon text-secondary"></i>
                                <h6 class="mb-0 fw-semibold">Văn phòng khoa</h6>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_office_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime-edit"
                                           id="edit_office_start" name="phases[2][open_date]"
                                           data-role="2" data-type="start" required>
                                    <div class="error-message" id="edit_office_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_office_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime-edit"
                                           id="edit_office_end" name="phases[2][end_date]"
                                           data-role="2" data-type="end" required>
                                    <div class="error-message" id="edit_office_end_error"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="resetEditForm">
                        <i class="fas fa-redo me-2"></i>Đặt lại
                    </button>
                    <button type="button" class="btn btn-warning" id="submitEdit">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="detailModalLabel">Chi tiết đợt chấm điểm rèn luyện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tên đợt đánh giá</label>
                            <p class="fw-semibold detail_conduct_name"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Học kỳ</label>
                            <p class="fw-semibold detail_semester_name"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Ngày tạo</label>
                            <p class="fw-semibold detail_created"></p>
                        </div>
                    </div>
                    <hr>
                    <h5 class="mb-3">Giai đoạn chấm điểm</h5>
                    <div class="phase-card mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Sinh viên</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        <span class="detail_open_date_1"></span> - <span
                                            class="detail_end_date_1"></span>
                                    </small>
                                </div>
                            </div>
                            <span class="badge badge-role-student">Sinh viên</span>
                        </div>
                    </div>
                    <div class="phase-card mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Giáo viên</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        <span class="detail_open_date_2"></span> - <span
                                            class="detail_end_date_2"></span>
                                    </small>
                                </div>
                            </div>
                            <span class="badge badge-role-teacher">Giáo viên</span>
                        </div>
                    </div>
                    <div class="phase-card mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building text-secondary me-2"></i>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Văn phòng khoa</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        <span class="detail_open_date_3"></span> - <span
                                            class="detail_end_date_3"></span>
                                    </small>
                                </div>
                            </div>
                            <span class="badge badge-role-office">Văn phòng khoa</span>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Bạn có chắc chắn muốn xóa đợt chấm điểm rèn luyện:</p>
                    <div class="alert alert-warning">
                        <strong id="deleteItemName"></strong>
                    </div>
                    <p class="text-muted small">Hành động này không thể hoàn tác!</p>
                </div>
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="deleteId">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-danger" id="confirmDelete">
                            <i class="fas fa-trash me-2"></i>Xóa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#detailModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const name = button.data('name');
                const semester = button.data('semester');
                const created = button.data('created');
                const openDate1 = button.data('open-date-1');
                const endDate1 = button.data('end-date-1');
                const openDate2 = button.data('open-date-2');
                const endDate2 = button.data('end-date-2');
                const openDate3 = button.data('open-date-3');
                const endDate3 = button.data('end-date-3');

                $('#detailModalLabel').text(`Chi tiết đợt chấm điểm rèn luyện: ${name}`);
                $('.detail_conduct_name').text(name);
                $('.detail_semester_name').text(semester);
                $('.detail_created').text(created);
                $('.detail_open_date_1').text(openDate1);
                $('.detail_end_date_1').text(endDate1);
                $('.detail_open_date_2').text(openDate2);
                $('.detail_end_date_2').text(endDate2);
                $('.detail_open_date_3').text(openDate3);
                $('.detail_end_date_3').text(endDate3);
            });

            const roleNames = {
                0: "Sinh viên",
                1: "Giáo viên",
                2: "Văn phòng khoa"
            };

            $('#submitCreate').on('click', function () {
                if (!validateForm('create'))
                    return;

                $('#createForm').submit();
            });

            $('#submitEdit').on('click', function () {
                if (!validateForm('edit'))
                    return;

                $('#editForm').submit();
            });

            $('#editModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const name = button.data('name');
                const semesterId = button.data('semester-id');
                const id = button.data('id');
                const openDate1 = button.data('open-date-1');
                const endDate1 = button.data('end-date-1');
                const openDate2 = button.data('open-date-2');
                const endDate2 = button.data('end-date-2');
                const openDate3 = button.data('open-date-3');
                const endDate3 = button.data('end-date-3');
                const currentPage = button.data('current-page');

                $('#editModalLabel').text(`Chỉnh sửa đợt chấm điểm rèn luyện: ${name}`);
                $('#edit_name').val(name);
                $('#edit_semester').val(semesterId);
                $('#editForm').attr('action', `/student-affairs-department/conduct-score/${id}`);
                $('#edit_student_start').val(openDate1);
                $('#edit_student_end').val(endDate1);
                $('#edit_teacher_start').val(openDate2);
                $('#edit_teacher_end').val(endDate2);
                $('#edit_office_start').val(openDate3);
                $('#edit_office_end').val(endDate3);
                $('.currentPage').val(currentPage);

                currentEditId = id;
            });

            $('#deleteModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const name = button.data('name');

                $('#deleteForm').attr('action', `/student-affairs-department/conduct-score/${id}`);
                $('#deleteItemName').text(`Xác nhận xóa đợt chấm điểm rèn luyện: ${name}`);
            });

            $('#resetCreateForm').click(() => resetForm('create'));
            $('#resetEditForm').click(() => resetForm('edit'));

            // Modal events
            $('#createModal').on('hidden.bs.modal', () => resetForm('create'));
            $('#editModal').on('hidden.bs.modal', () => resetForm('edit'));

            // Real-time validation
            $('.phase-datetime').on('change', () => validatePhases('create'));
            $('.phase-datetime-edit').on('change', () => validatePhases('edit'));

            function validatePhases(formType) {
                const prefix = formType === 'create' ? 'create' : 'edit';
                clearErrors(formType);

                const phases = [];
                for (let i = 0; i < 3; i++) {
                    const startInput = $(`#${prefix}_${getRoleName(i)}_start`);
                    const endInput = $(`#${prefix}_${getRoleName(i)}_end`);

                    phases.push({
                        role: i,
                        start: startInput.val(),
                        end: endInput.val(),
                        startElement: startInput,
                        endElement: endInput
                    });
                }

                let hasError = false;

                phases.forEach((phase, index) => {
                    const roleName = getRoleName(phase.role);

                    if (phase.start && phase.end) {
                        const startDate = new Date(phase.start);
                        const endDate = new Date(phase.end);

                        if (startDate >= endDate) {
                            showError(`${prefix}_${roleName}_end_error`, 'Thời gian kết thúc phải sau thời gian bắt đầu');
                            hasError = true;
                        }
                    }
                });

                for (let i = 0; i < phases.length; i++) {
                    for (let j = i + 1; j < phases.length; j++) {
                        const phase1 = phases[i];
                        const phase2 = phases[j];

                        if (phase1.start && phase1.end && phase2.start && phase2.end) {
                            const start1 = new Date(phase1.start);
                            const end1 = new Date(phase1.end);
                            const start2 = new Date(phase2.start);
                            const end2 = new Date(phase2.end);

                            // Check if periods overlap
                            if (start1 <= end2 && end1 >= start2) {
                                const role1Name = roleNames[phase1.role];
                                const role2Name = roleNames[phase2.role];
                                const errorMsg = `Thời gian bị trùng với giai đoạn ${role1Name}`;

                                showError(`${prefix}_${getRoleName(phase2.role)}_start_error`, errorMsg);
                                hasError = true;
                            }
                        }
                    }
                }

                return !hasError;
            }

            function getRoleName(role) {
                const roleMap = {0: 'student', 1: 'teacher', 2: 'office'};
                return roleMap[role];
            }

            function validateForm(formType) {
                const prefix = formType === 'create' ? 'create' : 'edit';
                clearErrors(formType);

                let hasError = false;

                const name = $(`#${prefix}_name`).val().trim();
                if (!name) {
                    showError(`${prefix}_name_error`, 'Vui lòng nhập tên đợt đánh giá');
                    hasError = true;
                }

                const semesterId = $(`#${prefix}_semester`).val();
                if (!semesterId) {
                    showError(`${prefix}_semester_error`, 'Vui lòng chọn học kỳ');
                    hasError = true;
                }

                for (let i = 0; i < 3; i++) {
                    const roleName = getRoleName(i);
                    const startVal = $(`#${prefix}_${roleName}_start`).val();
                    const endVal = $(`#${prefix}_${roleName}_end`).val();

                    if (!startVal) {
                        showError(`${prefix}_${roleName}_start_error`, `Vui lòng chọn thời gian bắt đầu cho ${roleNames[i]}`);
                        hasError = true;
                    }

                    if (!endVal) {
                        showError(`${prefix}_${roleName}_end_error`, `Vui lòng chọn thời gian kết thúc cho ${roleNames[i]}`);
                        hasError = true;
                    }
                }

                if (!hasError) {
                    hasError = !validatePhases(formType);
                }

                return !hasError;
            }

            function resetForm(formType) {
                const prefix = formType === 'create' ? 'create' : 'edit';
                $(`#${prefix}Form`)[0].reset();
                clearErrors(formType);
                if (formType === 'edit') {
                    currentEditId = null;
                }
            }

            function clearErrors(formType) {
                const prefix = formType === 'create' ? 'create' : 'edit';
                $(`.error-message[id^="${prefix}_"]`).text('');
            }

            function showError(elementId, message) {
                $(`#${elementId}`).text(message);
            }

            function formatDateTime(dateTime) {
                return new Date(dateTime).toLocaleString('vi-VN', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        });
    </script>
@endpush


