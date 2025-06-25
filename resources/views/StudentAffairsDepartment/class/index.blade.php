@extends('layouts.studentAffairsDepartment')

@section('title', 'Lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
    ['label' => 'Lớp học']
    ]"/>
@endsection

@push('styles')
    <style>
        .stats-card {
            /*background: linear-gradient(135deg, #0d6efd, #0056b3);*/
            /*color: white;*/
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-card .stats-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .action-buttons .btn {
            margin: 0 2px;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .stats-card {
                padding: 1rem;
                margin-bottom: 0.5rem;
            }

            .stats-card .stats-number {
                font-size: 1.5rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .action-buttons .btn {
                margin: 0;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .card-header .btn {
                margin-top: 0.5rem;
                width: 100%;
            }
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 mb-3">
                <div class="stats-card border">
                    <div class="stats-number">{{ $data['studyClasses']['total'] }}</div>
                    <div class="stats-label">Lớp học</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 mb-3">
                <div class="stats-card border">
                    <div class="stats-number">{{ count($data['majors']) }}</div>
                    <div class="stats-label">Ngành học</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 mb-3">
                <div class="stats-card border">
                    <div class="stats-number">{{ $data['totalDepartments'] }}</div>
                    <div class="stats-label">Khoa</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 mb-3">
                <div class="stats-card border">
                    <div class="stats-number">{{ $data['totalStudents'] }}</div>
                    <div class="stats-label">Sinh viên</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('student-affairs-department.class.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Ngành học</label>
                            <select class="form-select" id="major_id_filter" name="major_id">
                                <option value="">Tất cả ngành</option>
                                @forelse($data['majors'] ?? [] as $item)
                                    <option value="{{ $item['id'] }}" {{ $item['id'] == request()->get('major_id') ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                @empty
                                    <option value="" disabled>Không có ngành học</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Niên khóa</label>
                            <select class="form-select" id="cohort_id_filter" name="cohort_id">
                                <option value="">Tất cả khóa</option>
                                @forelse($data['cohorts'] ?? [] as $item)
                                    <option value="{{ $item['id'] }}"  {{ $item['id'] == request()->get('cohort_id') ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                @empty
                                    <option value="" disabled>Không có niên khóa</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tìm kiếm</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" name="search" value="{{ request()->get('search') }}" placeholder="Tìm kiếm lớp học...">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Classes Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách lớp học</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                    <i class="fas fa-plus"></i> Thêm lớp học
                </button>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="classesTable">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="d-none d-md-table-cell">Niên khóa</th>
                            <th>Tên lớp</th>
                            <th class="d-none d-md-table-cell">Ngành học</th>
                            <th class="d-none d-lg-table-cell">Khoa</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($data['studyClasses']['data'] ?? [] as $item)
                            <tr data-id="1">
                                <td>{{ $loop->iteration }}</td>
                                <td class="d-none d-md-table-cell">{{ $item['cohort']['name'] }}</td>
                                <td><strong>{{ $item['name'] }}</strong></td>
                                <td class="d-none d-md-table-cell">{{ $item['major']['name'] }}</td>
                                <td class="d-none d-lg-table-cell">{{ $item['major']['faculty']['department']['name'] }}</td>
                                <td>
                                    <div class="btn-group gap-2">
                                        <button class="btn btn-sm btn-info" data-bs-target="#viewClassModal" data-bs-toggle="modal" title="Xem"
                                            data-id="{{ $item['id'] }}"
                                            data-name="{{ $item['name'] }}"
                                            data-cohort="{{ $item['cohort']['name'] }}"
                                            data-major="{{ $item['major']['name'] }}"
                                            data-faculty="{{ $item['major']['faculty']['name'] }}"
                                            data-department="{{ $item['major']['faculty']['department']['name'] }}"
                                            data-lecturer="{{ $item['lecturer']['user']['name'] ?? 'Không xác định' }}"
                                            data-students="{{ count($item['students']) ?? 0 }}"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" data-bs-target="#editClassModal" data-bs-toggle="modal" title="Sửa"
                                            data-id="{{ $item['id'] }}"
                                            data-name="{{ $item['name'] }}"
                                            data-cohort-id="{{ $item['cohort_id'] }}"
                                            data-major-id="{{ $item['major_id'] }}"
                                            data-lecturer-id="{{ $item['lecturer_id'] }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-target="#deleteModal" data-bs-toggle="modal" title="Xóa"
                                            data-id="{{ $item['id'] }}"
                                            data-current-page="{{ $data['studyClasses']['current_page'] }}"
                                            data-name="{{ $item['name'] }}"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có lớp học nào</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <x-pagination.pagination :paginate="$data['studyClasses']" class="mt-3"/>
            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Thêm lớp học mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <form id="addClassForm" method="POST" action="{{ route('student-affairs-department.class.store') }}">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="className" class="form-label">Tên lớp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="className" name="name" placeholder="VD: 63KTPM2" required>
                                <div class="invalid-feedback">Vui lòng nhập tên lớp.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="cohort_id" class="form-label">Niên khóa <span class="text-danger">*</span></label>
                                <select class="form-select" id="cohort_id" name="cohort_id" required>
                                    <option value="" disabled selected>Chọn niên khóa</option>
                                    @forelse($data['cohorts'] ?? [] as $item)
                                        <option value="{{ $item['id'] ?? '' }}">{{ $item['name'] ?? 'Không xác định' }}</option>
                                    @empty
                                        <option value="">Không có khóa học</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn niên khóa.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="major_id" class="form-label">Ngành học <span class="text-danger">*</span></label>
                                <select class="form-select" id="major_id" name="major_id" required>
                                    <option value="" disabled selected>Chọn ngành học</option>
                                    @forelse($data['majors'] ?? [] as $item)
                                        <option value="{{ $item['id'] ?? '' }}">{{ $item['name'] ?? 'Không xác định' }}</option>
                                    @empty
                                        <option value="">Không có ngành học</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn ngành học.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="lecturer" class="form-label">Giảng viên phụ trách <span class="text-danger">*</span></label>
                                <select class="form-select" id="lecturer" name="lecturer_id" required>
                                    <option value="" disabled selected>Chọn giảng viên</option>
                                    @forelse($data['lecturers'] ?? [] as $item)
                                        @php
                                            $title = $item['title'] ?? '';
                                            $userName = $item['user']['name'] ?? '';
                                            $lecturerId = $item['id'] ?? '';
                                            $renameTitle = [
                                                'Tiến sĩ' => 'TS.',
                                                'Thạc sĩ' => 'ThS.',
                                                'Giáo sư' => 'GS.',
                                                'Phó Giáo sư' => 'PGS.',
                                                'Giảng viên' => 'GV.'
                                            ];
                                            $displayTitle = array_key_exists($title, $renameTitle) ? $renameTitle[$title] : $title;
                                        @endphp
                                        @if($lecturerId && $userName)
                                            <option value="{{ $lecturerId }}">{{ $displayTitle }} {{ $userName }}</option>
                                        @endif
                                    @empty
                                        <option value="">Không có giảng viên</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn giảng viên.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="saveClassButton">
                            <i class="fas fa-save me-1"></i>Lưu lớp học
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Class Modal -->
    <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa lớp học
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <form id="editClassForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editClassName" class="form-label">Tên lớp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editClassName" name="name" placeholder="VD: 63KTPM2" required>
                                <div class="invalid-feedback">Vui lòng nhập tên lớp.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="editCohortId" class="form-label">Niên khóa <span class="text-danger">*</span></label>
                                <select class="form-select" id="editCohortId" name="cohort_id" required>
                                    <option value="" disabled>Chọn niên khóa</option>
                                    @forelse($data['cohorts'] ?? [] as $item)
                                        <option value="{{ $item['id'] ?? '' }}">{{ $item['name'] ?? 'Không xác định' }}</option>
                                    @empty
                                        <option value="">Không có khóa học</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn niên khóa.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="editMajorId" class="form-label">Ngành học <span class="text-danger">*</span></label>
                                <select class="form-select" id="editMajorId" name="major_id" required>
                                    <option value="" disabled>Chọn ngành học</option>
                                    @forelse($data['majors'] ?? [] as $item)
                                        <option value="{{ $item['id'] ?? '' }}">{{ $item['name'] ?? 'Không xác định' }}</option>
                                    @empty
                                        <option value="">Không có ngành học</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn ngành học.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="editLecturer" class="form-label">Giảng viên phụ trách <span class="text-danger">*</span></label>
                                <select class="form-select" id="editLecturer" name="lecturer_id" required>
                                    <option value="" disabled>Chọn giảng viên</option>
                                    @forelse($data['lecturers'] ?? [] as $item)
                                        @php
                                            $title = $item['title'] ?? '';
                                            $userName = $item['user']['name'] ?? '';
                                            $lecturerId = $item['id'] ?? '';
                                            $renameTitle = [
                                                'Tiến sĩ' => 'TS.',
                                                'Thạc sĩ' => 'ThS.',
                                                'Giáo sư' => 'GS.',
                                                'Phó Giáo sư' => 'PGS.',
                                                'Giảng viên' => 'GV.'
                                            ];
                                            $displayTitle = array_key_exists($title, $renameTitle) ? $renameTitle[$title] : $title;
                                        @endphp
                                        @if($lecturerId && $userName)
                                            <option value="{{ $lecturerId }}">{{ $displayTitle }} {{ $userName }}</option>
                                        @endif
                                    @empty
                                        <option value="">Không có giảng viên</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn giảng viên.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="updateClassButton">
                            <i class="fas fa-save me-1"></i>Cập nhật lớp học
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Class Modal -->
    <div class="modal fade" id="viewClassModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>Chi tiết lớp học
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewClassContent">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Tên lớp:</strong> <span id="viewClassName"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Khóa:</strong> <span id="viewCohort"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngành học:</strong> <span id="viewMajor"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Bộ môn:</strong> <span id="viewFaculty"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Bộ môn:</strong> <span id="viewDepartment"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Giảng viên phụ trách:</strong> <span id="viewLecturer"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Số sinh viên:</strong> <span id="viewStudents"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <h4>Bạn có chắc chắn muốn xóa lớp học này?</h4>
                    <p class="text-muted">Lớp: <strong id="deleteClassName"></strong></p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hành động này không thể hoàn tác!
                    </div>
                </div>
                <form method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteClassId" name="id">
                    <input type="hidden" id="deleteCurrentPage" name="current_page">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger" id="confirmDelete">
                            <i class="fas fa-trash me-1"></i>Xóa lớp học
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
            const addClassModal = $('#addClassModal');
            const addClassForm = $('#addClassForm');

            addClassModal.on('show.bs.modal', function () {
                addClassForm[0].reset();
                addClassForm.find('.is-invalid').removeClass('is-invalid');
                addClassForm.find('select').trigger('change');
            });

            $('#saveClassButton').on('click', function (e) {
                e.preventDefault();
                if (addClassForm[0].checkValidity()) {
                    addClassForm.submit();
                } else {
                    addClassForm.addClass('was-validated');
                }
            });

            $('#editClassModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const classId = button.data('id');
                const className = button.data('name');
                const cohortId = button.data('cohort-id');
                const majorId = button.data('major-id');
                const lecturerId = button.data('lecturer-id');

                $('#editClassForm').attr('action', `/student-affairs-department/class/${classId}`);
                $('#editClassName').val(className);
                $('#editCohortId').val(cohortId);
                $('#editMajorId').val(majorId);
                $('#editLecturer').val(lecturerId);
            });

            $('#deleteModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const classId = button.data('id');
                const className = button.data('name');
                const currentPage = button.data('current-page');

                $('#deleteClassName').text(className);
                $('#deleteClassId').val(classId);
                $('#deleteCurrentPage').val(currentPage);
                $('#deleteModal form').attr('action', `/student-affairs-department/class/${classId}`);
            });

            $('#viewClassModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                $('#viewClassName').text(button.data('name'));
                $('#viewCohort').text(button.data('cohort'));
                $('#viewMajor').text(button.data('major'));
                $('#viewFaculty').text(button.data('faculty'));
                $('#viewDepartment').text(button.data('department'));
                $('#viewLecturer').text(button.data('lecturer'));
                $('#viewStudents').text(button.data('students'));
            });
        });
    </script>
@endpush
