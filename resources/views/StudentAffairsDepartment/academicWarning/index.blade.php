@extends('layouts.studentAffairsDepartment')

@section('title', 'Quản lý cảnh báo học vụ')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Quản lý cảnh báo học vụ']]"/>
@endsection

@push('styles')
    <style>
        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 95vw;
            }
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Quản lý cảnh báo học vụ</h4>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWarningModal">
                    <i class="fas fa-plus"></i> Thêm mới
                </button>
            </div>

            <form method="GET" action="{{ route('student-affairs-department.academic-warning.index') }}"
                  class="card-header d-flex justify-content-between align-items-center gap-2">
                <select class="form-select w-auto" id="semesterFilter" name="semester_id">
                    <option value="">Tất cả học kỳ</option>
                    @forelse($data['getSemesters'] ?? [] as $item)
                        <option value="{{ $item['id'] }}" {{ request('semester_id') == $item['id'] ? 'selected' : '' }}>
                            {{ $item['name'] }} - {{ $item['school_year'] }}
                        </option>
                    @empty
                        <option value="" disabled>Không có học kỳ nào</option>
                    @endforelse
                </select>
                <div class="input-group w-auto">
                    <input type="text" class="form-control w-auto" name="search" id="search-academic"
                           placeholder="Tìm kiếm..." value="{{ request('search') }}">

                    <button type="submit" class="btn btn-outline-secondary btn-filter">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </div>
            </form>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                        <tr>
                            <th class="text-center d-none d-md-table-cell">ID</th>
                            <th>Sinh viên</th>
                            <th class="text-center">Học kỳ</th>
                            <th class="text-center">Số tín chỉ</th>
                            <th class="text-center">GPA (10) / (4)</th>
                            <th class="text-center">Mức cảnh báo</th>
                            <th class="text-center">Ghi chú</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody id="warningsTable">
                        @forelse($data['academicWarnings']['data'] ?? [] as $item)
                            <tr data-bs-target="#view-academic-warning"
                                data-bs-toggle="modal"
                                data-detail="{{ json_encode($item) }}" class="clickable-row"
                            >
                                <td class="text-center d-none d-md-table-cell">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item['student']['user']['name'] }} <br>
                                    <small
                                        class="text-muted fw-thin">{{ $item['student']['study_class']['name'] }}</small>
                                </td>
                                <td class="text-center">{{ $item['semester']['name'] }}
                                    - {{ $item['semester']['school_year'] }} </td>
                                <td class="text-center">{{ $item['credits'] }}</td>
                                <td class="text-center">{{ $item['gpa_10'] }} / {{ $item['gpa_4'] }}</td>
                                <td class="text-center">{{ $item['academic_status'] }}</td>
                                <td class="text-center title_cut">{{ $item['note'] ?? '---' }}</td>
                                <td class="text-center">
                                    <div class="btn-group gap-2">
                                        <button class="btn btn-sm btn-warning btn-edit-academic" data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                title="Sửa"
                                                data-id="{{ $item['id'] }}"
                                                data-semester-id="{{ $item['semester_id'] }}"
                                                data-student-id="{{ $item['student_id'] }}"
                                                data-student-code="{{ $item['student']['student_code'] }}"
                                                data-student-name="{{ $item['student']['user']['name'] }}"
                                                data-student-email="{{ $item['student']['user']['email'] }}"
                                                data-study-class-name="{{ $item['student']['study_class']['name'] }}"
                                                data-credits="{{ $item['credits'] }}"
                                                data-gpa-10="{{ $item['gpa_10'] }}"
                                                data-gpa-4="{{ $item['gpa_4'] }}"
                                                data-academic-status="{{ $item['academic_status'] }}"
                                                data-note="{{ $item['note'] }}"
                                                data-current-page="{{ $data['academicWarnings']['current_page'] }}"
                                        ><i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete-academic" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                title="Xóa"
                                                data-id="{{ $item['id'] }}"
                                                data-current-page="{{ $data['academicWarnings']['current_page'] }}"
                                        ><i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có cảnh báo học vụ nào</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <x-pagination.pagination :paginate="$data['academicWarnings']"/>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tạo Cảnh Báo Học Vụ -->
    <div class="modal fade" id="createWarningModal" tabindex="-1" aria-labelledby="createWarningModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createWarningModalLabel">Tạo cảnh báo học vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createWarningForm" method="POST"
                          action="{{ route('student-affairs-department.academic-warning.store') }}">
                        @csrf
                        @method('POST')
                        <!-- Student Search Section -->
                        <div class="mb-3">
                            <label for="student-search" class="form-label">Tìm kiếm sinh viên <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                                    <input type="text" class="form-control" id="student-search"
                                           placeholder="Nhập mã sinh viên, tên hoặc email..." required>
                                    <button type="button" class="btn btn-outline-secondary d-none"
                                            id="clear-student-btn">
                                        <i class="fa fa-x"></i>
                                    </button>
                                </div>

                                <!-- Search Results Dropdown -->
                                <div class="position-absolute w-100 mt-1 d-none" id="search-results-dropdown"
                                     style="z-index: 1050; max-height: 300px; overflow-y: auto;">
                                    <div class="list-group shadow" id="search-results-list">
                                        <!-- Results will be populated by jQuery -->
                                    </div>
                                </div>

                                <!-- Selected Student Display -->
                                <div class="card mt-2 d-none" id="selected-student-card">
                                    <div
                                        class="card-body py-2 px-3 bg-success bg-opacity-10 border border-success border-opacity-25">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-check-circle-fill text-success me-2"></i>
                                            <span class="text-success fw-medium" id="selected-student-info"></span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="selected-student-id" name="student_id" required>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="semester_id" class="form-label">Học kỳ <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="semester_id" name="semester_id" required>
                                    @forelse($data['getSemesters'] ?? [] as $semester)
                                        <option value="{{ $semester['id'] }}">
                                            {{ $semester['name'] }} - {{ $semester['school_year'] }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Không có học kỳ nào</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="credits" class="form-label">Số tín chỉ <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="credits" name="credits" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gpa_10" class="form-label">GPA (thang 10) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="gpa_10" name="gpa_10" min="0"
                                       max="10" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gpa_4" class="form-label">GPA (thang 4) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="gpa_4" name="gpa_4" min="0"
                                       max="4" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="academic_status" class="form-label">Mức xử lý học vụ <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="academic_status" name="academic_status" required>
                                    <option value="Cảnh báo">Cảnh báo</option>
                                    <option value="Đình chỉ học tập">Đình chỉ học tập</option>
                                    <option value="Buộc thôi học">Buộc thôi học</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" id="note" name="note" rows="4"
                                          placeholder="Nhập ghi chú (không bắt buộc)..."></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    style="width: 120px;">
                                Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary btn-create-academic" style="width: 120px;">
                                Tạo cảnh báo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view-academic-warning" tabindex="-1" aria-labelledby="viewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Chi tiết cảnh báo học vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã sinh viên</label>
                            <p id="view_student_code" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên sinh viên</label>
                            <p id="view_student_name" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <p id="view_student_email" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lớp học</label>
                            <p id="view_study_class_name" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Học kỳ</label>
                            <p id="view_semester" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số tín chỉ</label>
                            <p id="view_credits" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">GPA (10 / 4)</label>
                            <p id="view_gpa" class="fw-bold"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mức cảnh báo</label>
                            <p id="view_academic_status" class="fw-bold"></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Ghi chú</label>
                            <p id="view_note" class="fw-bold"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editModalLabel">Sửa cảnh báo học vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="current_page" class="current_page">
                        <input type="hidden" id="edit_student_id" name="student_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="fw-medium mb-0 text-truncate edit_student_name"></p>
                                <p class="mb-0 text-muted edit_student_code"></p>

                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="fw-medium mb-0 edit_study_class_name"></p>
                                <p class="mb-0 edit_student_email"></p>

                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_semester_id" class="form-label">Học kỳ <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_semester_id" name="semester_id" required>
                                    @forelse($data['getSemesters'] ?? [] as $semester)
                                        <option value="{{ $semester['id'] }}">
                                            {{ $semester['name'] }} - {{ $semester['school_year'] }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Không có học kỳ nào</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_credits" class="form-label">Số tín chỉ <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_credits" name="credits" min="0"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_gpa_10" class="form-label">GPA (thang 10) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="edit_gpa_10" name="gpa_10"
                                       min="0" max="10" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_gpa_4" class="form-label">GPA (thang 4) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="edit_gpa_4" name="gpa_4"
                                       min="0" max="4" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_academic_status" class="form-label">Mức xử lý học vụ <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_academic_status" name="academic_status" required>
                                    <option value="Cảnh báo">Cảnh báo</option>
                                    <option value="Đình chỉ học tập">Đình chỉ học tập</option>
                                    <option value="Buộc thôi học">Buộc thôi học</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" id="edit_note" name="note" rows="4"
                                          placeholder="Nhập ghi chú (không bắt buộc)..."></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    style="width: 120px;">Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary" style="width: 120px;">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa cảnh báo học vụ này không?</p>
                </div>
                <form method="POST" id="deleteForm" action="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_id" name="id">
                    <input type="hidden" name="current_page" class="current_page">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger" id="confirmDelete">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".title_cut").each(function () {
                var text = $(this).text().trim();
                if (text.length > 20) {
                    $(this).attr("title", text);
                    $(this).text(text.substring(0, 20) + '...');
                }
            });

            $('.btn-edit-academic').on('click', function () {
                const id = $(this).data('id');
                const semesterId = $(this).data('semester-id');
                const studentId = $(this).data('student-id');
                const studentCode = $(this).data('student-code');
                const studentName = $(this).data('student-name');
                const studentEmail = $(this).data('student-email');
                const studyClassName = $(this).data('study-class-name');
                const credits = $(this).data('credits');
                const gpa10 = $(this).data('gpa-10');
                const gpa4 = $(this).data('gpa-4');
                const academicStatus = $(this).data('academic-status');
                const note = $(this).data('note');
                const currentPage = $(this).data('current-page');

                $('#edit_student_id').val(studentId);
                $('.edit_student_code').text(studentCode);
                $('.edit_study_class_name').text(studyClassName);
                $('.edit_student_name').text(studentName);
                $('.edit_student_email').text(studentEmail);
                $('#edit_semester_id').val(semesterId);
                $('#edit_credits').val(credits);
                $('#edit_gpa_10').val(gpa10);
                $('#edit_gpa_4').val(gpa4);
                $('#edit_academic_status').val(academicStatus);
                $('#edit_note').val(note);
                $('.current_page').val(currentPage);

                $('#editForm').attr('action', '{{ route('student-affairs-department.academic-warning.update', '') }}/' + id);

            });

            $('#view-academic-warning').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const detail = button.data('detail');

                $('#view_student_code').text(detail.student.student_code);
                $('#view_student_name').text(detail.student.user.name);
                $('#view_student_email').text(detail.student.user.email);
                $('#view_study_class_name').text(detail.student.study_class.name);
                $('#view_semester').text(`${detail.semester.name} - ${detail.semester.school_year}`);
                $('#view_credits').text(detail.credits);
                $('#view_gpa').text(`${detail.gpa_10} / ${detail.gpa_4}`);
                $('#view_academic_status').text(detail.academic_status);
                $('#view_note').text(detail.note || '---');
            });

            $('.btn-delete-academic').on('click', function () {
                const id = $(this).data('id');
                const currentPage = $(this).data('current-page');

                $('#delete_id').val(id);
                $('.current_page').val(currentPage);

                $('#deleteForm').attr('action', '{{ route('student-affairs-department.academic-warning.delete', '') }}/' + id);
            });

            let searchTimeout;
            const $searchInput = $('#student-search');
            const $searchResultsDropdown = $('#search-results-dropdown');
            const $searchResultsList = $('#search-results-list');
            const $selectedStudentCard = $('#selected-student-card');
            const $selectedStudentInfo = $('#selected-student-info');
            const $selectedStudentId = $('#selected-student-id');
            const $clearStudentBtn = $('#clear-student-btn');

            // Debounce search function
            $searchInput.on('input', function () {
                const searchTerm = $(this).val().toString().trim();

                clearTimeout(searchTimeout);

                if (searchTerm.length >= 2) {
                    searchTimeout = setTimeout(function () {
                        searchStudents(searchTerm);
                    }, 300);
                } else {
                    $searchResultsDropdown.addClass('d-none');
                }
            });

            const mockStudents = @json($data['students'] ?? []);

            // Search students function
            function searchStudents(searchTerm) {
                const filteredStudents = mockStudents.filter(student =>
                    (student.student_code && student.student_code.toLowerCase().includes(searchTerm.toLowerCase())) ||
                    (student.user && student.user.name && student.user.name.toLowerCase().includes(searchTerm.toLowerCase())) ||
                    (student.user && student.user.email && student.user.email.toLowerCase().includes(searchTerm.toLowerCase()))
                ).slice(0, 10); // Limit to 10 results

                renderSearchResults(filteredStudents);
            }

            // Render search results
            function renderSearchResults(students) {
                $searchResultsList.empty();

                if (students.length > 0) {
                    students.forEach(student => {
                        const $item = $(`
                <a href="#" class="list-group-item list-group-item-action student-result" data-student='${JSON.stringify(student)}'>
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa fa-user text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-light text-dark border">${student.student_code}</span>
                                <span class="badge bg-secondary">${student.study_class.name}</span>
                            </div>
                            <p class="fw-medium mb-0 text-truncate">${student.user.name}</p>
                            <div class="d-flex align-items-center gap-1 small text-muted">
                                <i class="fa fa-envelope-fill"></i>
                                <span class="text-truncate">${student.user.email}</span>
                            </div>
                        </div>
                    </div>
                </a>
            `);

                        $searchResultsList.append($item);
                    });

                    $searchResultsDropdown.removeClass('d-none');
                } else {
                    $searchResultsList.append(`
            <div class="list-group-item text-center text-muted py-3">
                <i class="fa fa-search me-2"></i>Không tìm thấy sinh viên
            </div>
        `);
                    $searchResultsDropdown.removeClass('d-none');
                }
            }

            // Handle student selection
            $(document).on('click', '.student-result', function (e) {
                e.preventDefault();

                const student = $(this).data('student');
                selectStudent(student);
                $searchResultsDropdown.addClass('d-none');
            });

            // Select student function
            function selectStudent(student) {
                $selectedStudentId.val(student.id);
                $searchInput.val(`${student.student_code} - ${student.user.name}`);
                $selectedStudentInfo.text(`Đã chọn: ${student.user.name} (${student.student_code})`);
                $selectedStudentCard.removeClass('d-none');
                $clearStudentBtn.removeClass('d-none');
            }

            // Clear student selection
            $clearStudentBtn.on('click', function () {
                clearStudentSelection();
            });

            function clearStudentSelection() {
                $selectedStudentId.val('');
                $searchInput.val('');
                $selectedStudentCard.addClass('d-none');
                $clearStudentBtn.addClass('d-none');
            }

            // Close dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#student-search, #search-results-dropdown').length) {
                    $searchResultsDropdown.addClass('d-none');
                }
            });

            // Reset form when modal is closed
            $('#createWarningModal').on('hidden.bs.modal', function () {
                $('#createWarningForm').trigger('reset');
                clearStudentSelection();
            });
        });
    </script>
@endpush
