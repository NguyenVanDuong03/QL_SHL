@extends('layouts.teacher')

@section('title', 'Sinh hoạt lớp cố định')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'teacher.class-session.index'],
        ['label' => 'Sinh hoạt lớp cố định', 'url' => 'teacher.class-session.fixed-class-activitie'],
        ['label' => 'Đăng ký sinh hoạt lớp']
    ]"/>
@endsection

@section('main')
    <div class="container-fluid mt-4">
        <!-- Header với thông tin khóa học -->
        <div class="mx-3">
            <div class="mb-2">
                <a href="{{ route('teacher.class-session.fixed-class-activitie') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-0"><strong>{{ $data['getCSRSemesterInfo']->name }}
                                    - {{ $data['getCSRSemesterInfo']->school_year }}</strong></p>
                            <p class="mb-0"><strong>Lớp:</strong> {{ $data['getStudyClassByIds']->name }}</p>
                        </div>
                        <h3 class="fw-bold d-none d-md-block">Đăng ký sinh hoạt lớp</h3>
                        <div>
                            <p class="mb-0"><strong>Ngày bắt
                                    đầu</strong> {{ \Carbon\Carbon::parse($data['getCSRSemesterInfo']->open_date)->format('H:i d/m/Y') }}
                            </p>
                            <p class="mb-0"><strong>Ngày kết
                                    thúc:</strong> {{ \Carbon\Carbon::parse($data['getCSRSemesterInfo']->end_date)->format('H:i d/m/Y') }}
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="my-2 mx-3">
            <form id="form-session" action="{{ route('teacher.class-session.store') }}" method="POST" class="form-class-session">
                @csrf
                <div class="row">
                    <input type="hidden" name="study_class_id" value="{{ $data['getStudyClassByIds']->id }}">
                    <input type="hidden" name="status" value="0">
                    <!-- Cột trái - Form tiêu đề và nội dung -->
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-bold">Tiêu đề</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           placeholder="Nhập tiêu đề" required>
                                </div>

                                <div class="mb-4">
                                    <label for="content" class="form-label fw-bold">Nội dung chính</label>
                                    <textarea class="form-control resize-none" id="content" name="content" rows="15"
                                              placeholder="Nhập nội dung chính" required></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-session-submit">Đăng ký</button>
                                </div>

                                <!-- Rejection reason if status = 2 -->
                                @if(optional($data['getClassSessionRequest'])->status == 2 && !empty($data['getClassSessionRequest']->rejection_reason))
                                    <div class="mt-2">
                                        <div class="alert alert-danger">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-exclamation-triangle me-2"></i>Lý do từ chối
                                            </h6>
                                            <p class="mb-0">{{ $data['getClassSessionRequest']->rejection_reason }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Cột phải - Form đăng ký -->
                    <div class="col-md-4 mt-md-0 mt-2">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Đăng ký hình thức sinh hoạt lớp</h6>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="resetBtn">Đặt lại
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <select class="form-select" id="activityType" name="position">
                                        <option value="0" selected>Trực tiếp tại trường</option>
                                        <option value="1">Trực tuyến</option>
                                        <option value="2">Dã ngoại</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="timeSelect" class="form-label">Thời gian</label>
                                    <input type="datetime-local" class="form-control" name="proposed_at" id="timeSelect"
                                           placeholder="Chọn thời gian" required>
                                </div>

                                <div class="mb-3 class-location">
                                    <label for="location" class="form-label">Địa điểm</label>
                                    <input type="text" class="form-control" name="location" id="location"
                                           placeholder="Nhập địa điểm">
                                </div>

                                <div class="class-meeting">
                                    <div class="mb-3">
                                        <label for="meeting_type" class="form-label">Chọn nền tảng</label>
                                        <select class="form-select" name="meeting_type" id="meeting_type">
                                            <option value="" disabled selected>Chọn nền tảng</option>
                                            <option value="Google Meet">Google Meet</option>
                                            <option value="Zoom Cloud Meeting">Zoom Cloud Meeting</option>
                                            <option value="Microsoft Teams">Microsoft Teams</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="meeting_id" class="form-label">Mã cuộc họp</label>
                                        <input type="text" class="form-control" name="meeting_id" id="meeting_id"
                                               placeholder="Nhập mã cuộc họp">
                                    </div>

                                    <div class="mb-3">
                                        <label for="meeting_password" class="form-label">Mật khẩu cuộc họp</label>
                                        <input type="text" class="form-control" name="meeting_password"
                                               id="meeting_password"
                                               placeholder="Nhập mật khẩu cuộc họp">
                                    </div>

                                    <div class="mb-3">
                                        <label for="meeting_link" class="form-label">Link cuộc họp</label>
                                        <input type="text" class="form-control" name="meeting_url" id="meeting_link"
                                               placeholder="Nhập link cuộc họp">
                                        <div class="invalid-feedback">
                                            Vui lòng nhập một URL hợp lệ.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="notes" class="form-label">Ghi chú (nếu có)</label>
                                    <textarea class="form-control resize-none" name="note" id="notes" rows="5"
                                              placeholder="Nhập nội dung ghi chú"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const $activityType = $('#activityType');
            const $formRight = $('.form-class-session .col-md-4');

            function isValidURL(str) {
                const pattern = /^(https?:\/\/)[^\s$.?#].[^\s]*$/i;
                return pattern.test(str);
            }

            $('#resetBtn').click(function () {
                $formRight.find('input[type="text"], input[type="datetime-local"], textarea').val('');
                $activityType.val('0').trigger('change');
                $formRight.find('select').not('#activityType').prop('selectedIndex', 0);
            });

            $activityType.change(function () {
                const selectedValue = $(this).val();

                $formRight.find('input[type="text"], input[type="datetime-local"], textarea').val('');
                $formRight.find('select').not('#activityType').prop('selectedIndex', 0);

                $('.class-meeting input, .class-meeting select').removeAttr('required');
                $('.class-location input').removeAttr('required');
                $('#meeting_type').removeAttr('required');

                if (selectedValue === '0') {
                    $('.class-location').addClass('d-none');
                    $('.class-meeting').addClass('d-none');
                } else if (selectedValue === '1') {
                    $('.class-location').addClass('d-none');
                    $('.class-meeting').removeClass('d-none');

                    $('.class-meeting input, .class-meeting select').attr('required', true);
                    $('#meeting_type').attr('required', true);

                    $('#meeting_link').on('input', function () {
                        const url = $(this).val().trim();

                        if (url === '') {
                            $(this).attr('required', true)
                        } else if (!isValidURL(url)) {
                            $(this).addClass('is-invalid');
                            $('.btn-session-submit').addClass('disabled').prop('disabled', true);
                        } else {
                            $(this).removeClass('is-invalid');
                            $('.btn-session-submit').removeClass('disabled').prop('disabled', false);
                        }
                    });

                    $('form').on('submit', function (e) {
                        const url = $('#meeting_link').val().trim();

                        if (url !== '' && !isValidURL(url)) {
                            $('#meeting_link').addClass('is-invalid');
                            $('.btn-session-submit').addClass('disabled').prop('disabled', true);
                            e.preventDefault();
                        }
                    });
                } else if (selectedValue === '2') {
                    $('.class-location').removeClass('d-none');
                    $('.class-meeting').addClass('d-none');

                    $('.class-location input').attr('required', true);
                }
            });


            $activityType.trigger('change');

            const getClassSessionRequest = @json($data['getClassSessionRequest']);
            if (getClassSessionRequest) {
                if (getClassSessionRequest['position'] === 0) {
                    $activityType.val('0').trigger('change');
                } else if (getClassSessionRequest['position'] === 1) {
                    $activityType.val('1').trigger('change');
                    $('#meeting_id').val(getClassSessionRequest['meeting_id']);
                    $('#meeting_password').val(getClassSessionRequest['meeting_password']);
                    $('#meeting_link').val(getClassSessionRequest['meeting_url']);
                    $('#meeting_type').val(getClassSessionRequest['meeting_type']);
                } else if (getClassSessionRequest['position'] === 2) {
                    $activityType.val('2').trigger('change');
                    $('#location').val(getClassSessionRequest['location']);
                }

                $('#title').val(getClassSessionRequest['title']);
                $('#content').val(getClassSessionRequest['content']);
                $('#timeSelect').val(getClassSessionRequest['proposed_at']);
                $('#notes').val(getClassSessionRequest['note']);
            }
        });
    </script>
@endpush
