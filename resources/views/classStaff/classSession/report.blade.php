@extends('layouts.classStaff')

@section('title', 'Chi tiết sinh hoạt lớp')

@push('styles')
    <style>
        textarea {
            resize: none;
        }
    </style>
@endpush

@section('main')
    <div class="container mb-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            <span id="form-title">Báo cáo sinh hoạt lớp</span>
                        </h4>
                        <div class="btn-group" id="action-buttons" style="display: none;">
                            <button type="button" class="btn btn-outline-danger btn-sm" id="delete-btn">
                                <i class="fas fa-trash me-1"></i>Xóa báo cáo
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="reportForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="class_session_request_id" name="class_session_request_id" value="{{ request()->get('class_session_request_id') }}">
                            <input type="hidden" id="reporter_id" name="reporter_id" value="{{ auth()->user()->student?->id }}">

                            <div class="row">
                                <!-- Thông tin cơ bản -->
                                <div class="col-md-6 mb-3">
                                    <label for="attending_students" class="form-label">
                                        <i class="fas fa-users me-1"></i>
                                        Số sinh viên tham dự <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="attending_students" name="attending_students"
                                           placeholder="Nhập số sinh viên tham dự" min="0" value="" required>
                                    <div class="invalid-feedback">Vui lòng nhập số sinh viên tham dự</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="teacher_attendance" class="form-label">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>
                                        Giáo viên chủ nhiệm tham gia <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="teacher_attendance" name="teacher_attendance" required>
                                        <option value="">-- Chọn --</option>
                                        <option value="0">Có tham gia</option>
                                        <option value="1">Không tham gia</option>
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn tình trạng tham gia của giáo viên</div>
                                </div>
                            </div>

                            <!-- Các nội dung báo cáo -->
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="politics_ethics_lifestyle" class="form-label">
                                        <i class="fas fa-balance-scale me-1"></i>
                                        Tình hình chính trị, tư tưởng, đạo đức, lối sống <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="politics_ethics_lifestyle" name="politics_ethics_lifestyle"
                                              rows="4" placeholder="Mô tả tình hình chính trị, tư tưởng, đạo đức, lối sống của sinh viên..." required></textarea>
                                    <div class="invalid-feedback">Vui lòng mô tả tình hình chính trị, tư tưởng, đạo đức, lối sống</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="academic_training_status" class="form-label">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        Tình hình học tập, rèn luyện <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="academic_training_status" name="academic_training_status"
                                              rows="4" placeholder="Mô tả tình hình học tập, rèn luyện của sinh viên..." required></textarea>
                                    <div class="invalid-feedback">Vui lòng mô tả tình hình học tập, rèn luyện</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="on_campus_student_status" class="form-label">
                                        <i class="fas fa-building me-1"></i>
                                        Tình hình sinh viên nội trú <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="on_campus_student_status" name="on_campus_student_status"
                                              rows="3" placeholder="Mô tả tình hình sinh viên ở nội trú..." required></textarea>
                                    <div class="invalid-feedback">Vui lòng mô tả tình hình sinh viên nội trú</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="off_campus_student_status" class="form-label">
                                        <i class="fas fa-home me-1"></i>
                                        Tình hình sinh viên ngoại trú <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="off_campus_student_status" name="off_campus_student_status"
                                              rows="3" placeholder="Mô tả tình hình sinh viên ở ngoại trú..." required></textarea>
                                    <div class="invalid-feedback">Vui lòng mô tả tình hình sinh viên ngoại trú</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="other_activities" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Các hoạt động khác <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="other_activities" name="other_activities"
                                              rows="3" placeholder="Mô tả các hoạt động khác trong buổi sinh hoạt lớp..." required></textarea>
                                    <div class="invalid-feedback">Vui lòng mô tả các hoạt động khác</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="suggestions_to_faculty_university" class="form-label">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Đề xuất, kiến nghị với Khoa, Nhà trường <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="suggestions_to_faculty_university" name="suggestions_to_faculty_university"
                                              rows="3" placeholder="Các đề xuất, kiến nghị với Khoa, Nhà trường..." required></textarea>
                                    <div class="invalid-feedback">Vui lòng nhập đề xuất, kiến nghị</div>
                                </div>
                            </div>

                            <!-- Upload hình ảnh -->
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="activity_evidence_image" class="form-label">
                                        <i class="fas fa-image me-1"></i>
                                        Minh chứng, hình ảnh buổi sinh hoạt lớp <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="activity_evidence_image" name="path"
                                           accept="image/*" required>
                                    <div class="form-text">Chọn 1 hình ảnh (JPG, PNG, GIF). Tối đa 10MB.</div>
                                    <div class="invalid-feedback">Vui lòng chọn hình ảnh minh chứng</div>

                                    <!-- Preview image -->
                                    <div id="image-preview" class="mt-3" style="display: none;">
                                        <img id="preview-img" src="" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                    </div>

                                    <!-- Existing image (for edit mode) -->
                                    <div id="existing-image" class="mt-3" style="display: none;">
                                        <h6>Hình ảnh hiện tại:</h6>
                                        <img id="existing-img" src="" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary" onclick="history.back()">
                                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submit-btn">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            <span id="submit-text">Gửi báo cáo</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa báo cáo sinh hoạt lớp này không?</p>
                    <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                        <i class="fas fa-trash me-1"></i>Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let isEditMode = false;
            let reportId = null;
            $('#submit-btn').removeClass('d-none');

            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Check if we're in edit mode
            checkEditMode();

            function checkEditMode() {
                const urlParams = new URLSearchParams(window.location.search);
                reportId = urlParams.get('report_id');

                // if (reportId) {
                //     isEditMode = true;
                //     loadExistingData();
                //     $('#form-title').text('Chỉnh sửa báo cáo sinh hoạt lớp');
                //     $('#submit-text').text('Cập nhật báo cáo');
                //     $('#action-buttons').show();
                //     $('#activity_evidence_image').removeAttr('required');
                // }
                if (reportId) {
                    isEditMode = true;
                    loadExistingData();
                    $('#form-title').text('Chi tiết báo cáo sinh hoạt lớp');
                    $('#submit-btn').addClass('d-none');
                    $('#action-buttons').show();
                    $('#activity_evidence_image').removeAttr('required');
                }
            }

            function loadExistingData() {
                const report = @json($data['report'] ?? null);
                setTimeout(function() {
                    const sampleData = {
                        attending_students: report['attending_students'] ?? '',
                        teacher_attendance: report['teacher_attendance'] ?? '',
                        politics_ethics_lifestyle: report['politics_ethics_lifestyle'] ?? '',
                        academic_training_status: report['academic_training_status'] ?? '',
                        on_campus_student_status: report['on_campus_student_status'] ?? '',
                        off_campus_student_status: report['off_campus_student_status'] ?? '',
                        other_activities: report['other_activities'] ?? '',
                        suggestions_to_faculty_university: report['suggestions_to_faculty_university'] ?? '',
                        existing_image: report['path'] ?? ''
                    };
                    populateForm(sampleData);
                }, 500);
            }

            function populateForm(data) {
                Object.keys(data).forEach(key => {
                    if (key === 'existing_image') {
                        $('#existing-img').attr('src', data[key]);
                        $('#existing-image').show();
                    } else {
                        const element = $('#' + key);
                        if (element.length) {
                            element.val(data[key]);
                        }
                    }
                });
            }

            // Handle file input change for image preview
            $('#activity_evidence_image').on('change', function() {
                const file = this.files[0];

                if (file) {
                    if (file.size > 10 * 1024 * 1024) {
                        showToast('File quá lớn. Vui lòng chọn file nhỏ hơn 10MB.', 'error');
                        $(this).val('');
                        $('#image-preview').hide();
                        return;
                    }

                    if (!file.type.startsWith('image/')) {
                        showToast('Vui lòng chọn file hình ảnh hợp lệ.', 'error');
                        $(this).val('');
                        $('#image-preview').hide();
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-img').attr('src', e.target.result);
                        $('#image-preview').show();
                        $('#existing-image').hide();
                    };
                    reader.readAsDataURL(file);

                    $(this).removeClass('is-invalid');
                } else {
                    $('#image-preview').hide();
                    if (isEditMode) {
                        $('#existing-image').show();
                    }
                }
            });

            // Simple form validation
            function validateForm() {
                let isValid = true;

                const requiredFields = $('input[required], textarea[required], select[required]');
                $('.form-control, .form-select').removeClass('is-invalid is-valid');

                requiredFields.each(function() {
                    const value = $(this).val();

                    if (!value || value.trim() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).addClass('is-valid');
                    }
                });

                const attendingStudents = $('#attending_students').val();
                if (attendingStudents && (isNaN(attendingStudents) || parseInt(attendingStudents) < 0)) {
                    $('#attending_students').addClass('is-invalid').removeClass('is-valid');
                    showToast('Số sinh viên tham dự phải là số dương', 'error');
                    isValid = false;
                }

                const fileInput = $('#activity_evidence_image')[0];
                const hasNewImage = fileInput.files.length > 0;
                const hasExistingImage = isEditMode && $('#existing-image').is(':visible');

                if (!hasNewImage && !hasExistingImage) {
                    $('#activity_evidence_image').addClass('is-invalid');
                    showToast('Vui lòng chọn hình ảnh minh chứng', 'error');
                    isValid = false;
                }

                return isValid;
            }

            // Submit form
            $('#reportForm').on('submit', function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    showToast('Vui lòng điền đầy đủ tất cả thông tin bắt buộc', 'error');
                    const firstInvalid = $('.is-invalid').first();
                    if (firstInvalid.length) {
                        $('html, body').animate({
                            scrollTop: firstInvalid.offset().top - 100
                        }, 500);
                        firstInvalid.focus();
                    }
                    return;
                }

                const formData = new FormData(this);
                saveReport(formData);
            });

            function saveReport(formData) {
                const submitBtn = $('#submit-btn');
                const originalText = submitBtn.find('span').text();

                submitBtn.prop('disabled', true);
                submitBtn.find('span').text('Đang gửi...');
                submitBtn.find('i').removeClass('fa-paper-plane').addClass('fa-spinner fa-spin');

                // Ensure CSRF token and method are included
                // formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                // if (isEditMode) {
                //     formData.append('_method', 'PUT');
                // }
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                // const url = isEditMode ? `/class-staff/class-session/report/${reportId}` : '/class-staff/class-session/report';
                const url = '/class-staff/class-session/report';
                // const method = isEditMode ? 'PUT' : 'POST';
                const method = 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success('Gửi báo cáo thành công', 'success');
                        setTimeout(() => {
                            window.location.href = '/class-staff/class-session/history';
                        }, 1500);
                    },
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra, vui lòng thử lại';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showToast(message, 'error');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.find('span').text(originalText);
                        submitBtn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-paper-plane');
                    }
                });
            }

            // Delete report
            $('#delete-btn').on
            $('#delete-btn').on('click', function() {
                $('#deleteModal').modal('show');
            });

            $('#confirm-delete-btn').on('click', function() {
                const deleteBtn = $(this);
                deleteBtn.prop('disabled', true);
                deleteBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Đang xóa...');

                $.ajax({
                    url: `/class-staff/class-session/report/${reportId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success('Xóa báo cáo thành công');
                        $('#deleteModal').modal('hide');
                        setTimeout(() => {
                            window.location.href = '/class-staff/class-session/history';
                        }, 1500);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON ?.message || 'Có lỗi xảy ra khi xóa báo cáo';
                        toastr.error(message);
                    },
                    complete: function() {
                        deleteBtn.prop('disabled', false);
                        deleteBtn.html('<i class="fas fa-trash me-1"></i>Xóa');
                    }
                });
            });

            // Toast notification function
            function showToast(message, type = 'info') {
                const toastClass = type === 'error' ? 'bg-danger' : type === 'success' ? 'bg-success' : 'bg-info';
                const toastHtml = `
                    <div class="toast align-items-center text-white ${toastClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                ${message}
                            </div>
                            <button type="btn" type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">Xóa</button>
                        </div>
                    </div>
                `;

                if (!$('#toast-container').length) {
                    $('body').append('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
                }

                const $toast = $(toastHtml);
                $('#toast-container').append($toast);

                const toast = new bootstrap.Toast($toast[0]);
                toast.show();

                $toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }
        });
    </script>
@endpush
