
@php
    $student = auth()->user()->student ?? null;
@endphp

@if(is_null($student))
    <!-- Modal -->
    <div class="modal fade" id="updateStudentModal" tabindex="-1" aria-labelledby="updateStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="updateStudentForm" method="POST" action="{{ route('student.createOrUpdateStudent') }}">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateStudentModalLabel">
                            Cập nhật tài khoản sinh viên
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

                        <!-- Student Code (disabled) -->
                        <div class="mb-3">
                            <label for="student_code" class="form-label">Mã sinh viên</label>
                            <input type="text" class="form-control" id="student_code" name="student_code" value="{{ $data['user']['student_code'] ?? '' }}" disabled>
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
                                <option value="Nam" {{ $data['user']['gender'] ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ $data['user']['gender'] ? 'selected' : '' }}>Nữ</option>
                            </select>
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $data['user']['phone'] ?? '' }}">
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Cohort -->
                        <div class="mb-3">
                            <label for="cohort_id" class="form-label">Niên khóa <small class="text-danger">*</small></label>
                            <select class="form-select" id="cohort_id" name="cohort_id">
                                <option value="">Chọn Niên khóa</option>
                                @forelse($data['cohorts'] ?? [] as $item)
                                    <option value="{{ $item['id'] }}" {{ $data['user']['cohort_id'] == $item['id'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @empty
                                    <option value="" disabled>Không có khóa học nào</option>
                                @endforelse
                            </select>
                            <small class="text-danger-error text-danger"></small>
                        </div>

                        <!-- Study Class (Hard-coded condition: assuming null for display) -->
                        <div class="mb-3" id="studyClassField">
                            <label for="study_class_id" class="form-label">Lớp học <small class="text-danger">*</small></label>
                            <select class="form-select" id="study_class_id" name="study_class_id">
                                <option value="">Chọn Lớp học</option>
{{--                                @forelse($data['studyClasses'] ?? [] as $item)--}}
{{--                                    <option value="{{ $item['id'] }}" {{ $data['user']['study_class_id'] == $item['id'] ? 'selected' : '' }}>--}}
{{--                                        {{ $item['name'] }}--}}
{{--                                    </option>--}}
{{--                                @empty--}}
{{--                                    <option value="" disabled>Không có lớp học nào</option>--}}
{{--                                @endforelse--}}
                            </select>
                            <small class="text-danger-error text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-update-student">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        $(document).ready(function () {
            const studyClasses = @json($data['studyClasses'] ?? []);

            function updateStudyClasses(selectedCohortId) {
                const $studyClassSelect = $('#study_class_id');
                $studyClassSelect.find('option:not(:first)').remove();

                const filteredClasses = studyClasses.filter(cls => cls.cohort_id == selectedCohortId);
                if (filteredClasses.length === 0) {
                    $studyClassSelect.append('<option value="" disabled>Không có lớp học nào</option>');
                } else {
                    filteredClasses.forEach(cls => {
                        const isSelected = cls.id == '{{ $data['user']['study_class_id'] ?? '' }}' ? 'selected' : '';
                        $studyClassSelect.append(
                            `<option value="${cls.id}" data-cohort-id="${cls.cohort_id}" ${isSelected}>${cls.name}</option>`
                        );
                    });
                }
            }

            const initialCohortId = $('#cohort_id').val();
            if (initialCohortId) {
                updateStudyClasses(initialCohortId);
            }

            $('#cohort_id').on('change', function () {
                const cohortId = $(this).val();
                updateStudyClasses(cohortId);
            });

            let email = $('#email').val();
            let studentCode = email.split('@')[0];
            $('#student_code').val(studentCode);

            $('#date_of_birth').on('change', function() {
                console.log('Date selected:', $(this).val());
            });

            if ($('#updateStudentModal').length) {
                $('#updateStudentModal').show().addClass('show').attr('aria-hidden', 'false');
                $('<div class="modal-backdrop fade show"></div>').appendTo('body');

                $('#updateStudentModal [data-bs-dismiss="modal"]').on('click', function() {
                    $('#updateStudentModal').hide().removeClass('show').attr('aria-hidden', 'true');
                    $('.modal-backdrop').remove();
                });
            }

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

            $('.btn-update-student').on('click', function (e) {
                e.preventDefault();

                // Validate form inputs
                const nameValid = checkValidate('#name', /.+/, 'Yêu cầu nhập họ và tên!');
                const dateOfBirthValid = checkValidate('#date_of_birth', /.+/, 'Yêu cầu nhập ngày sinh!');
                const genderValid = checkValidate('#gender', /.+/, 'Yêu cầu chọn giới tính!');
                const phoneValid = checkValidate('#phone', /^[0-9]{10,11}$/, 'Số điện thoại không hợp lệ!');
                const cohortValid = checkValidate('#cohort_id', /.+/, 'Yêu cầu chọn niên khóa!');
                const studyClassValid = checkValidate('#study_class_id', /.+/, 'Yêu cầu chọn lớp học!');

                if (!nameValid || !dateOfBirthValid || !genderValid || !phoneValid || !cohortValid || !studyClassValid) {
                    return;
                }

                // Get form and data
                const $form = $('#updateStudentForm');
                const url = $form.attr('action');
                // Serialize form data and append student_code
                let data = $form.serializeArray();
                data.push({ name: 'student_code', value: $('#student_code').val() });

                // AJAX submission
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $.param(data), // Convert array back to query string
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message || 'Cập nhật thành công!');
                            $('#updateStudentModal').hide().removeClass('show').attr('aria-hidden', 'true');
                            $('.modal-backdrop').remove();
                            // Reload the page to update UI
                            window.location.reload();
                        } else {
                            toastr.error(response.message || 'Cập nhật thất bại: Lỗi không xác định');
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = 'Đã xảy ra lỗi';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Display field-specific validation errors
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
