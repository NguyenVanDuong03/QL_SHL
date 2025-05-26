@extends('layouts.studentAffairsDepartment')

@section('title', 'Tài khoản GV & SV')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Tài khoản GV & SV']]" />
@endsection

@push('styles')
    <style>
        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd #0d6efd #fff;
        }

        .nav-tabs .nav-link {
            color: #0d6efd;
        }
    </style>
@endpush

@section('main')
    <!-- Include the tab navigation -->
    @include('StudentAffairsDepartment.account.tabs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold d-none d-md-block">Tài khoản sinh viên</span>
                            <div class="d-flex align-items-end">
                                <div class="input-group me-2" style="width: 250px;">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm sinh viên..."
                                        id="studentSearch">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <form method="POST"
                                    action="{{ route('student-affairs-department.account.importStudent') }}"
                                    enctype="multipart/form-data" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <label for="studentExcelFile" class="btn btn-sm btn-success me-2 mb-0">
                                        <i class="fas fa-file-excel me-1"></i> Import Excel
                                        <input type="file" id="studentExcelFile" class="d-none" name="studentExcelFile"
                                            accept=".xlsx, .xls">
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 columns-acctount">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">STT</th>
                                        <th>MSSV</th>
                                        <th>Họ và tên</th>
                                        <th class="d-none d-md-table-cell">ngày sinh</th>
                                        <th>Giới tính</th>
                                        <th>Email</th>
                                        <th style="width: 150px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="studentTableBody">
                                    @if ($data['students']['total'] == 0)
                                        <tr>
                                            <td colspan="7" class="text-center">Không có dữ liệu sinh viên</td>
                                        </tr>
                                    @else
                                        @foreach ($data['students']['data'] as $student)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $student['student_code'] }}</td>
                                                <td>{{ $student['user']['name'] }}</td>
                                                <td class="d-none d-md-table-cell">{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}
                                                </td>
                                                <td>{{ $student['user']['gender'] }}</td>
                                                <td>{{ $student['user']['email'] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-show-student mb-1"
                                                        title="Xem chi tiết" data-id="{{ $student['id'] }}"
                                                        data-name="{{ $student['user']['name'] }}"
                                                        data-email="{{ $student['user']['email'] }}"
                                                        data-birth="{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}"
                                                        data-gender="{{ $student['user']['gender'] }}"
                                                        data-code="{{ $student['student_code'] }}"
                                                        data-position="{{ $student['position'] }}"
                                                        data-class="{{ $student['study_class']['name'] }}"
                                                        data-cohort="{{ $student['cohort']['name'] }}"
                                                        data-phone="{{ $student['user']['phone'] }}" data-bs-toggle="modal"
                                                        data-bs-target="#showModal">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning mb-1" title="Chỉnh sửa"
                                                        data-id="{{ $student['id'] }}"
                                                        data-userid="{{ $student['user']['id'] }}"
                                                        data-name="{{ $student['user']['name'] }}"
                                                        data-email="{{ $student['user']['email'] }}"
                                                        data-birth="{{ $student['user']['date_of_birth'] }}"
                                                        data-gender="{{ $student['user']['gender'] }}"
                                                        data-position="{{ $student['position'] }}"
                                                        data-classid="{{ $student['study_class_id'] }}"
                                                        data-cohortid="{{ $student['cohort_id'] }}"
                                                        data-phone="{{ $student['user']['phone'] }}"
                                                        data-current-page="{{ $data['students']['current_page'] }}"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger mb-1" title="Xóa"
                                                        data-id="{{ $student['id'] }}"
                                                        data-user-id="{{ $student['user_id'] }}"
                                                        data-current-page="{{ $data['students']['current_page'] }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <x-pagination.pagination :paginate="$data['students']" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- show teacher --}}
    <div class="modal fade auto-reset-modal" id="showModal" tabindex="-1" aria-labelledby="showModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Thông tin chi tiết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Họ và tên:</strong>
                            <span class="student-name"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Mã sinh viên</strong>
                            <span class="student-code"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Email</strong>
                            <span class="student-email"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày sinh:</strong>
                            <span class="student-birth"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Giới tính:</strong>
                            <span class="student-gender"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong>
                            <span class="student-phone"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Niên khóa:</strong>
                            <span class="student-cohort"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Lớp:</strong>
                            <span class="student-class"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Vai trò:</strong>
                            <span class="student-position"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quay lại</button>
                </div>
            </div>
        </div>
    </div>

    {{-- edit students --}}
    <div class="modal fade auto-reset-modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
        <div class="modal-dialog modal-lg"> <!-- Thêm modal-lg cho rộng -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin sinh viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editFrom">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="edituserId">
                        <input type="hidden" name="current_page" id="current_page">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="editName" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                                <div class="text-danger text-danger-error"></div>
                                @error('name')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                                <div class="text-danger text-danger-error"></div>
                                @error('email')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editBirth" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="editBirth" name="date_of_birth" required>
                                <div class="text-danger text-danger-error"></div>
                                @error('date_of_birth')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editPhone" class="form-label">Số điện thoại</label>
                                <input type="number" class="form-control" id="editPhone" name="phone" required>
                                <div class="text-danger text-danger-error"></div>
                                @error('phone')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editGender" class="form-label">Giới tính</label>
                                <select class="form-select" id="editGender" name="gender" required>
                                    <option value="" disabled>-- Chọn giới tính --</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editCohort" class="form-label">Niên khóa</label>
                                <select class="form-select" id="editCohort" name="cohort" required>
                                    <option value="" disabled>-- Chọn trình độ --</option>
                                    @foreach ($data['cohorts'] as $cohort)
                                        <option value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editClass" class="form-label">Lớp</label>
                                <select value="" class="form-select" id="editClass" name="study_class_id"
                                    data-selected="{{ $student['study_class_id'] ?? '' }}" required>
                                    <option disabled>-- Chọn lớp --</option>
                                </select>
                                <div class="text-danger text-danger-error"></div>
                                @error('study_class_id')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editPosition" class="form-label">Vai trò</label>
                                <select class="form-select" id="editPosition" name="position" required>
                                    <option value="" disabled>-- Chọn vai trò --</option>
                                    <option value="0">Sinh viên</option>
                                    <option value="1">Lớp trưởng</option>
                                    <option value="2">Lớp phó</option>
                                    <option value="3">Bí thư</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary btn-edit">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal delete --}}
    <div class="modal fade auto-reset-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xóa tài khoản giáo viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="current_page" id="deleteCurrentPage">
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa tài khoản sinh viên này không?</p>
                        <input type="hidden" name="id" id="deletestudentId">
                        <input type="hidden" name="user_id" id="deleteUserId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger btn-student-delete">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // $('.auto-reset-modal').on('hidden.bs.modal', function() {
            //     $(this).find('form')[0].reset();
            // });

            $('#studentExcelFile').on('change', function() {
                if (this.files.length > 0) {
                    $(this).closest('form').submit();
                }
            });

            $('.btn-show-student').on('click', function() {
                const student = $(this).data();
                $('.student-name').text(student.name ?? "---");
                $('.student-code').text(student.code ?? "---");
                $('.student-email').text(student.email ?? "---");
                $('.student-birth').text(student.birth ?? "---");
                $('.student-gender').text(student.gender ?? "---");
                $('.student-phone').text(student.phone ?? "---");
                $('.student-cohort').text(student.cohort ?? "---");
                $('.student-class').text(student.class ?? "---");

                if (student.position == 0)
                    $('.student-position').text('Sinh viên');
                else if (student.position == 1)
                    $('.student-position').text('Lớp trưởng');
                else if (student.position == 2)
                    $('.student-position').text('Lớp phó');
                else if (student.position == 3)
                    $('.student-position').text('Bí thư');
                else
                    $('.student-position').text('---');
            });

            function updateStudyClassSelect(cohortId, selectedClassId = null) {
                const studyClassSelect = $('#editClass');
                const studyClasses = @json($data['studyClasses']);

                studyClassSelect.empty();
                studyClassSelect.append('<option disabled selected>-- Chọn lớp --</option>');

                const matchedClasses = studyClasses.filter(sc => sc.cohort_id == cohortId);

                if (matchedClasses.length > 0) {
                    matchedClasses.forEach(function(studyClass) {
                        const isSelected = selectedClassId && studyClass.id == selectedClassId;
                        studyClassSelect.append(
                            `<option value="${studyClass.id}" ${isSelected ? 'selected' : ''}>${studyClass.name}</option>`
                        );
                    });
                } else {
                    studyClassSelect.append('<option disabled>Không có lớp nào cho niên khóa này</option>');
                }
            }

            // Khi chọn lại niên khóa
            $('#editCohort').on('change', function() {
                const selectedCohortId = $(this).val();
                updateStudyClassSelect(selectedCohortId);
            });

            // Khi mở modal
            $('#editModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget); // button được click

                const selectedCohortId = button.data('cohortid');
                const selectedClassId = button.data('classid');
                const userId = button.data('userid');
                const name = button.data('name');
                const email = button.data('email');
                const birth = button.data('birth');
                const phone = button.data('phone');
                const gender = button.data('gender');
                const position = button.data('position');
                const currentPage = button.data('current-page');
                const studentId = button.data('id');

                $('#editFrom').attr('action', `/student-affairs-department/account/student/${studentId}`);
                const cohortSelect = $('#editCohort');
                const classSelect = $('#editClass');
                $('#edituserId').val(userId);
                $('#editName').val(name);
                $('#editEmail').val(email);
                $('#editBirth').val(birth);
                $('#editPhone').val(phone);
                $('#editGender').val(gender);
                $('#editPosition').val(position);
                $('#current_page').val(currentPage);

                // Gán giá trị cho select cohort
                cohortSelect.val(selectedCohortId);

                // Load danh sách lớp theo cohort, chọn đúng class
                updateStudyClassSelect(selectedCohortId, selectedClassId);
            });

            function check(id, regex, message) {
                const input = $(id);
                const errorBox = input.next('.text-danger-error');

                if (input.val().trim() === '') {
                    errorBox.text('Yêu cầu nhập đầy đủ thông tin!').show();
                    input.addClass('is-invalid');
                    return false;
                } else if (regex != '0' && !regex.test(input.val())) {
                    errorBox.text(message).show();
                    input.addClass('is-invalid');
                    return false;
                }

                errorBox.text('').hide();
                input.removeClass('is-invalid');
                return true;
            }

            $('.btn-edit').on('click', function(event) {
                event.preventDefault();

                const nameValid = check('#editName', /.+/, 'Yêu cầu nhập họ và tên!');
                const emailValid = check('#editEmail', /^[^\s@]+@[^\s@]+\.[^\s@]+$/, 'Email không hợp lệ!');
                const birthValid = check('#editBirth', /.+/, 'Yêu cầu nhập ngày sinh!');
                const phoneValid = check('#editPhone', /^\d{10,11}$/,
                    'Số điện thoại phải từ 10 đến 11 chữ số!');
                const genderValid = check('#editGender', /.+/, 'Yêu cầu chọn giới tính!');
                const cohortValid = check('#editCohort', /.+/, 'Yêu cầu chọn niên khóa!');
                const classValid = check('#editClass', /.+/, 'Yêu cầu chọn lớp!');
                const positionValid = check('#editPosition', /.+/, 'Yêu cầu chọn vai trò!');

                if (nameValid && emailValid && birthValid && phoneValid && genderValid && cohortValid &&
                    classValid && positionValid) {
                    $('#editFrom').submit();
                }

            });

            $('#deleteModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const userId = button.data('user-id');
                const currentPage = button.data('current-page');

                $('#deleteForm').attr('action', `/student-affairs-department/account/student/${id}`);
                $('#deleteLecturerId').val(id);
                $('#deleteUserId').val(userId);
                $('#deleteCurrentPage').val(currentPage);
            });

        });
    </script>
@endpush
