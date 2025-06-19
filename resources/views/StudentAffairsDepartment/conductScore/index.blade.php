@extends('layouts.studentAffairsDepartment')

@section('title', 'Điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Điểm rèn luyện']]" />
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
                    <h4 class="flex-grow-1 mb-0 d-none d-md-block">Danh sách điểm rèn luyện</h4>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('student-affairs-department.conduct-score.index') }}" class="position-relative">
                            <div class="input-group me-2" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Tìm kiếm..." name="search" value="{{ request('search') }}"
                                       id="search">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <button class="btn btn-primary px-3" data-bs-target="#confirmCreateModal" data-bs-toggle="modal">
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
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 80px;">STT</th>
                                    <th scope="col">Học kỳ</th>
                                    <th scope="col">Thời gian bắt đầu</th>
                                    <th scope="col">Thời gian kết thúc</th>
                                    <th scope="col" class="text-center" style="width: 200px;">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($data['ConductEvaluationPeriods']['data'] as $index => $conductEvaluationPeriod)
                                    <tr>
                                        <td class="text-center fw-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="fw-semibold">
                                            {{ $conductEvaluationPeriod['semester']['name'] }} -
                                            {{ $conductEvaluationPeriod['semester']['school_year'] }}
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            {{ \Carbon\Carbon::parse($conductEvaluationPeriod['open_date'])->format('H:i d/m/Y') }}
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                            {{ \Carbon\Carbon::parse($conductEvaluationPeriod['end_date'])->format('H:i d/m/Y') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('student-affairs-department.conduct-score.infoConductScore', $conductEvaluationPeriod['id']) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-warning btn-sm btn-edit"
                                                        data-id="{{ $conductEvaluationPeriod['id'] }}"
                                                        data-semester="{{ $conductEvaluationPeriod['semester']['name'] }} - {{ $conductEvaluationPeriod['semester']['school_year'] }}"
                                                        data-open-date="{{ \Carbon\Carbon::parse($conductEvaluationPeriod['open_date'])->format('Y-m-d\TH:i') }}"
                                                        data-end-date="{{ \Carbon\Carbon::parse($conductEvaluationPeriod['end_date'])->format('Y-m-d\TH:i') }}"
                                                        data-semester-id="{{ $conductEvaluationPeriod['semester']['id'] }}"
                                                        data-current-page="{{ $data['ConductEvaluationPeriods']['current_page'] }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal"
                                                        title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-danger btn-sm btn-delete"
                                                        data-id="{{ $conductEvaluationPeriod['id'] }}"
                                                        data-semester="{{ $conductEvaluationPeriod['semester']['name'] }} - {{ $conductEvaluationPeriod['semester']['school_year'] }}"
                                                        data-current-page="{{ $data['ConductEvaluationPeriods']['current_page'] }}"
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                            Không có dữ liệu
                                        </td>
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
        <div class="row mt-4">
            <div class="col-12">
                <x-pagination.pagination :paginate="$data['ConductEvaluationPeriods']" />
            </div>
        </div>
    </div>

    <!-- Modal Tạo mới -->
    <div class="modal fade auto-reset-modal" id="confirmCreateModal" tabindex="-1" aria-labelledby="confirmCreateModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createModalLabel">Đăng ký đánh giá điểm rèn luyện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createform" method="POST" action="{{ route('student-affairs-department.conduct-score.create') }}">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="semester_id" class="form-label">Học kỳ <span class="text-danger">*</span></label>
                            <select class="form-select" id="semester_id" name="semester_id" required>
                                <option value="">Chọn học kỳ</option>
                                @if (!empty($data['semesters']))
                                    @foreach ($data['semesters'] as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }} - {{ $semester->school_year }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="semester_id_error" class="text-danger text-danger-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="open_date" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="open_date" name="open_date" required
                                   min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                            <div id="open_date_error" class="text-danger text-danger-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required
                                   min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                            <div id="end_date_error" class="text-danger text-danger-error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btnReset">
                        <i class="fas fa-redo me-2"></i>Đặt lại
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Quay lại
                    </button>
                    <button type="button" class="btn btn-primary btn-create-submit">
                        <i class="fas fa-save me-2"></i>Đăng ký
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chỉnh sửa -->
    <div class="modal fade auto-reset-modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editModalLabel">Chỉnh sửa đánh giá điểm rèn luyện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editform" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="current_page" class="current_page">
                        <div class="mb-3">
                            <label for="semester_id_edit" class="form-label">Học kỳ <span class="text-danger">*</span></label>
                            <select class="form-select" id="semester_id_edit" name="semester_id" required>
                                <option value="">Chọn học kỳ</option>
                                @if (!empty($data['semesters']))
                                    @foreach ($data['semesters'] as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }} - {{ $semester->school_year }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="semester_id_error_edit" class="text-danger text-danger-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="open_date_edit" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="open_date_edit" name="open_date" required>
                            <div id="open_date_error_edit" class="text-danger text-danger-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="end_date_edit" class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="end_date_edit" name="end_date" required>
                            <div id="end_date_error_edit" class="text-danger text-danger-error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btnResetEdit">
                        <i class="fas fa-redo me-2"></i>Đặt lại
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Quay lại
                    </button>
                    <button type="button" class="btn btn-warning btn-edit-submit">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Bạn có chắc chắn muốn xóa kỳ đánh giá điểm rèn luyện:</p>
                    <div class="alert alert-warning">
                        <strong id="deleteSemesterName"></strong>
                    </div>
                    <p class="text-muted small">Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let deleteId = null;

            // Reset form when modal is closed
            $('.auto-reset-modal').on('hidden.bs.modal', function() {
                $('.text-danger-error').text('');
                $(this).find('form')[0].reset();
            });

            // Reset create form
            $('.btnReset').click(function() {
                $('.text-danger-error').text('');
                $('#createform')[0].reset();
            });

            // Reset edit form
            $('.btnResetEdit').click(function() {
                $('.text-danger-error').text('');
                $('#editform')[0].reset();
            });

            // Handle edit button click
            $('.btn-edit').click(function() {
                const id = $(this).data('id');
                const semesterId = $(this).data('semester-id');
                const openDate = $(this).data('open-date');
                const endDate = $(this).data('end-date');
                const currentPage = $(this).data('current-page');

                $('#semester_id_edit').val(semesterId);
                $('#open_date_edit').val(openDate);
                $('#end_date_edit').val(endDate);
                $('.current_page').val(currentPage);

                // Set form action URL for edit
                $('#editform').attr('action', `/student-affairs-department/conduct-score/${id}`);
            });

            // Handle delete button click
            $('.btn-delete').click(function() {
                deleteId = $(this).data('id');
                const semesterName = $(this).data('semester');

                $('#deleteSemesterName').text(semesterName);
                $('#deleteModal').modal('show');
            });

            // Confirm delete
            $('#confirmDeleteBtn').click(function() {
                if (deleteId) {
                    // Create and submit delete form
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': `/student-affairs-department/conduct-score/${deleteId}`
                    });

                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': $('meta[name="csrf-token"]').attr('content')
                    }));

                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));

                    $('body').append(form);
                    form.submit();
                }
            });

            // Create form validation and submit
            $('.btn-create-submit').on('click', function(e) {
                e.preventDefault();

                let openDate = $('#open_date').val();
                let endDate = $('#end_date').val();
                let semesterId = $('#semester_id').val();

                // Clear previous errors
                $('.text-danger-error').text('');

                let hasError = false;

                if (!semesterId) {
                    $('#semester_id_error').text('Vui lòng chọn học kỳ');
                    hasError = true;
                }

                if (!openDate) {
                    $('#open_date_error').text('Vui lòng chọn thời gian bắt đầu');
                    hasError = true;
                }

                if (!endDate) {
                    $('#end_date_error').text('Vui lòng chọn thời gian kết thúc');
                    hasError = true;
                }

                if (openDate && endDate && new Date(openDate) >= new Date(endDate)) {
                    $('#end_date_error').text('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
                    hasError = true;
                }

                if (!hasError) {
                    $('#createform').submit();
                }
            });

            // Edit form validation and submit
            $('.btn-edit-submit').on('click', function(e) {
                e.preventDefault();

                let openDate = $('#open_date_edit').val();
                let endDate = $('#end_date_edit').val();
                let semesterId = $('#semester_id_edit').val();

                // Clear previous errors
                $('.text-danger-error').text('');

                let hasError = false;

                if (!semesterId) {
                    $('#semester_id_error_edit').text('Vui lòng chọn học kỳ');
                    hasError = true;
                }

                if (!openDate) {
                    $('#open_date_error_edit').text('Vui lòng chọn thời gian bắt đầu');
                    hasError = true;
                }

                if (!endDate) {
                    $('#end_date_error_edit').text('Vui lòng chọn thời gian kết thúc');
                    hasError = true;
                }

                if (openDate && endDate && new Date(openDate) >= new Date(endDate)) {
                    $('#end_date_error_edit').text('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
                    hasError = true;
                }

                if (!hasError) {
                    $('#editform').submit();
                }
            });

            // Add hover effects to table rows
            $('tbody tr').hover(
                function() {
                    $(this).addClass('table-active');
                },
                function() {
                    $(this).removeClass('table-active');
                }
            );
        });
    </script>
@endpush


