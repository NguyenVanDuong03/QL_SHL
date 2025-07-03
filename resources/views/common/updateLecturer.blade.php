@php
    $lecturer = auth()->user()->lecturer ?? null;
@endphp

@if(is_null($lecturer))
    <!-- Modal -->
    <div class="modal fade" id="updateLecturerModal" tabindex="-1" aria-labelledby="updateLecturerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="updateLecturerForm" method="POST" action="{{ route('teacher.createOrUpdateLecturer') }}">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateLecturerModalLabel">
                            Cập nhật tài khoản giảng viên
                            <br>
                            <small class="text-danger">Bạn phải cập nhật thông tin để có thể sử dụng hệ thống</small>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <!-- Email (disabled) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $data['user']['email'] ?? '' }}" disabled>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $data['user']['name'] ?? '' }}" required>
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Ngày sinh <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $data['user']['date_of_birth'] ? \Carbon\Carbon::parse($data['user']['date_of_birth'])->format('Y-m-d') : '' }}">
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <label for="gender" class="form-label">Giới tính <small class="text-danger">*</small></label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Chọn giới tính</option>
                                <option value="Nam" {{ ($data['user']['gender'] ?? '') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ ($data['user']['gender'] ?? '') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            </select>
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $data['user']['phone'] ?? '' }}">
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Học hàm/Học vị <small class="text-danger">*</small></label>
                            <select class="form-select" id="title" name="title">
                                <option value="">Chọn học hàm/học vị</option>
                                <option value="Giảng viên" {{ ($data['lecturer']['title'] ?? '') == 'Giảng viên' ? 'selected' : '' }}>Giảng viên</option>
                                <option value="Thạc sĩ" {{ ($data['lecturer']['title'] ?? '') == 'Thạc sĩ' ? 'selected' : '' }}>Thạc sĩ</option>
                                <option value="Tiến sĩ" {{ ($data['lecturer']['title'] ?? '') == 'Tiến sĩ' ? 'selected' : '' }}>Tiến sĩ</option>
                                <option value="Phó Giáo sư" {{ ($data['lecturer']['title'] ?? '') == 'Phó giáo sư' ? 'selected' : '' }}>Phó giáo sư</option>
                                <option value="Giáo sư" {{ ($data['lecturer']['title'] ?? '') == 'Giáo sư' ? 'selected' : '' }}>Giáo sư</option>
                            </select>
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Faculty -->
                        <div class="mb-3">
                            <label for="faculty_id" class="form-label">Khoa <small class="text-danger">*</small></label>
                            <select class="form-select" id="faculty_id" name="faculty_id">
                                <option value="">Chọn Khoa</option>
                                @forelse($data['faculties'] ?? [] as $item)
                                    <option value="{{ $item['id'] }}" {{ ($data['lecturer']['faculty_id'] ?? '') == $item['id'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @empty
                                    <option value="" disabled>Không có khoa nào</option>
                                @endforelse
                            </select>
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Position -->
                        <div class="mb-3">
                            <label for="position" class="form-label">Chức vụ <small class="text-danger">*</small></label>
                            <select class="form-select" id="position" name="position">
                                <option value="">Chọn chức vụ</option>
                                <option value="Giảng viên" {{ ($data['lecturer']['position'] ?? '') == 'Giảng viên' ? 'selected' : '' }}>Giảng viên</option>
                                <option value="Phó trưởng bộ môn" {{ ($data['lecturer']['position'] ?? '') == 'Phó trưởng bộ môn' ? 'selected' : '' }}>Phó trưởng bộ môn</option>
                                <option value="Trưởng bộ môn" {{ ($data['lecturer']['position'] ?? '') == 'Trưởng bộ môn' ? 'selected' : '' }}>Trưởng bộ môn</option>
                                <option value="Phó trưởng khoa" {{ ($data['lecturer']['position'] ?? '') == 'Phó trưởng khoa' ? 'selected' : '' }}>Phó trưởng khoa</option>
                                <option value="Trưởng khoa" {{ ($data['lecturer']['position'] ?? '') == 'Trưởng khoa' ? 'selected' : '' }}>Trưởng khoa</option>
                            </select>
                            <small class="text-danger-error text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-update-lecturer">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        $(document).ready(function () {
            // Show modal if it exists
            if ($('#updateLecturerModal').length) {
                $('#updateLecturerModal').show().addClass('show').attr('aria-hidden', 'false');
                $('<div class="modal-backdrop fade show"></div>').appendTo('body');

                $('#updateLecturerModal [data-bs-dismiss="modal"]').on('click', function() {
                    $('#updateLecturerModal').hide().removeClass('show').attr('aria-hidden', 'true');
                    $('.modal-backdrop').remove();
                });
            }

            // Validation function
            function checkValidate(selector, regex, errorMessage) {
                const $input = $(selector);
                const value = $input.val();
                const $error = $input.next('.text-danger-error');

                if (!regex.test(value)) {
                    $error.text(errorMessage);
                    return false;
                } else {
                    $error.text('');
                    return true;
                }
            }

            $('.btn-update-lecturer').on('click', function (e) {
                e.preventDefault();

                // Validate form inputs
                const nameValid = checkValidate('#name', /.+/, 'Yêu cầu nhập họ và tên!');
                const dateOfBirthValid = checkValidate('#date_of_birth', /.+/, 'Yêu cầu nhập ngày sinh!');
                const genderValid = checkValidate('#gender', /.+/, 'Yêu cầu chọn giới tính!');
                const phoneValid = checkValidate('#phone', /^[0-9]{10,11}$/, 'Số điện thoại không hợp lệ!');
                const titleValid = checkValidate('#title', /.+/, 'Yêu cầu chọn học hàm/học vị!');
                const facultyValid = checkValidate('#faculty_id', /.+/, 'Yêu cầu chọn khoa!');
                const positionValid = checkValidate('#position', /.+/, 'Yêu cầu chọn chức vụ!');

                if (!nameValid || !dateOfBirthValid || !genderValid || !phoneValid || !titleValid || !facultyValid || !positionValid) {
                    return;
                }

                // Get form and data
                const $form = $('#updateLecturerForm');
                const url = $form.attr('action');
                const data = $form.serialize();

                // AJAX submission
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message || 'Cập nhật thành công!');
                            $('#updateLecturerModal').hide().removeClass('show').attr('aria-hidden', 'true');
                            $('.modal-backdrop').remove();
                            window.location.reload();
                        } else {
                            toastr.error(response.message || 'Cập nhật thất bại: Lỗi không xác định');
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = 'Đã xảy ra lỗi';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(field => {
                                $(`#${field}`).next('.text-danger-error').text(xhr.responseJSON.errors[field][0]);
                            });
                            errorMessage += ': Vui lòng kiểm tra các trường dữ liệu';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += ': ' + xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            errorMessage += ': ' + xhr.statusText;
                        }
                        toastr.error(errorMessage);
                        console.error('AJAX error:', xhr);
                    }
                });
            });
        });
    </script>
@endpush
