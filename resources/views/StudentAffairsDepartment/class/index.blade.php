@extends('layouts.studentAffairsDepartment')

@section('title', 'Lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
    ['label' => 'Lớp học']
    ]" />
@endsection

@section('main')
    <style>
        /* Custom CSS - chỉ những gì Bootstrap chưa có */
        .stats-card {
            background: linear-gradient(135deg, #0d6efd, #0056b3);
            color: white;
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
            .table th:nth-child(n+6),
            .table td:nth-child(n+6) {
                display: none;
            }

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

    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number">24</div>
                    <div class="stats-label">Tổng số lớp học</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="stats-card bg-success">
                    <div class="stats-number">8</div>
                    <div class="stats-label">Ngành học</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="stats-card bg-warning">
                    <div class="stats-number">5</div>
                    <div class="stats-label">Bộ môn</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="stats-card bg-info">
                    <div class="stats-number">856</div>
                    <div class="stats-label">Tổng sinh viên</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm lớp học...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ngành học</label>
                        <select class="form-select" id="majorFilter">
                            <option value="">Tất cả ngành</option>
                            <option value="1">Công nghệ thông tin</option>
                            <option value="2">Kỹ thuật phần mềm</option>
                            <option value="3">Khoa học máy tính</option>
                            <option value="4">Hệ thống thông tin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bộ môn</label>
                        <select class="form-select" id="facultyFilter">
                            <option value="">Tất cả bộ môn</option>
                            <option value="1">Công nghệ phần mềm</option>
                            <option value="2">Hệ thống thông tin</option>
                            <option value="3">Khoa học máy tính</option>
                            <option value="4">Mạng máy tính</option>
                            <option value="5">Trí tuệ nhân tạo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-secondary w-100 d-block" onclick="filterClasses()">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                    </div>
                </div>
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="classesTable">
                        <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Tên lớp</th>
                            <th width="20%" class="d-none d-md-table-cell">Ngành học</th>
                            <th width="15%" class="d-none d-lg-table-cell">Bộ môn</th>
                            <th width="20%" class="d-none d-md-table-cell">Giảng viên phụ trách</th>
                            <th width="10%">Khóa</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-id="1">
                            <td>1</td>
                            <td><strong>CNTT2021-1</strong></td>
                            <td class="d-none d-md-table-cell">Công nghệ thông tin</td>
                            <td class="d-none d-lg-table-cell">Công nghệ phần mềm</td>
                            <td class="d-none d-md-table-cell">TS. Nguyễn Văn A</td>
                            <td>2021</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewClass(1)" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editClass(1)" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteClass(1)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="2">
                            <td>2</td>
                            <td><strong>CNTT2021-2</strong></td>
                            <td class="d-none d-md-table-cell">Công nghệ thông tin</td>
                            <td class="d-none d-lg-table-cell">Công nghệ phần mềm</td>
                            <td class="d-none d-md-table-cell">ThS. Trần Thị B</td>
                            <td>2021</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewClass(2)" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editClass(2)" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteClass(2)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="3">
                            <td>3</td>
                            <td><strong>KTPM2022-1</strong></td>
                            <td class="d-none d-md-table-cell">Kỹ thuật phần mềm</td>
                            <td class="d-none d-lg-table-cell">Công nghệ phần mềm</td>
                            <td class="d-none d-md-table-cell">PGS.TS. Lê Văn C</td>
                            <td>2022</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewClass(3)" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editClass(3)" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteClass(3)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="4">
                            <td>4</td>
                            <td><strong>KHMT2022-1</strong></td>
                            <td class="d-none d-md-table-cell">Khoa học máy tính</td>
                            <td class="d-none d-lg-table-cell">Khoa học máy tính</td>
                            <td class="d-none d-md-table-cell">TS. Phạm Thị D</td>
                            <td>2022</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewClass(4)" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editClass(4)" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteClass(4)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="5">
                            <td>5</td>
                            <td><strong>HTTT2023-1</strong></td>
                            <td class="d-none d-md-table-cell">Hệ thống thông tin</td>
                            <td class="d-none d-lg-table-cell">Hệ thống thông tin</td>
                            <td class="d-none d-md-table-cell">GS.TS. Hoàng Văn E</td>
                            <td>2023</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewClass(5)" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editClass(5)" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteClass(5)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="6">
                            <td>6</td>
                            <td><strong>HTTT2023-2</strong></td>
                            <td class="d-none d-md-table-cell">Hệ thống thông tin</td>
                            <td class="d-none d-lg-table-cell">Hệ thống thông tin</td>
                            <td class="d-none d-md-table-cell">TS. Vũ Thị F</td>
                            <td>2023</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewClass(6)" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editClass(6)" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteClass(6)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">Hiển thị 1-6 trong tổng số 24 lớp học</small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Thêm lớp học mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="classForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên lớp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="className" placeholder="VD: CNTT2023-1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Khóa <span class="text-danger">*</span></label>
                                <select class="form-select" id="cohort" required>
                                    <option value="">Chọn khóa</option>
                                    <option value="1">2020</option>
                                    <option value="2">2021</option>
                                    <option value="3">2022</option>
                                    <option value="4">2023</option>
                                    <option value="5">2024</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngành học <span class="text-danger">*</span></label>
                                <select class="form-select" id="major" required>
                                    <option value="">Chọn ngành học</option>
                                    <option value="1">Công nghệ thông tin</option>
                                    <option value="2">Kỹ thuật phần mềm</option>
                                    <option value="3">Khoa học máy tính</option>
                                    <option value="4">Hệ thống thông tin</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Giảng viên phụ trách <span class="text-danger">*</span></label>
                                <select class="form-select" id="lecturer" required>
                                    <option value="">Chọn giảng viên</option>
                                    <option value="1">TS. Nguyễn Văn A</option>
                                    <option value="2">ThS. Trần Thị B</option>
                                    <option value="3">PGS.TS. Lê Văn C</option>
                                    <option value="4">TS. Phạm Thị D</option>
                                    <option value="5">GS.TS. Hoàng Văn E</option>
                                    <option value="6">TS. Vũ Thị F</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Lưu lớp học
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
                            <strong>Tên lớp:</strong> <span id="viewClassName">CNTT2021-1</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Khóa:</strong> <span id="viewCohort">2021</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngành học:</strong> <span id="viewMajor">Công nghệ thông tin</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Bộ môn:</strong> <span id="viewFaculty">Công nghệ phần mềm</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Giảng viên phụ trách:</strong> <span id="viewLecturer">TS. Nguyễn Văn A</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Chức vụ:</strong> <span id="viewPosition">Trưởng bộ môn</span>
                        </div>
                        <div class="col-12">
                            <strong>Ngày tạo:</strong> <span id="viewCreatedAt">15/01/2023</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-warning" onclick="editClassFromView()">
                        <i class="fas fa-edit me-1"></i>Chỉnh sửa
                    </button>
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
                    <i class="fas fa-trash-alt text-danger mb-3" style="font-size: 3rem;"></i>
                    <h6>Bạn có chắc chắn muốn xóa lớp học này?</h6>
                    <p class="text-muted">Lớp: <strong id="deleteClassName">CNTT2021-1</strong></p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hành động này không thể hoàn tác!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash me-1"></i>Xóa lớp học
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Form submit
            $('#classForm').on('submit', function(e) {
                e.preventDefault();
                saveClass();
            });

            // Search functionality
            $('#searchInput').on('keyup', function() {
                searchClasses();
            });

            // Reset form when modal closes
            $('#addClassModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        });

        // Sample data for demo
        const classesData = {
            1: {
                name: 'CNTT2021-1',
                major: 'Công nghệ thông tin',
                faculty: 'Công nghệ phần mềm',
                lecturer: 'TS. Nguyễn Văn A',
                position: 'Trưởng bộ môn',
                cohort: '2021',
                created_at: '15/01/2023'
            },
            2: {
                name: 'CNTT2021-2',
                major: 'Công nghệ thông tin',
                faculty: 'Công nghệ phần mềm',
                lecturer: 'ThS. Trần Thị B',
                position: 'Giảng viên',
                cohort: '2021',
                created_at: '15/01/2023'
            },
            3: {
                name: 'KTPM2022-1',
                major: 'Kỹ thuật phần mềm',
                faculty: 'Công nghệ phần mềm',
                lecturer: 'PGS.TS. Lê Văn C',
                position: 'Phó trưởng khoa',
                cohort: '2022',
                created_at: '20/02/2023'
            },
            4: {
                name: 'KHMT2022-1',
                major: 'Khoa học máy tính',
                faculty: 'Khoa học máy tính',
                lecturer: 'TS. Phạm Thị D',
                position: 'Giảng viên',
                cohort: '2022',
                created_at: '25/02/2023'
            },
            5: {
                name: 'HTTT2023-1',
                major: 'Hệ thống thông tin',
                faculty: 'Hệ thống thông tin',
                lecturer: 'GS.TS. Hoàng Văn E',
                position: 'Trưởng khoa',
                cohort: '2023',
                created_at: '10/03/2023'
            },
            6: {
                name: 'HTTT2023-2',
                major: 'Hệ thống thông tin',
                faculty: 'Hệ thống thông tin',
                lecturer: 'TS. Vũ Thị F',
                position: 'Phó trưởng bộ môn',
                cohort: '2023',
                created_at: '10/03/2023'
            }
        };

        let currentClassId = null;
        let isEditMode = false;

        // View class
        function viewClass(id) {
            const data = classesData[id];
            if (data) {
                $('#viewClassName').text(data.name);
                $('#viewCohort').text(data.cohort);
                $('#viewMajor').text(data.major);
                $('#viewFaculty').text(data.faculty);
                $('#viewLecturer').text(data.lecturer);
                $('#viewPosition').text(data.position);
                $('#viewCreatedAt').text(data.created_at);

                currentClassId = id;
                $('#viewClassModal').modal('show');
            }
        }

        // Edit class
        function editClass(id) {
            const data = classesData[id];
            if (data) {
                isEditMode = true;
                currentClassId = id;

                // Update modal title
                $('#addClassModal .modal-title').html('<i class="fas fa-edit me-2"></i>Chỉnh sửa lớp học');
                $('#addClassModal .btn-primary').html('<i class="fas fa-save me-1"></i>Cập nhật');

                // Fill form with demo data
                $('#className').val(data.name);
                $('#cohort').val(data.cohort === '2021' ? '2' : (data.cohort === '2022' ? '3' : '4'));
                $('#major').val(data.major === 'Công nghệ thông tin' ? '1' :
                    (data.major === 'Kỹ thuật phần mềm' ? '2' :
                        (data.major === 'Khoa học máy tính' ? '3' : '4')));
                $('#lecturer').val(id);

                $('#addClassModal').modal('show');
            }
        }

        // Edit from view modal
        function editClassFromView() {
            $('#viewClassModal').modal('hide');
            setTimeout(() => {
                editClass(currentClassId);
            }, 300);
        }

        // Delete class
        function deleteClass(id) {
            const data = classesData[id];
            if (data) {
                currentClassId = id;
                $('#deleteClassName').text(data.name);
                $('#deleteModal').modal('show');
            }
        }

        // Confirm delete
        $('#confirmDelete').on('click', function() {
            if (currentClassId) {
                // Remove row with animation
                $(`tr[data-id="${currentClassId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });

                $('#deleteModal').modal('hide');
                showAlert('success', 'Xóa lớp học thành công!');
                currentClassId = null;
            }
        });

        // Save class
        function saveClass() {
            // Basic validation
            if (!$('#className').val() || !$('#cohort').val() || !$('#major').val() || !$('#lecturer').val()) {
                showAlert('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc!');
                return;
            }

            const formData = {
                name: $('#className').val(),
                cohort: $('#cohort option:selected').text(),
                major: $('#major option:selected').text(),
                faculty: $('#major option:selected').text() === 'Công nghệ thông tin' || $('#major option:selected').text() === 'Kỹ thuật phần mềm' ?
                    'Công nghệ phần mềm' : $('#major option:selected').text(),
                lecturer: $('#lecturer option:selected').text(),
                position: 'Giảng viên'
            };

            if (isEditMode) {
                // Update existing row
                const row = $(`tr[data-id="${currentClassId}"]`);
                row.find('td:nth-child(2) strong').text(formData.name);
                row.find('td:nth-child(3)').text(formData.major);
                row.find('td:nth-child(4)').text(formData.faculty);
                row.find('td:nth-child(5)').text(formData.lecturer);
                row.find('td:nth-child(6)').text(formData.cohort);

                showAlert('success', 'Cập nhật lớp học thành công!');
            } else {
                // Add new row (demo)
                const newId = Date.now();
                const newRow = `
            <tr data-id="${newId}">
                <td>${$('#classesTable tbody tr').length + 1}</td>
                <td><strong>${formData.name}</strong></td>
                <td class="d-none d-md-table-cell">${formData.major}</td>
                <td class="d-none d-lg-table-cell">${formData.faculty}</td>
                <td class="d-none d-md-table-cell">${formData.lecturer}</td>
                <td>${formData.cohort}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-info" onclick="viewClass(${newId})" title="Xem">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="editClass(${newId})" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteClass(${newId})" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
                $('#classesTable tbody').append(newRow);

                // Add to demo data
                classesData[newId] = {
                    name: formData.name,
                    major: formData.major,
                    faculty: formData.faculty,
                    lecturer: formData.lecturer,
                    position: formData.position,
                    cohort: formData.cohort,
                    created_at: new Date().toLocaleDateString('vi-VN')
                };

                showAlert('success', 'Thêm lớp học thành công!');
            }

            $('#addClassModal').modal('hide');
        }

        // Reset form
        function resetForm() {
            $('#classForm')[0].reset();
            $('#addClassModal .modal-title').html('<i class="fas fa-plus-circle me-2"></i>Thêm lớp học mới');
            $('#addClassModal .btn-primary').html('<i class="fas fa-save me-1"></i>Lưu lớp học');
            isEditMode = false;
            currentClassId = null;
        }

        // Search classes
        function searchClasses() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            $('#classesTable tbody tr').each(function() {
                const row = $(this);
                const text = row.text().toLowerCase();
                if (text.includes(searchTerm)) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        // Filter classes
        function filterClasses() {
            const majorFilter = $('#majorFilter').val();
            const facultyFilter = $('#facultyFilter').val();

            $('#classesTable tbody tr').each(function() {
                const row = $(this);
                let show = true;

                if (majorFilter) {
                    const majorText = row.find('td:nth-child(3)').text().trim();
                    const majorMap = {
                        '1': 'Công nghệ thông tin',
                        '2': 'Kỹ thuật phần mềm',
                        '3': 'Khoa học máy tính',
                        '4': 'Hệ thống thông tin'
                    };
                    if (majorText !== majorMap[majorFilter]) {
                        show = false;
                    }
                }

                if (facultyFilter && show) {
                    const facultyText = row.find('td:nth-child(4)').text().trim();
                    const facultyMap = {
                        '1': 'Công nghệ phần mềm',
                        '2': 'Hệ thống thông tin',
                        '3': 'Khoa học máy tính',
                        '4': 'Mạng máy tính',
                        '5': 'Trí tuệ nhân tạo'
                    };
                    if (facultyText !== facultyMap[facultyFilter]) {
                        show = false;
                    }
                }

                if (show) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        // Show alert
        function showAlert(type, message) {
            const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
            $('.container-fluid').prepend(alertHtml);

            // Auto hide after 3 seconds
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 3000);
        }
    </script>
@endsection

