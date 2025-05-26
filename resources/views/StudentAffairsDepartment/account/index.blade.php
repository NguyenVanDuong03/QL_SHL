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
                            <span class="text-primary fw-bold d-none d-md-block">Tài khoản giáo viên</span>
                            <div class="d-flex align-items-end">
                                <div class="input-group me-2" style="width: 250px;">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm giáo viên..."
                                        id="teacherSearch">
                                    <button class="btn btn-outline-secondary btn-search-lecturer" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <form method="POST"
                                    action="{{ route('student-affairs-department.account.importLecturer') }}"
                                    enctype="multipart/form-data" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <label for="teacherExcelFile" class="btn btn-sm btn-success me-2 mb-0">
                                        <i class="fas fa-file-excel me-1"></i> Import Excel
                                        <input type="file" id="teacherExcelFile" name="teacherExcelFile" class="d-none"
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
                                        <th>Họ và tên</th>
                                        <th class="d-none d-md-table-cell">Ngày sinh</th>
                                        <th class="d-none d-md-table-cell">Giới tính</th>
                                        <th>Email</th>
                                        <th style="width: 150px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="teacherTableBody">
                                    @if ($data['lecturers']['total'] == 0)
                                        <tr>
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @else
                                        @foreach ($data['lecturers']['data'] as $lecturer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $lecturer['user']['name'] }}</td>
                                                <td class="d-none d-md-table-cell">
                                                    {{ \Carbon\Carbon::parse($lecturer['user']['date_of_birth'])->format('d/m/Y') }}
                                                </td>
                                                <td class="d-none d-md-table-cell">{{ $lecturer['user']['gender'] }}</td>
                                                <td>{{ $lecturer['user']['email'] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info btn-show-lecturer"
                                                        title="Xem chi tiết" data-email="{{ $lecturer['user']['email'] }}"
                                                        data-name="{{ $lecturer['user']['name'] }}"
                                                        data-birth="{{ \Carbon\Carbon::parse($lecturer['user']['date_of_birth'])->format('d/m/Y') }}"
                                                        data-phone="{{ $lecturer['user']['phone'] }}"
                                                        data-gender="{{ $lecturer['user']['gender'] }}"
                                                        data-faculty="{{ $lecturer['faculty']['name'] }}"
                                                        data-department="{{ $lecturer['faculty']['department']['name'] }}"
                                                        data-title="{{ $lecturer['title'] }}"
                                                        data-position="{{ $lecturer['position'] }}"
                                                        data-bs-target="#showModal" data-bs-toggle="modal">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning" title="Chỉnh sửa"
                                                        data-id="{{ $lecturer['id'] }}"
                                                        data-user-id="{{ $lecturer['user_id'] }}"
                                                        data-email="{{ $lecturer['user']['email'] }}"
                                                        data-name="{{ $lecturer['user']['name'] }}"
                                                        data-birth="{{ $lecturer['user']['date_of_birth'] }}"
                                                        data-phone="{{ $lecturer['user']['phone'] }}"
                                                        data-gender="{{ $lecturer['user']['gender'] }}"
                                                        data-faculty="{{ $lecturer['faculty']['id'] }}"
                                                        data-department="{{ $lecturer['faculty']['department']['id'] }}"
                                                        data-title="{{ $lecturer['title'] }}"
                                                        data-position="{{ $lecturer['position'] }}"
                                                        data-current-page="{{ $data['lecturers']['current_page'] }}"
                                                        data-bs-target="#editModal" data-bs-toggle="modal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-danger" title="Xóa"
                                                        data-id="{{ $lecturer['id'] }}"
                                                        data-user-id="{{ $lecturer['user_id'] }}"
                                                        data-current-page="{{ $data['lecturers']['current_page'] }}"
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
                        <x-pagination.pagination :paginate="$data['lecturers']" />
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
                            <span class="lecturer-name"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Email</strong>
                            <span class="lecturer-email"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Ngày sinh:</strong>
                            <span class="lecturer-birth"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Giới tính:</strong>
                            <span class="lecturer-gender"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong>
                            <span class="lecturer-phone"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Trình độ:</strong>
                            <span class="lecturer-title"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Chức vụ:</strong>
                            <span class="lecturer-position"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Bộ môn:</strong>
                            <span class="lecturer-faculty"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Khoa:</strong>
                            <span class="lecturer-deparment"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quay lại</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal edit --}}
    <div class="modal fade auto-reset-modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
        <div class="modal-dialog modal-lg"> <!-- Thêm modal-lg cho rộng -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin giáo viên</h5>
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
                                <label for="editLecturerName" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="editLecturerName" name="name"
                                    required>
                                <div class="text-danger text-danger-error"></div>
                                @error('name')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editLecturerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editLecturerEmail" name="email"
                                    required>
                                <div class="text-danger text-danger-error"></div>
                                @error('email')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editLecturerBirth" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="editLecturerBirth" name="date_of_birth"
                                    required>
                                <div class="text-danger text-danger-error"></div>
                                @error('date_of_birth')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editLecturerPhone" class="form-label">Số điện thoại</label>
                                <input type="number" class="form-control" id="editLecturerPhone" name="phone"
                                    required>
                                <div class="text-danger text-danger-error"></div>
                                @error('phone')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editLecturerGender" class="form-label">Giới tính</label>
                                <select class="form-select" id="editLecturerGender" name="gender" required>
                                    <option value="" disabled>-- Chọn giới tính --</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editLecturerTitle" class="form-label">Trình độ</label>
                                <select class="form-select" id="editLecturerTitle" name="title" required>
                                    <option  value="" disabled>-- Chọn trình độ --</option>
                                    <option value="Giáo sư">Giáo sư</option>
                                    <option value="Phó Giáo sư">Phó Giáo sư</option>
                                    <option value="Tiến sĩ">Tiến sĩ</option>
                                    <option value="Thạc sĩ">Thạc sĩ</option>
                                    <option value="Cử nhân">Cử nhân</option>
                                    <option value="Kỹ sư">Kỹ sư</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editLecturerDepartment" class="form-label">Khoa</label>
                                <select class="form-select" id="editLecturerDepartment" name="department_id" required>
                                    <option value="" disabled>-- Chọn khoa --</option>
                                    @foreach ($data['departments'] as $department)
                                        <option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editLecturerFaculty" class="form-label">Bộ môn</label>
                                <select class="form-select" id="editLecturerFaculty" name="faculty_id"
                                    data-selected="{{ $lecturer['faculty_id'] ?? '' }}" required>
                                    <option value="" disabled>-- Chọn bộ môn --</option>
                                </select>
                                <div class="text-danger text-danger-error"></div>
                                @error('faculty_id')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="editLecturerPosition" class="form-label">Chức vụ</label>
                                <select class="form-select" id="editLecturerPosition" name="position" required>
                                    <option value="" disabled>-- Chọn chức vụ --</option>
                                    <option value="Trưởng khoa">Trưởng khoa</option>
                                    <option value="Phó trưởng khoa">Phó trưởng khoa</option>
                                    <option value="Trưởng bộ môn">Trưởng bộ môn</option>
                                    <option value="Phó trưởng bộ môn">Phó trưởng bộ môn</option>
                                    <option value="Giảng viên">Giảng viên</option>
                                    <option value="Trợ giảng">Trợ giảng</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary btn-lecturer-edit">Lưu thay đổi</button>
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
                        <p>Bạn có chắc chắn muốn xóa tài khoản giáo viên này không?</p>
                        <input type="hidden" name="id" id="deleteLecturerId">
                        <input type="hidden" name="user_id" id="deleteUserId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger btn-lecturer-delete">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.auto-reset-modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.faculty-info').addClass('d-none');
            });

            $('#teacherExcelFile').on('change', function() {
                if (this.files.length > 0) {
                    $(this).closest('form').submit();
                }
            });

            $('.btn-show-lecturer').on('click', function() {
                const lecturerData = $(this).data();
                $('.lecturer-name').text(lecturerData.name ?? "---");
                $('.lecturer-email').text(lecturerData.email ?? "---");
                $('.lecturer-birth').text(lecturerData.birth ?? "---");
                $('.lecturer-gender').text(lecturerData.gender ?? "---");
                $('.lecturer-phone').text(lecturerData.phone ?? "---");
                $('.lecturer-faculty').text(lecturerData.faculty ?? "---");
                $('.lecturer-deparment').text(lecturerData.department ?? "---");
                $('.lecturer-position').text(lecturerData.position ?? "---");
                $('.lecturer-title').text(lecturerData.title ?? "---");
            });

            function updateFacultySelect(departmentId, selectedFacultyId = null) {
                const faculties = @json($data['faculties']);
                const facultySelect = $('#editLecturerFaculty');

                facultySelect.empty();
                facultySelect.append('<option disabled selected>-- Chọn bộ môn --</option>');

                faculties.forEach(function(faculty) {
                    if (faculty.department_id == departmentId) {
                        const selected = faculty.id == selectedFacultyId ? 'selected' : '';
                        facultySelect.append(
                            `<option value="${faculty.id}" ${selected}>${faculty.name}</option>`
                        );
                    }
                });

                facultySelect.prop('disabled', facultySelect.find('option').length <= 1);
            }

            $('#editModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const modal = $(this);

                // Lấy dữ liệu từ button
                const id = button.data('id');
                const userId = button.data('user-id');
                const name = button.data('name');
                const email = button.data('email');
                const birth = button.data('birth');
                const phone = button.data('phone');
                const gender = button.data('gender');
                const departmentId = button.data('department');
                const facultyId = button.data('faculty');
                const title = button.data('title');
                const position = button.data('position');
                const currentPage = button.data('current-page');

                // Gán vào form
                $('#editFrom').attr('action', `/student-affairs-department/account/lecturer/${id}`);
                modal.find('#edituserId').val(userId);
                modal.find('#editLecturerName').val(name);
                modal.find('#editLecturerEmail').val(email);
                modal.find('#editLecturerBirth').val(birth);
                modal.find('#editLecturerPhone').val(phone);
                modal.find('#editLecturerTitle').val(title);
                modal.find('#editLecturerPosition').val(position);
                modal.find('#current_page').val(currentPage);

                modal.find('#editLecturerGender').val(gender);
                modal.find('#editLecturerDepartment').val(departmentId);

                updateFacultySelect(departmentId, facultyId);
            });

            $('#editLecturerDepartment').on('change', function() {
                const departmentId = $(this).val();
                updateFacultySelect(departmentId);
            });


            $('#deleteModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const userId = button.data('user-id');
                const currentPage = button.data('current-page');

                $('#deleteForm').attr('action', `/student-affairs-department/account/lecturer/${id}`);
                $('#deleteLecturerId').val(id);
                $('#deleteUserId').val(userId);
                $('#deleteCurrentPage').val(currentPage);
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

            $('.btn-lecturer-edit').on('click', function(e) {
                const userId = $('#edituserId').val();
                const name = $('#editLecturerName').val().trim();
                const email = $('#editLecturerEmail').val().trim();
                const birth = $('#editLecturerBirth').val();
                const phone = $('#editLecturerPhone').val().trim();
                const gender = $('#editLecturerGender').val();
                const facultyId = $('#editLecturerFaculty').val();
                const departmentId = $('#editLecturerDepartment').val();
                const title = $('#editLecturerTitle').val();
                const position = $('#editLecturerPosition').val();

                const checkName = check('#editLecturerName', 0, 'Tên không hợp lệ!');
                const checkEmail = check('#editLecturerEmail',
                    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 'Email không hợp lệ!');
                const checkBirth = check('#editLecturerBirth', 0, 'Ngày sinh không hợp lệ!');
                const checkPhone = check('#editLecturerPhone', /^[0-9]{10,11}$/,
                    'Số điện thoại không hợp lệ!');
                const checkGender = check('#editLecturerGender', 0, 'Vui lòng chọn giới tính!');
                const checkFaculty = check('#editLecturerFaculty', 0, 'Vui lòng chọn bộ môn!');
                const checkDepartment = check('#editLecturerDepartment', 0, 'Vui lòng chọn khoa!');
                const checkTitle = check('#editLecturerTitle', 0, 'Vui lòng chọn trình độ!');
                const checkPosition = check('#editLecturerPosition', 0, 'Vui lòng chọn chức vụ!');

                if (!checkName || !checkEmail || !checkBirth || !checkPhone || !checkGender || !
                    checkFaculty || !checkDepartment || !checkTitle || !checkPosition) {
                    e.preventDefault();
                    return;
                }

            })

            // $('#teacherSearch').on('input', function() {
            //     const searchTerm = $(this).val().toLowerCase();
            //     $('#teacherTableBody tr').each(function() {
            //         const name = $(this).find('td:nth-child(2)').text().toLowerCase();
            //         $(this).toggle(name.includes(searchTerm));
            //     });
            // });

            // function searchTeachers() {
            //     let searchTerm = $('#teacherSearch').val().toLowerCase();

            //     $.ajax({
            //         url: '{{ route('student-affairs-department.account.index') }}',
            //         type: 'GET',
            //         data: {
            //             search: searchTerm
            //         },
            //         success: function(response) {
            //             const lecturers = response.lecturers;
            //             const currentPage = response.current_page || 1;
            //             const tbody = $('#teacherTableBody');
            //             tbody.empty();

            //             if (lecturers.length === 0) {
            //                 tbody.append(
            //                     '<tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>'
            //                     );
            //             } else {
            //                 lecturers.forEach((lecturer, index) => {
            //                     const user = lecturer.user;
            //                     const faculty = lecturer.faculty;
            //                     const department = faculty?.department || {};
            //                     const birthDate = new Date(user.date_of_birth);
            //                     const formattedBirth = birthDate.toLocaleDateString('vi-VN', {
            //                         day: '2-digit',
            //                         month: '2-digit',
            //                         year: 'numeric'
            //                     });

            //                     tbody.append(`
        //         <tr>
        //             <td>${index + 1}</td>
        //             <td>${user.name}</td>
        //             <td class="d-none d-md-table-cell">${formattedBirth}</td>
        //             <td class="d-none d-md-table-cell">${user.gender}</td>
        //             <td>${user.email}</td>
        //             <td>
        //                 <button class="btn btn-sm btn-info btn-show-lecturer"
        //                     title="Xem chi tiết"
        //                     data-email="${user.email}"
        //                     data-name="${user.name}"
        //                     data-birth="${formattedBirth}"
        //                     data-phone="${user.phone}"
        //                     data-gender="${user.gender}"
        //                     data-faculty="${faculty?.name || ''}"
        //                     data-department="${department?.name || ''}"
        //                     data-title="${lecturer.title || ''}"
        //                     data-position="${lecturer.position || ''}"
        //                     data-bs-target="#showModal" data-bs-toggle="modal">
        //                     <i class="fas fa-eye"></i>
        //                 </button>
        //                 <button class="btn btn-sm btn-warning"
        //                     title="Chỉnh sửa"
        //                     data-id="${lecturer.id}"
        //                     data-user-id="${lecturer.user_id}"
        //                     data-email="${user.email}"
        //                     data-name="${user.name}"
        //                     data-birth="${user.date_of_birth}"
        //                     data-phone="${user.phone}"
        //                     data-gender="${user.gender}"
        //                     data-faculty="${faculty?.id || ''}"
        //                     data-department="${department?.id || ''}"
        //                     data-title="${lecturer.title || ''}"
        //                     data-position="${lecturer.position || ''}"
        //                     data-current-page="${currentPage}"
        //                     data-bs-target="#editModal" data-bs-toggle="modal">
        //                     <i class="fas fa-edit"></i>
        //                 </button>
        //                 <button class="btn btn-sm btn-danger"
        //                     title="Xóa"
        //                     data-id="${lecturer.id}"
        //                     data-user-id="${lecturer.user_id}"
        //                     data-bs-toggle="modal" data-bs-target="#deleteModal">
        //                     <i class="fas fa-trash"></i>
        //                 </button>
        //             </td>
        //         </tr>
        //     `);
            //                 });
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error('Lỗi khi tìm kiếm giảng viên:', xhr);
            //         }
            //     });

            // }

            // $('.btn-search-lecturer').on('click', function() {
            //     searchTeachers();
            // });

            // $('#teacherSearch').on('keydown', function(e) {
            //     if (e.key === 'Enter') {
            //         e.preventDefault();
            //         searchTeachers();
            //     }
            // });

        });
    </script>
@endpush
