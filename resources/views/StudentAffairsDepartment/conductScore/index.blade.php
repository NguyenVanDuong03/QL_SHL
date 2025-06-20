@extends('layouts.studentAffairsDepartment')

@section('title', 'Điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Điểm rèn luyện']]" />
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
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control search-input" placeholder="Tìm kiếm..."
                                   id="searchInput" style="width: 250px;">
                        </div>
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
                                    <th scope="col">Tên đợt đánh giá</th>
                                    <th scope="col">Học kỳ</th>
                                    <th scope="col">Giai đoạn</th>
                                    <th scope="col">Ngày tạo</th>
                                    <th scope="col" class="text-center" style="width: 200px;">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                <!-- Data will be populated by JavaScript -->
                                </tbody>
                            </table>
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
                    <form id="createForm">
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
                                    <option value="1">HK1 - 2024-2025</option>
                                    <option value="2">HK2 - 2024-2025</option>
                                    <option value="3">HK3 - 2024-2025</option>
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
                                <div class="col-md-6">
                                    <label for="create_student_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_student_start" name="phases[0][open_date]"
                                           data-role="0" data-type="start" required>
                                    <div class="error-message" id="create_student_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_student_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_student_end" name="phases[0][end_date]"
                                           data-role="0" data-type="end" required>
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
                                <div class="col-md-6">
                                    <label for="create_teacher_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_teacher_start" name="phases[1][open_date]"
                                           data-role="1" data-type="start" required>
                                    <div class="error-message" id="create_teacher_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_teacher_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_teacher_end" name="phases[1][end_date]"
                                           data-role="1" data-type="end" required>
                                    <div class="error-message" id="create_teacher_end_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Office Phase -->
                        <div class="phase-card">
                            <div class="phase-header">
                                <i class="fas fa-building phase-icon text-purple"></i>
                                <h6 class="mb-0 fw-semibold">Văn phòng khoa</h6>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="create_office_start" class="form-label">
                                        Thời gian bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_office_start" name="phases[2][open_date]"
                                           data-role="2" data-type="start" required>
                                    <div class="error-message" id="create_office_start_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="create_office_end" class="form-label">
                                        Thời gian kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" class="form-control phase-datetime"
                                           id="create_office_end" name="phases[2][end_date]"
                                           data-role="2" data-type="end" required>
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
                    <form id="editForm">
                        <input type="hidden" id="edit_id" name="id">
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
                                    <option value="1">HK1 - 2024-2025</option>
                                    <option value="2">HK2 - 2024-2025</option>
                                    <option value="3">HK3 - 2024-2025</option>
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
                                <i class="fas fa-building phase-icon text-purple"></i>
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
                    <!-- Content will be populated by JavaScript -->
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
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
            // Mock data
            const mockSemesters = {
                1: { id: 1, name: "HK1", school_year: "2024-2025" },
                2: { id: 2, name: "HK2", school_year: "2024-2025" },
                3: { id: 3, name: "HK3", school_year: "2024-2025" }
            };

            let evaluationPeriods = [
                {
                    id: 1,
                    semester_id: 1,
                    name: "Đợt 1 HK1 2024-2025",
                    semester: mockSemesters[1],
                    phases: [
                        { id: 1, role: 0, open_date: "2024-12-01T08:00", end_date: "2024-12-07T23:59" },
                        { id: 2, role: 1, open_date: "2024-12-08T08:00", end_date: "2024-12-14T23:59" },
                        { id: 3, role: 2, open_date: "2024-12-15T08:00", end_date: "2024-12-21T23:59" }
                    ],
                    created_at: "2024-11-20T10:00:00",
                    updated_at: "2024-11-20T10:00:00"
                },
                {
                    id: 2,
                    semester_id: 1,
                    name: "Đợt 2 HK1 2024-2025",
                    semester: mockSemesters[1],
                    phases: [
                        { id: 4, role: 0, open_date: "2025-01-10T08:00", end_date: "2025-01-16T23:59" },
                        { id: 5, role: 1, open_date: "2025-01-17T08:00", end_date: "2025-01-23T23:59" },
                        { id: 6, role: 2, open_date: "2025-01-24T08:00", end_date: "2025-01-30T23:59" }
                    ],
                    created_at: "2024-11-25T14:30:00",
                    updated_at: "2024-11-25T14:30:00"
                }
            ];

            const roleNames = {
                0: "Sinh viên",
                1: "Giáo viên",
                2: "Văn phòng khoa"
            };

            const roleBadgeClasses = {
                0: "badge-role-student",
                1: "badge-role-teacher",
                2: "badge-role-office"
            };

            let currentEditId = null;
            let currentDeleteId = null;

            // Initialize
            renderTable();
            setupEventListeners();

            function renderTable(data = evaluationPeriods) {
                const tbody = $('#tableBody');
                tbody.empty();

                if (data.length === 0) {
                    tbody.append(`
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                        Không có dữ liệu
                    </td>
                </tr>
            `);
                    return;
                }

                data.forEach((period, index) => {
                    const phaseBadges = period.phases.map(phase =>
                        `<span class="badge ${roleBadgeClasses[phase.role]} me-1">${roleNames[phase.role]}</span>`
                    ).join('');

                    tbody.append(`
                <tr>
                    <td class="text-center fw-bold">${index + 1}</td>
                    <td>
                        <div class="fw-semibold">${period.name}</div>
                    </td>
                    <td>
                        <span class="badge bg-secondary">${period.semester.name} - ${period.semester.school_year}</span>
                    </td>
                    <td>${phaseBadges}</td>
                    <td>
                        <small class="text-muted">${formatDateTime(period.created_at)}</small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group-actions">
                            <button class="btn btn-primary btn-sm" onclick="viewDetail(${period.id})" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="editPeriod(${period.id})" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deletePeriod(${period.id})" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
                });
            }

            function setupEventListeners() {
                // Search functionality
                $('#searchInput').on('input', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    const filtered = evaluationPeriods.filter(period =>
                        period.name.toLowerCase().includes(searchTerm) ||
                        period.semester.name.toLowerCase().includes(searchTerm) ||
                        period.semester.school_year.includes(searchTerm)
                    );
                    renderTable(filtered);
                });

                // Form submissions
                $('#submitCreate').click(handleCreate);
                $('#submitEdit').click(handleEdit);
                $('#confirmDelete').click(handleDelete);

                // Form resets
                $('#resetCreateForm').click(() => resetForm('create'));
                $('#resetEditForm').click(() => resetForm('edit'));

                // Modal events
                $('#createModal').on('hidden.bs.modal', () => resetForm('create'));
                $('#editModal').on('hidden.bs.modal', () => resetForm('edit'));

                // Real-time validation
                $('.phase-datetime').on('change', () => validatePhases('create'));
                $('.phase-datetime-edit').on('change', () => validatePhases('edit'));
            }

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

                // Validate each phase
                phases.forEach((phase, index) => {
                    const roleName = getRoleName(phase.role);

                    // Check if end time is after start time
                    if (phase.start && phase.end) {
                        const startDate = new Date(phase.start);
                        const endDate = new Date(phase.end);

                        if (startDate >= endDate) {
                            showError(`${prefix}_${roleName}_end_error`, 'Thời gian kết thúc phải sau thời gian bắt đầu');
                            hasError = true;
                        }
                    }
                });

                // Check for overlapping phases
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
                const roleMap = { 0: 'student', 1: 'teacher', 2: 'office' };
                return roleMap[role];
            }

            function validateForm(formType) {
                const prefix = formType === 'create' ? 'create' : 'edit';
                clearErrors(formType);

                let hasError = false;

                // Validate name
                const name = $(`#${prefix}_name`).val().trim();
                if (!name) {
                    showError(`${prefix}_name_error`, 'Vui lòng nhập tên đợt đánh giá');
                    hasError = true;
                }

                // Validate semester
                const semesterId = $(`#${prefix}_semester`).val();
                if (!semesterId) {
                    showError(`${prefix}_semester_error`, 'Vui lòng chọn học kỳ');
                    hasError = true;
                }

                // Validate required phase fields
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

                // Validate phases if no basic errors
                if (!hasError) {
                    hasError = !validatePhases(formType);
                }

                return !hasError;
            }

            function handleCreate() {
                if (!validateForm('create')) return;

                const formData = getFormData('create');
                const newPeriod = {
                    id: Math.max(...evaluationPeriods.map(p => p.id)) + 1,
                    semester_id: parseInt(formData.semester_id),
                    name: formData.name,
                    semester: mockSemesters[formData.semester_id],
                    phases: [
                        { id: getNextPhaseId(), role: 0, open_date: formData.phases[0].start, end_date: formData.phases[0].end },
                        { id: getNextPhaseId(), role: 1, open_date: formData.phases[1].start, end_date: formData.phases[1].end },
                        { id: getNextPhaseId(), role: 2, open_date: formData.phases[2].start, end_date: formData.phases[2].end }
                    ],
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString()
                };

                evaluationPeriods.push(newPeriod);
                renderTable();
                $('#createModal').modal('hide');
                showToast('Tạo đợt chấm điểm thành công!', 'success');
            }

            function handleEdit() {
                if (!validateForm('edit') || !currentEditId) return;

                const formData = getFormData('edit');
                const index = evaluationPeriods.findIndex(p => p.id === currentEditId);

                if (index !== -1) {
                    evaluationPeriods[index] = {
                        ...evaluationPeriods[index],
                        name: formData.name,
                        semester_id: parseInt(formData.semester_id),
                        semester: mockSemesters[formData.semester_id],
                        phases: [
                            { ...evaluationPeriods[index].phases[0], open_date: formData.phases[0].start, end_date: formData.phases[0].end },
                            { ...evaluationPeriods[index].phases[1], open_date: formData.phases[1].start, end_date: formData.phases[1].end },
                            { ...evaluationPeriods[index].phases[2], open_date: formData.phases[2].start, end_date: formData.phases[2].end }
                        ],
                        updated_at: new Date().toISOString()
                    };

                    renderTable();
                    $('#editModal').modal('hide');
                    showToast('Cập nhật đợt chấm điểm thành công!', 'success');
                }
            }

            function handleDelete() {
                if (!currentDeleteId) return;

                evaluationPeriods = evaluationPeriods.filter(p => p.id !== currentDeleteId);
                renderTable();
                $('#deleteModal').modal('hide');
                showToast('Xóa đợt chấm điểm thành công!', 'success');
                currentDeleteId = null;
            }

            function getFormData(formType) {
                const prefix = formType === 'create' ? 'create' : 'edit';
                return {
                    name: $(`#${prefix}_name`).val().trim(),
                    semester_id: $(`#${prefix}_semester`).val(),
                    phases: [
                        {
                            start: $(`#${prefix}_student_start`).val(),
                            end: $(`#${prefix}_student_end`).val()
                        },
                        {
                            start: $(`#${prefix}_teacher_start`).val(),
                            end: $(`#${prefix}_teacher_end`).val()
                        },
                        {
                            start: $(`#${prefix}_office_start`).val(),
                            end: $(`#${prefix}_office_end`).val()
                        }
                    ]
                };
            }

            function getNextPhaseId() {
                const allPhases = evaluationPeriods.flatMap(p => p.phases);
                return allPhases.length > 0 ? Math.max(...allPhases.map(p => p.id)) + 1 : 1;
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

            function showToast(message, type = 'info') {
                // Simple toast implementation
                const toast = $(`
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'info'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);

                if (!$('.toast-container').length) {
                    $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
                }

                $('.toast-container').append(toast);
                toast.toast('show');

                setTimeout(() => toast.remove(), 5000);
            }

            // Global functions for button clicks
            window.viewDetail = function(id) {
                const period = evaluationPeriods.find(p => p.id === id);
                if (!period) return;

                const phasesHtml = period.phases.map(phase => `
            <div class="phase-card mb-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${phase.role === 0 ? 'graduation-cap' : phase.role === 1 ? 'chalkboard-teacher' : 'building'} me-2"></i>
                        <div>
                            <h6 class="mb-0 fw-semibold">${roleNames[phase.role]}</h6>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                ${formatDateTime(phase.open_date)} - ${formatDateTime(phase.end_date)}
                            </small>
                        </div>
                    </div>
                    <span class="badge ${roleBadgeClasses[phase.role]}">${roleNames[phase.role]}</span>
                </div>
            </div>
        `).join('');

                $('#detailContent').html(`
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label text-muted">Tên đợt đánh giá</label>
                    <p class="fw-semibold">${period.name}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">Học kỳ</label>
                    <p class="fw-semibold">${period.semester.name} - ${period.semester.school_year}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">Ngày tạo</label>
                    <p>${formatDateTime(period.created_at)}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">Cập nhật lần cuối</label>
                    <p>${formatDateTime(period.updated_at)}</p>
                </div>
            </div>
            <hr>
            <h5 class="mb-3">Giai đoạn chấm điểm</h5>
            ${phasesHtml}
        `);

                $('#detailModal').modal('show');
            };

            window.editPeriod = function(id) {
                const period = evaluationPeriods.find(p => p.id === id);
                if (!period) return;

                currentEditId = id;

                // Populate form
                $('#edit_id').val(period.id);
                $('#edit_name').val(period.name);
                $('#edit_semester').val(period.semester_id);

                // Populate phases
                period.phases.forEach(phase => {
                    const roleName = getRoleName(phase.role);
                    $(`#edit_${roleName}_start`).val(phase.open_date);
                    $(`#edit_${roleName}_end`).val(phase.end_date);
                });

                $('#editModal').modal('show');
            };

            window.deletePeriod = function(id) {
                const period = evaluationPeriods.find(p => p.id === id);
                if (!period) return;

                currentDeleteId = id;
                $('#deleteItemName').text(period.name);
                $('#deleteModal').modal('show');
            };
        });
    </script>
@endpush


