@extends('layouts.studentAffairsDepartment')

@section('title', 'Tài khoản GV & SV')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Tài khoản GV & SV']]"/>
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
                                <form method="GET" action="{{ route('student-affairs-department.account.index') }}"
                                      class="input-group me-2" style="width: 250px;">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm giáo viên..."
                                           name="search" value="{{ request()->get('search') }}"
                                           id="teacherSearch">
                                    <button class="btn btn-outline-secondary btn-search-lecturer" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
{{--                                <form method="POST"--}}
{{--                                      action="{{ route('student-affairs-department.account.importLecturer') }}"--}}
{{--                                      enctype="multipart/form-data" class="d-inline">--}}
{{--                                    @csrf--}}
{{--                                    @method('POST')--}}
{{--                                    <label for="teacherExcelFile" class="btn btn-sm btn-success me-2 mb-0"--}}
{{--                                           title="Tạo tài khoản từ Excel">--}}
{{--                                        <i class="fas fa-file-excel me-1"></i> Import Excel--}}
{{--                                        <input type="file" id="teacherExcelFile" name="teacherExcelFile" class="d-none"--}}
{{--                                               accept=".xlsx, .xls">--}}
{{--                                    </label>--}}
{{--                                </form>--}}
                                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal"
                                        data-bs-target="#addTeacherModal" title="Thêm giáo viên mới">
                                    <i class="fas fa-plus"></i> Thêm mới
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 columns-acctount">
                                <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ và tên</th>
                                    {{--                                        <th class="d-none d-md-table-cell">Ngày sinh</th>--}}
                                    {{--                                        <th class="d-none d-md-table-cell">Giới tính</th>--}}
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 150px;">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody id="teacherTableBody">
                                @if ($data['getAllWithTrashed']['total'] == 0)
                                    <tr>
                                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @else
                                    @foreach ($data['getAllWithTrashed']['data'] as $lecturer)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $lecturer['user']['name'] }}</td>
                                            <td>{{ $lecturer['user']['email'] }}</td>
                                            @php
                                                $deleted = isset($lecturer['user']) && $lecturer['user']['deleted_at'] !== null;
                                            @endphp
                                            <td>
                                                @if ($deleted)
                                                    <span class="badge bg-danger">Đã xóa</span>
                                                @else
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @endif
                                            </td>
                                            <td class="btn-group gap-2">
                                                <button class="btn btn-sm btn-info btn-show-lecturer"
                                                        title="Xem chi tiết"
                                                        data-email="{{ $lecturer['user']['email'] }}"
                                                        data-name="{{ $lecturer['user']['name'] }}"
                                                        data-birth="{{ \Carbon\Carbon::parse($lecturer['user']['date_of_birth'])->format('d/m/Y') }}"
                                                        data-phone="{{ $lecturer['user']['phone'] }}"
                                                        data-gender="{{ $lecturer['user']['gender'] }}"
                                                        data-faculty="{{ $lecturer['faculty']['name'] }}"
                                                        data-department="{{ $lecturer['faculty']['department']['name'] }}"
                                                        data-title="{{ $lecturer['title'] }}"
                                                        data-position="{{ $lecturer['position'] }}"
                                                        data-current-page="{{ $data['lecturers']['current_page'] }}"
                                                        data-search="{{ request('search') }}"
                                                        data-bs-target="#showModal" data-bs-toggle="modal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if (!$deleted)
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
                                                            data-search="{{ request('search') }}"
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
                                                @else
                                                    <button class="btn btn-sm btn-secondary" title="Khôi phục"
                                                            data-id="{{ $lecturer['id'] }}"
                                                            data-user-id="{{ $lecturer['user_id'] }}"
                                                            data-current-page="{{ $data['lecturers']['current_page'] }}"
                                                            data-bs-toggle="modal" data-bs-target="#restoreModal">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <x-pagination.pagination :paginate="$data['getAllWithTrashed']"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Teacher Modal --}}
    <div class="modal fade auto-reset-modal" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTeacherModalLabel">Thêm giáo viên mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addTeacherForm" method="POST"
                      action="{{ route('student-affairs-department.account.createAccount') }}">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="type" value="1">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Email -->
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="text-danger text-danger-error"></div>
                                @error('email')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success btn-add-Teacher">Thêm</button>
                    </div>
                </form>
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
                        <input type="hidden" name="user_id" class="edituserId">
                        <input type="hidden" name="current_page">
                        <input type="hidden" name="search" class="search_keyword">
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
                                    <option value="" disabled>-- Chọn trình độ --</option>
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
                    <input type="hidden" name="user_id" class="deleteUserId">
                    <input type="hidden" name="current_page" class="current_page">
                    <input type="hidden" name="email" class="deleteEmail">
                    <input type="hidden" name="search" class="search_keyword">
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa tài khoản giáo viên này không?</p>
                        <input type="hidden" name="id" id="deleteLecturerId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger btn-lecturer-delete">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục giảng viên này?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="confirmRestoreBtn">Khôi phục</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.auto-reset-modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.faculty-info').addClass('d-none');
            });

            $('#teacherExcelFile').on('change', function () {
                if (this.files.length > 0) {
                    $(this).closest('form').submit();
                }
            });

            $('.btn-show-lecturer').on('click', function () {
                let lecturerData = $(this).data();
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

                faculties.forEach(function (faculty) {
                    if (faculty.department_id == departmentId) {
                        const selected = faculty.id == selectedFacultyId ? 'selected' : '';
                        facultySelect.append(
                            `<option value="${faculty.id}" ${selected}>${faculty.name}</option>`
                        );
                    }
                });

                facultySelect.prop('disabled', facultySelect.find('option').length <= 1);
            }

            $('#editModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const modal = $(this);

                // Lấy dữ liệu từ button
                let id = button.data('id');
                let userId = button.data('user-id');
                let name = button.data('name');
                let email = button.data('email');
                let birth = button.data('birth');
                let phone = button.data('phone');
                let gender = button.data('gender');
                let departmentId = button.data('department');
                let facultyId = button.data('faculty');
                let title = button.data('title');
                let position = button.data('position');
                let currentPage = button.data('current-page');
                let searchKeyword = button.data('search');

                // Gán vào form
                $('#editFrom').attr('action', `/student-affairs-department/account/lecturer/${id}`);
                $('.edituserId').val(userId);
                $('#editLecturerName').val(name);
                $('#editLecturerEmail').val(email);
                $('#editLecturerBirth').val(birth);
                $('#editLecturerPhone').val(phone);
                $('#editLecturerTitle').val(title);
                $('#editLecturerPosition').val(position);
                $('.current_page').val(currentPage);
                $('.search_keyword').val(searchKeyword);

                $('#editLecturerGender').val(gender);
                $('#editLecturerDepartment').val(departmentId);

                updateFacultySelect(departmentId, facultyId);
            });

            $('#editLecturerDepartment').on('change', function () {
                const departmentId = $(this).val();
                updateFacultySelect(departmentId);
            });

            $('.btn-lecturer-edit').on('click', function (e) {
                let userId = $('.edituserId').val();
                let name = $('#editLecturerName').val().trim();
                let email = $('#editLecturerEmail').val().trim();
                let birth = $('#editLecturerBirth').val();
                let phone = $('#editLecturerPhone').val().trim();
                let gender = $('#editLecturerGender').val();
                let facultyId = $('#editLecturerFaculty').val();
                let departmentId = $('#editLecturerDepartment').val();
                let title = $('#editLecturerTitle').val();
                let position = $('#editLecturerPosition').val();

                let checkName = checkValidate('#editLecturerName', 0, 'Tên không hợp lệ!');
                let checkEmail = checkValidate('#editLecturerEmail',
                    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 'Email không hợp lệ!');
                let checkBirth = checkValidate('#editLecturerBirth', 0, 'Ngày sinh không hợp lệ!');
                let checkPhone = checkValidate('#editLecturerPhone', /^[0-9]{10,11}$/,
                    'Số điện thoại không hợp lệ!');
                let checkGender = checkValidate('#editLecturerGender', 0, 'Vui lòng chọn giới tính!');
                let checkFaculty = checkValidate('#editLecturerFaculty', 0, 'Vui lòng chọn bộ môn!');
                let checkDepartment = checkValidate('#editLecturerDepartment', 0, 'Vui lòng chọn khoa!');
                let checkTitle = checkValidate('#editLecturerTitle', 0, 'Vui lòng chọn trình độ!');
                let checkPosition = checkValidate('#editLecturerPosition', 0, 'Vui lòng chọn chức vụ!');

                if (!checkName || !checkEmail || !checkBirth || !checkPhone || !checkGender || !
                    checkFaculty || !checkDepartment || !checkTitle || !checkPosition) {
                    e.preventDefault();
                    return;
                }

            })

            $('#deleteModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id = button.data('id');
                let userId = button.data('user-id');
                let currentPage = button.data('current-page');
                let searchKeyword = button.data('search');

                $('#deleteForm').attr('action', `/student-affairs-department/account/lecturer/${id}`);
                $('#deleteLecturerId').val(id);
                $('.deleteUserId').val(userId);
                $('#deleteCurrentPage').val(currentPage);
                $('.search_keyword').val(searchKeyword);
            });

            $('#restoreModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id = button.data('id');
                let userId = button.data('user-id');
                let currentPage = button.data('current-page');

                $('#confirmRestoreBtn').off('click').on('click', function () {
                    $.ajax({
                        url: `/student-affairs-department/account/lecturer/${id}/restore`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            user_id: userId,
                            current_page: currentPage
                        },
                        success: function (response) {
                            $('#restoreModal').modal('hide');
                            if (response.success) {
                                toastr.success(response.message);
                                // window.location.href = response.redirect;
                                setTimeout(function () {
                                    location.reload();
                                }, 5000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr);
                            let errorMessage = xhr.responseJSON?.message || 'Lỗi khi khôi phục tài khoản. Vui lòng thử lại sau.';
                            toastr.error(errorMessage);
                        }
                    });
                });
            });
        });
    </script>
@endpush
