@extends('layouts.studentAffairsDepartment')

@section('title', 'Quản lý cảnh báo học vụ')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Quản lý cảnh báo học vụ']]" />
@endsection

@section('main')
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Quản lý cảnh báo học vụ</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> Thêm mới
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                        <tr>
                            <th class="text-center d-none d-md-table-cell">ID</th>
                            <th>Sinh viên</th>
                            <th class="text-center">Học kỳ</th>
                            <th class="text-center">GPA (10)</th>
                            <th class="text-center">GPA (4)</th>
                            <th class="text-center">Mức cảnh báo</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>
                        <tbody id="warningsTable">
                        <!-- Data will be populated by jQuery -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade auto-reset-modal" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="modal-title fw-bold" id="createModalLabel">Xét duyệt sinh hoạt lớp cố định</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                                            <form id="createForm">
                                                <div class="row">
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <label for="student_id" class="form-label">Sinh viên</label>
                                                        <select class="form-select" id="student_id" name="student_id" required>
                                                            <option value="">Chọn sinh viên</option>
                                                            <!-- Populated by jQuery -->
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <label for="semester_id" class="form-label">Học kỳ</label>
                                                        <select class="form-select" id="semester_id" name="semester_id" required>
                                                            <option value="">Chọn học kỳ</option>
                                                            <!-- Populated by jQuery -->
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <label for="warning_date" class="form-label">Ngày cảnh báo</label>
                                                        <input type="date" class="form-control" id="warning_date" name="warning_date">
                                                    </div>
                                                    <div class="col-4 mb-3">
                                                        <label for="credits" class="form-label">Số tín chỉ</label>
                                                        <input type="number" class="form-control" id="credits" name="credits" required min="0">
                                                    </div>
                                                    <div class="col-4 mb-3">
                                                        <label for="gpa_10" class="form-label">GPA (thang 10)</label>
                                                        <input type="number" step="0.01" class="form-control" id="gpa_10" name="gpa_10" required min="0" max="10">
                                                    </div>
                                                    <div class="col-4 mb-3">
                                                        <label for="gpa_4" class="form-label">GPA (thang 4)</label>
                                                        <input type="number" step="0.01" class="form-control" id="gpa_4" name="gpa_4" required min="0" max="4">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label for="academic_status" class="form-label">Mức xử lý học vụ</label>
                                                        <input type="text" class="form-control" id="academic_status" name="academic_status" required>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label for="reason" class="form-label">Lý do</label>
                                                        <textarea class="form-control" id="reason" name="reason" rows="4"></textarea>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label for="note" class="form-label">Ghi chú</label>
                                                        <textarea class="form-control" id="note" name="note" rows="4"></textarea>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center gap-3 mt-4">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                                            style="width: 120px;">Quay lại
                                                    </button>
                                                    <button type="submit" class="btn btn-primary btn-confirm-form"
                                                            style="width: 120px;">Tạo cảnh báo
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editModalLabel">Sửa cảnh báo học vụ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="edit_student_id" class="form-label">Sinh viên</label>
                                <select class="form-select" id="edit_student_id" name="student_id" required>
                                    <option value="">Chọn sinh viên</option>
                                    <!-- Populated by jQuery -->
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="edit_semester_id" class="form-label">Học kỳ</label>
                                <select class="form-select" id="edit_semester_id" name="semester_id" required>
                                    <option value="">Chọn học kỳ</option>
                                    <!-- Populated by jQuery -->
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="edit_warning_date" class="form-label">Ngày cảnh báo</label>
                                <input type="date" class="form-control" id="edit_warning_date" name="warning_date">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="edit_credits" class="form-label">Số tín chỉ</label>
                                <input type="number" class="form-control" id="edit_credits" name="credits" required min="0">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="edit_gpa_10" class="form-label">GPA (thang 10)</label>
                                <input type="number" step="0.01" class="form-control" id="edit_gpa_10" name="gpa_10" required min="0" max="10">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="edit_gpa_4" class="form-label">GPA (thang 4)</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_gpa_4" name="gpa_4" required min="0" max="4">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_academic_status" class="form-label">Mức xử lý học vụ</label>
                                <input type="text" class="form-control" id="edit_academic_status" name="academic_status" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_reason" class="form-label">Lý do</label>
                                <textarea class="form-control" id="edit_reason" name="reason" rows="4">
                        </textarea>
                                <div class="col-12 mb-3">
                                    <label for="edit_note" class="form-label">Ghi chú</label>
                                    <textarea class="form-control" id="edit_note" name="note" rows="4"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal modal-dialog">
        <div class="modal-content">
            <div class="modal modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa cảnh báo học vụ này không?</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
    </div>

    <style>
        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 95vw;
            }
        }
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: hidden;
            }
        }
        .table {
            font-size: 14px;
        }
        .table td-sm {
            padding: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
        }
    </style>

        <script>
        $(document).ready(function() {
        // Load students and semesters
        function loadStudents(selectId) {
            $.ajax({
            url: '/admin/academic-warnings/students',
            method: 'GET',
            success: function(data) {
            let html = '<option value="">Chọn sinh viên</option>';
        data.forEach(student => {
            html += `<option value="${student.id}">${student.student_code} - ${student.name}</option>`;
        });
            $(html).appendTo(selectId);
        }
        });
        }

            function loadSemesters(selectId) {
            $.ajax({
            url: '/admin/academic-warnings/semesters',
            method: 'GET',
            success: function(data) {
            let html = '<option value="">Chọn học kỳ</option>';
        data.forEach(semester => {
            html += `<option value="${semester.id}">${semester.semester_name} (${semester.semester_year})</option>`;
        });
            $(selectId).append(html);
        }
        });
        }

        // Load warnings table
        function loadWarnings() {
            $.ajax({
            url: '/admin/warnings',
            method: 'GET',
            success: function(data) {
            let html = '';
        data.forEach(warning => {
            html += `
            <tr>
            <td class="text-center d-none d-md-table-cell">${warning.id}</td>
            <td>${warning.student.student_code} - ${student.name}</td>
            <td class="text-center">${warning.semester.semester_name} (${semester.semester_year})</td>
        <td class="text-center">${warning.gpa_10.toFixed(2)}</td>
        <td class="text-center">${warning.gpa_4.toFixed(2)}</td>
        <td class="text-center">${warning.academic_status}</td>
        <td class="text-center">
        <button class="btn btn-sm btn-warning me-1 mb-1 mb-md-0 edit-btn" data-id="${warning.id}"><i class="fas fa-edit"></i></button>
        <td class="text-center">
        <button class="btn btn btn-sm btn-danger delete-btn" data-id="${warning.id}"><i class="fas fa-trash"></i></button>
        </td>
        </td>
        </tr>
        `;
        });
            $('#warningsTable').html(html);
        }
        });
        }

        // Load form data
        loadStudents('#student_id');
        loadStudents('#edit_student_id');
        loadSemesters('#semester_id');
        loadSemesters('#edit_semester_id');
        loadWarnings();

        // Create form submit
        $('#createForm').submit(function(e) {
        e.preventDefault();
            $.ajax({
            url: '/admin/academic-warnings',
            method: 'POST',
            data: $(this).serialize(),
        success: function(response) {
            $('#createModal').modal('hide');
        loadWarnings();
            $('#createForm')[0].reset();
        alert('Tạo cảnh báo thành công!');
        },
        error: function(xhr) {
        alert('Lỗi: ' + xhr.responseJSON.message);
        }
        });
        });

        // Edit button click
        $(document).on('click', function() {
            let id = $(this).data('id');
            $.ajax({
            url: `/admin/academic-warnings/${id}/edit`,
            method: 'GET',
            success: function(data) {
            $('#edit_id').val(data.id);
            $('#edit_student_id').val(data.student_id);
            $('#edit_semester_id').val(data.semester_id);
            $('#edit_warning_date').val(data.warning_date);
            $('#edit_credits').val(data.credits);
            $('#edit_gpa_10').val(data.gpa_10);
            $('#edit_gpa_4').val(data.gpa_4);
            $('#edit_academic_status').val(data.academic_status);
            $('#edit_reason').val(data.reason);
            $('#edit_note').val(data.note);
            $('#editModal').modal('show');
        }
        });
        });

        // Edit form submit
        $('#editForm').submit(function(e) {
        e.preventDefault();
            let id = $('#edit_id').val();
            $.ajax({
            url: `/admin/edit-warnings/${id}`,
            method: 'PUT',
            data: $(this).serialize(),
        success: function(response) {
            $('#editModal').modal('hide');
        loadWarnings();
        alert('Cập nhật thành công!');
        },
        error: function(xhr) {
        alert('Lỗi: ' + xhr.responseJSON.message);
        }
        });
        });

        // Delete button click
        $(document).on('click', function() {
            let id = $(this).data('id');
            $('#delete_id').val(id);
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#confirmDelete').click(function() {
            let id = $('#delete_id').val();
            $.ajax({
            url: `/admin/warnings/${id}`,
            method: 'DELETE',
            success: function(response) {
            $('#deleteModal').modal('hide');
        loadWarnings();
        alert('Xóa thành công!');
        },
        error: function(xhr) {
        alert('Lỗi: ' + xhr.responseJSON.message);
        }
        });
        });
        });
        </script>
@endsection
