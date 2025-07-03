@extends('layouts.teacher')

@section('title', 'Thông tin lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb
        :links="[['label' => 'Lớp học', 'url' => 'teacher.class.index'], ['label' => 'Thông tin lớp học']]"/>
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
            left: 90px;
            transform: translateY(-100%);
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            min-width: 200px;
        "
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid py-4">
        <!-- Header with navigation and actions -->
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('teacher.class.index') }}"
               class="btn btn-outline-secondary btn-sm mb-2">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>

            <div class="d-flex align-items-center gap-2">
                <!-- Search -->
                <form class="input-group" method="GET"
                      action="{{ route('teacher.class.infoStudent',$data['classInfo']->id) }}"
                      style="max-width: 250px;">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sinh viên"
                           aria-label="Search" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <button class="btn btn-info w-75" data-bs-toggle="modal" data-bs-target="#ClassModal">
                    <i class="fas fa-users me-2"></i>Cán sự lớp
                </button>
            </div>
        </div>

        <!-- Students table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách sinh viên {{ $data['classInfo']->name }}</h5>
                    <span class="badge bg-primary">{{ $data['students']['total'] }} sinh viên</span>
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
                                                <i class="fas fa-sticky-note text-danger note-icon"
                                                   data-note="{{ $student['note'] }}"></i>
                                            @endif
                                        </div>
                                        <small class="text-muted">ID: {{ $student['student_code'] }}</small>
                                        <div class="note-tooltip tooltip-note">
                                            <small class="text-primary"><strong>Ghi chú:</strong> <span
                                                    class="note-text"></span></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">{{ $student['user']['email'] }}</td>
                                <td class="d-none d-md-table-cell">
                                        <span
                                            class="badge {{ $student['user']['gender'] == 'Nam' ? 'bg-info' : 'bg-pink' }}">
                                            {{ $student['user']['gender'] == 'Nam' ? 'Nam' : 'Nữ' }}
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
                                                data-student-gender="{{ $student['user']['gender'] == 'Nam' ? 'Nam' : 'Nữ' }}"
                                                data-student-dob="{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}"
                                                data-student-phone="{{ $student['user']['phone'] }}"
                                                data-student-position="{{ $position['text'] }}"
                                                data-note="{{ $student['note'] ?? '' }}"
                                                title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
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
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-4">
            <x-pagination.pagination :paginate="$data['students']"/>
        </div>
    </div>

    <!-- View Student Modal -->
    <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStudentModalLabel">
                        <i class="fas fa-user me-2"></i>Thông tin sinh viên
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form-save-note">
                    @csrf
                    @method('PATCH')
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
                            <div>
                                <label class="form-label fw-bold">Ghi chú:</label>
                                {{--                                <input type="hidden" class="student_id" name="id">--}}
                                <textarea name="note" rows="3"
                                          class="form-control resize-none textarea-note"
                                          placeholder="Không có ghi chú..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Ghi chú
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Student Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel"
         aria-hidden="true">
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
                <form id="classOfficersForm" method="POST" action="{{ route('teacher.class.updateOfficers') }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="study_class_id" class="study_class_id"
                               value="{{ $data['studyClassId'] }}">
                        <div class="mb-3">
                            <label for="classLeader" class="form-label">Lớp trưởng</label>
                            <select class="form-select" id="classLeader" name="classLeader" required>
                                <option value="">-- Chọn lớp trưởng --</option>
                                @foreach ($data['students']['data'] as $student)
                                    <option
                                        value="{{ $student['id'] }}" {{ $student['position'] == 1 ? 'selected' : '' }}>
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
                                    <option
                                        value="{{ $student['id'] }}" {{ $student['position'] == 2 ? 'selected' : '' }}>
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
                                    <option
                                        value="{{ $student['id'] }}" {{ $student['position'] == 3 ? 'selected' : '' }}>
                                        {{ $student['user']['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="saveClassOfficersBtn">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
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
            $('.note-icon').click(function (e) {
                e.stopPropagation();
                const $this = $(this);
                const $tooltip = $this.closest('.position-relative').find('.note-tooltip');
                const $noteText = $tooltip.find('.note-text');
                const noteData = $this.data('note');

                $('.note-tooltip').not($tooltip).fadeOut(200);

                if ($tooltip.is(':visible')) {
                    $tooltip.fadeOut(200);
                } else {
                    $noteText.text(noteData);
                    $tooltip.fadeIn(200);
                }
            });

            $(document).click(function (e) {
                if (!$(e.target).closest('.note-icon, .note-tooltip').length) {
                    $('.note-tooltip').fadeOut(200);
                }
            });

            $('#viewStudentModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                $('#viewStudentName').text(button.data('student-name'));
                $('#viewStudentEmail').text(button.data('student-email'));
                $('#viewStudentGender').text(button.data('student-gender'));
                $('#viewStudentDob').text(button.data('student-dob'));
                $('#viewStudentPhone').text(button.data('student-phone'));
                $('#viewStudentPosition').text(button.data('student-position'));
                $('.student_id').val(button.data('student-id'));
                $('.textarea-note').val(button.data('note') ?? '');

                $('#form-save-note').attr('action', `/teacher/class/note/${button.data('student-id')}`);
            });

            const allStudents = @json($data['students']['data']);

            function renderSelectOptions(selectedIds, currentSelectId) {
                const select = $('#' + currentSelectId);
                const currentValue = select.val();

                select.empty();
                select.append(`<option value="">-- Chọn --</option>`);

                allStudents.forEach(student => {
                    if (!selectedIds.includes(student.id.toString()) || student.id.toString() === currentValue) {
                        const selectedAttr = student.id.toString() === currentValue ? 'selected' : '';
                        select.append(`<option value="${student.id}" ${selectedAttr}>${student.user.name}</option>`);
                    }
                });
            }

            function updateAllSelects(changedSelectId = null) {
                const selectedIds = [];

                ['classLeader', 'classViceLeader', 'classSecretary'].forEach(selectId => {
                    const val = $('#' + selectId).val();
                    if (val) selectedIds.push(val);
                });

                ['classLeader', 'classViceLeader', 'classSecretary'].forEach(selectId => {
                    if (selectId !== changedSelectId) {
                        renderSelectOptions(selectedIds, selectId);
                    }
                });
            }

            $('#classLeader, #classViceLeader, #classSecretary').on('change', function () {
                const changedId = $(this).attr('id');
                updateAllSelects(changedId);
            });

            $('#ClassModal').on('show.bs.modal', function () {
                updateAllSelects();
            });

            updateAllSelects();
        });
    </script>
@endpush
