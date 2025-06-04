@extends('layouts.teacher')

@section('title', 'Thông tin lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Lớp học', 'url' => 'teacher.class.index'], ['label' => 'Thông tin lớp học']]" />
@endsection

@push('styles')
    <style>
        .resize-none {
            resize: none;
        }

         .bg-pink {
             background-color: #e91e63 !important;
         }

        .tooltip-note {
            display: none;
            position: absolute;
            top: -5px;
            left: 60%;
            transform: translateY(-100%);
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            min-width: 200px;"
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid py-4">
        <!-- Header with navigation and actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-md-flex align-items-center gap-3">
                <a href="{{ route('teacher.class.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <h4 class="mb-0">Lớp {{ $data['classInfo']->name }}</h4>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Search -->
                <form class="input-group" method="GET" action="{{ route('teacher.class.infoStudent',$data['classInfo']->id) }}" style="max-width: 250px;">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sinh viên" aria-label="Search" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- Action buttons -->
{{--                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal">--}}
{{--                    <i class="fas fa-plus me-2"></i>Thêm sinh viên--}}
{{--                </button>--}}
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#noteModal">
                    <i class="fas fa-sticky-note me-2"></i>Sinh viên cần theo dõi
                </button>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ClassModal">
                    <i class="fas fa-users me-2"></i>Cán sự lớp
                </button>
            </div>
        </div>

        <!-- Students table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách sinh viên</h5>
                    <span class="badge bg-primary">{{ count($data['students']['data']) }} sinh viên</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên sinh viên</th>
                            <th scope="col" class="d-none d-md-table-cell">Email</th>
                            <th scope="col" class="d-none d-md-table-cell">Giới tính</th>
                            <th scope="col" class="d-none d-md-table-cell">Ngày sinh</th>
                            <th scope="col" class="d-none d-md-table-cell">Số điện thoại</th>
                            <th scope="col">Chức vụ</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['students']['data'] as $index => $student)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <div class="position-relative">
                                        <div class="fw-medium">{{ $student['user']['name'] }}
                                            @if(!empty($student['note']))
                                                <i class="fas fa-sticky-note text-secondary note-icon" data-note="{{ $student['note'] }}"></i>
                                           @endif
                                        </div>
                                        <small class="text-muted">ID: {{ $student['student_code'] }}</small>
                                        <div class="note-tooltip tooltip-note">
                                            <small class="text-primary"><strong>Ghi chú:</strong> <span class="note-text"></span></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">{{ $student['user']['email'] }}</td>
                                <td class="d-none d-md-table-cell">
                                        <span class="badge {{ $student['user']['gender'] == 0 ? 'bg-info' : 'bg-pink' }}">
                                            {{ $student['user']['gender'] == 0 ? 'Nam' : 'Nữ' }}
                                        </span>
                                </td>
                                <td class="d-none d-md-table-cell">{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}</td>
                                <td class="d-none d-md-table-cell">{{ $student['user']['phone'] }}</td>
                                <td>
                                    @php
                                        $positions = [
                                            0 => ['text' => 'Sinh viên', 'class' => 'bg-secondary'],
                                            1 => ['text' => 'Lớp trưởng', 'class' => 'bg-danger'],
                                            2 => ['text' => 'Lớp phó', 'class' => 'bg-warning'],
                                            3 => ['text' => 'Bí thư', 'class' => 'bg-success']
                                        ];
                                        $position = $positions[$student['position']] ?? $positions[0];
                                    @endphp
                                    <span class="badge {{ $position['class'] }}">{{ $position['text'] }}</span>
                                </td>
                                <td>
                                    <div class="btn-group gap-2" role="group">
                                        <button type="button"
                                                class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewStudentModal"
                                                data-student-id="{{ $student['id'] }}"
                                                data-student-name="{{ $student['user']['name'] }}"
                                                data-student-email="{{ $student['user']['email'] }}"
                                                data-student-gender="{{ $student['user']['gender'] == 0 ? 'Nam' : 'Nữ' }}"
                                                data-student-dob="{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}"
                                                data-student-phone="{{ $student['user']['phone'] }}"
                                                data-student-position="{{ $position['text'] }}"
                                                title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteStudentModal"
                                                data-student-id="{{ $student['id'] }}"
                                                data-student-name="{{ $student['user']['name'] }}"
                                                title="Xóa khỏi lớp">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @if(count($data['students']['data']) == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không tìm thấy sinh viên có thông tin "{{ request('search') }}"</p>
{{--                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">--}}
{{--                                <i class="fas fa-plus me-2"></i>Thêm sinh viên đầu tiên--}}
{{--                            </button>--}}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-4">
            <x-pagination.pagination :paginate="$data['students']" />
        </div>
    </div>

    <!-- Add Student Modal -->
{{--    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="addStudentModalLabel">--}}
{{--                        <i class="fas fa-user-plus me-2"></i>Thêm sinh viên vào lớp--}}
{{--                    </h5>--}}
{{--                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <form id="addStudentForm">--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="studentEmail" class="form-label">Email sinh viên</label>--}}
{{--                            <input type="email" class="form-control" id="studentEmail" name="studentEmail" required>--}}
{{--                            <div class="form-text">Nhập email của sinh viên để thêm vào lớp</div>--}}
{{--                            <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>--}}
{{--                        </div>--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="studentPosition" class="form-label">Chức vụ</label>--}}
{{--                            <select class="form-select" id="studentPosition" name="studentPosition" required>--}}
{{--                                <option value="0">Sinh viên</option>--}}
{{--                                <option value="1">Lớp trưởng</option>--}}
{{--                                <option value="2">Lớp phó</option>--}}
{{--                                <option value="3">Bí thư</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>--}}
{{--                    <button type="button" class="btn btn-success" id="saveStudentBtn">--}}
{{--                        <i class="fas fa-plus me-2"></i>Thêm sinh viên--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!-- View Student Modal -->
    <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStudentModalLabel">
                        <i class="fas fa-user me-2"></i>Thông tin sinh viên
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên:</label>
                                <p id="viewStudentName" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p id="viewStudentEmail" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Giới tính:</label>
                                <p id="viewStudentGender" class="text-muted mb-0"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày sinh:</label>
                                <p id="viewStudentDob" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại:</label>
                                <p id="viewStudentPhone" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chức vụ:</label>
                                <p id="viewStudentPosition" class="text-muted mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Student Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteStudentModalLabel">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Cảnh báo!</strong> Hành động này sẽ xóa sinh viên khỏi lớp học.
                    </div>
                    <p>Bạn có chắc chắn muốn xóa sinh viên <strong id="deleteStudentName"></strong> khỏi lớp không?</p>
                    <input type="hidden" id="deleteStudentId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteStudentBtn">
                        <i class="fas fa-trash me-2"></i>Xóa khỏi lớp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Note Class Modal (existing) -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">
                        <i class="fas fa-sticky-note me-2"></i>Sinh viên cần theo dõi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <form id="studentNotesForm">
                        <div class="student-notes-container" style="height: 450px; overflow-y: auto; padding: 1rem;">
                            @foreach ($data['students']['data'] as $index => $student)
                                <div class="student-note-item mb-4 border-bottom pb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="note-{{ $student['id'] }}" class="form-label mb-0 fw-bold">{{ $student['user']['name'] }}</label>
                                        <span class="badge bg-primary">ID: {{ $student['id'] }}</span>
                                    </div>
                                    <textarea name="note-{{ $student['id'] }}" id="note-{{ $student['id'] }}" class="form-control resize-none" rows="3" placeholder="Nhập ghi chú cho sinh viên này..."></textarea>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <span class="me-auto text-muted small">Tổng số: {{ count($data['students']['data']) }} sinh viên</span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveNotesBtn">
                        <i class="fas fa-save me-2"></i>Lưu ghi chú
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Modal (existing) -->
    <div class="modal fade" id="ClassModal" tabindex="-1" aria-labelledby="ClassModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ClassModalLabel">
                        <i class="fas fa-users me-2"></i>Cán sự lớp
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="classOfficersForm">
                        <div class="mb-3">
                            <label for="classLeader" class="form-label">Lớp trưởng</label>
                            <select class="form-select" id="classLeader" name="classLeader" required>
                                <option value="">-- Chọn lớp trưởng --</option>
                                @foreach ($data['students']['data'] as $student)
                                    <option value="{{ $student['id'] }}" {{ $student['position'] == 1 ? 'selected' : '' }}>
                                        {{ $student['user']['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="classViceLeader" class="form-label">Lớp phó</label>
                            <select class="form-select" id="classViceLeader" name="classViceLeader" required>
                                <option value="">-- Chọn lớp phó --</option>
                                @foreach ($data['students']['data'] as $student)
                                    <option value="{{ $student['id'] }}" {{ $student['position'] == 2 ? 'selected' : '' }}>
                                        {{ $student['user']['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="classSecretary" class="form-label">Bí thư</label>
                            <select class="form-select" id="classSecretary" name="classSecretary" required>
                                <option value="">-- Chọn bí thư --</option>
                                @foreach ($data['students']['data'] as $student)
                                    <option value="{{ $student['id'] }}" {{ $student['position'] == 3 ? 'selected' : '' }}>
                                        {{ $student['user']['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveClassOfficersBtn">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.note-icon').click(function(e) {
                e.stopPropagation(); // Ngăn click lan ra ngoài
                const $this = $(this);
                const $tooltip = $this.closest('.position-relative').find('.note-tooltip');
                const $noteText = $tooltip.find('.note-text');
                const noteData = $this.data('note');

                // Ẩn tất cả tooltip khác
                $('.note-tooltip').not($tooltip).fadeOut(200);

                // Toggle tooltip hiện tại
                if ($tooltip.is(':visible')) {
                    $tooltip.fadeOut(200);
                } else {
                    $noteText.text(noteData);
                    $tooltip.fadeIn(200);
                }
            });

            // Ẩn tooltip khi click ra ngoài
            $(document).click(function(e) {
                if (!$(e.target).closest('.note-icon, .note-tooltip').length) {
                    $('.note-tooltip').fadeOut(200);
                }
            });

            // View Student Modal
            $('#viewStudentModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                $('#viewStudentName').text(button.data('student-name'));
                $('#viewStudentEmail').text(button.data('student-email'));
                $('#viewStudentGender').text(button.data('student-gender'));
                $('#viewStudentDob').text(button.data('student-dob'));
                $('#viewStudentPhone').text(button.data('student-phone'));
                $('#viewStudentPosition').text(button.data('student-position'));
            });

            // Delete Student Modal
            $('#deleteStudentModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                $('#deleteStudentId').val(button.data('student-id'));
                $('#deleteStudentName').text(button.data('student-name'));
            });

            // Add Student
            $('#saveStudentBtn').click(function() {
                const formData = $('#addStudentForm').serialize();
                console.log('Adding student...', formData);
                $('#addStudentModal').modal('hide');
            });

            // Confirm Delete Student
            $('#confirmDeleteStudentBtn').click(function() {
                const studentId = $('#deleteStudentId').val();
                console.log('Deleting student with ID:', studentId);
                $('#deleteStudentModal').modal('hide');
            });

            // Save Notes
            $('#saveNotesBtn').click(function() {
                const formData = $('#studentNotesForm').serialize();
                console.log('Saving notes...', formData);
                $('#noteModal').modal('hide');
            });

            // Save Class Officers
            $('#saveClassOfficersBtn').click(function() {
                const formData = $('#classOfficersForm').serialize();
                console.log('Saving class officers...', formData);
                $('#ClassModal').modal('hide');
            });
        });
    </script>
@endpush
