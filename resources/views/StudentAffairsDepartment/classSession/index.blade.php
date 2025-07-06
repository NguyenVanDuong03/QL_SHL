@extends('layouts.studentAffairsDepartment')

@section('title', 'Sinh hoạt lớp cố định')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Sinh hoạt lớp cố định']]"/>
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
    @include('StudentAffairsDepartment.classSession.tabs')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="px-4">
            @if ($data['checkClassSessionRegistration'])
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h4>{{ $data['classSessionRegistration']->name }} -
                            {{ $data['classSessionRegistration']->school_year }}</h4>
                        <p class="p-0 m-0">Thời gian bắt đầu:
                            {{ \Carbon\Carbon::parse($data['classSessionRegistration']->open_date)->format('H:i d/m/Y') }}
                        </p>
                        <p class="p-0 m-0">Thời gian kết thúc:
                            {{ \Carbon\Carbon::parse($data['classSessionRegistration']->end_date)->format('H:i d/m/Y') }}
                        </p>
                        <p class="p-0">Số lớp đăng đã đăng ký:
                            {{ $data['classSessionRegistration']->total_registered_classes }}</p>

                        <div class="btn-group-sm">
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmEditModal"
                                    data-id="#">Chỉnh sửa thời gian
                            </button>
                            <button class="btn btn-danger btn-delete-class-session"
                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="#">
                                Xóa
                            </button>
                        </div>

                    </div>
                    <h4 class="fw-bold d-none d-md-block">Sinh hoạt lớp cố định</h4>
                    <div class="d-flex flex-column justify-content-end">
                        <form class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Tìm kiếm lớp học" name="search" value="{{ request('search') }}"
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <button type="submit" class="input-group-text" id="basic-addon2"><i
                                    class="fas fa-magnifying-glass"></i></button>
                        </form>

                        <a class="btn btn-secondary btn-sm btn-create-class-session" href="{{ route('student-affairs-department.class-session.listReports') }}">
                            Báo cáo
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên lớp</th>
                                    <th scope="col">Thời gian</th>
                                    <th scope="col">Hình thức</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($data['ListCSRs']['data']) && $data['ListCSRs']['total'] > 0)
                                    @foreach ($data['ListCSRs']['data'] as $index => $class)
                                        <tr>
                                            <td>
                                                {{ ($data['ListCSRs']['current_page'] - 1) * $data['ListCSRs']['per_page'] + $index + 1 }}
                                            </td>
                                            <td>
                                                <strong>{{ $class['study_class_name'] }}</strong>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($class['proposed_at'])->format('H:i d/m/Y') }}
                                            </td>
                                            <td>
                                                @if ($class['position'] == 0)
                                                    <span class="badge bg-success">Trực tiếp tại trường</span>
                                                @elseif ($class['position'] == 1)
                                                    <span class="badge bg-primary">Trực tuyến</span>
                                                @elseif ($class['position'] == 2)
                                                    <span class="badge bg-warning">Dã ngoại</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($class['status'] == 0)
                                                    <span class="badge bg-secondary">Chưa xét duyệt</span>
                                                @elseif ($class['status'] == 1)
                                                    <span class="badge bg-success">Đã xét duyệt</span>
                                                @elseif ($class['status'] == 2)
                                                    <span class="badge bg-danger">Đã từ chối</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group gap-2">
                                                    <button class="btn btn-primary btn-sm btn-confirm-class"
                                                            title="Xét duyệt"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#formModal"
                                                            data-id="{{ $class['id'] }}"
                                                            data-type="{{ $class['type'] }}"
                                                            data-position="{{ $class['position'] }}"
                                                            data-proposed_at="{{ $class['proposed_at'] }}"
                                                            data-location="{{ $class['location'] }}"
                                                            data-meeting-type="{{ $class['meeting_type'] }}"
                                                            data-meeting-id="{{ $class['meeting_id'] }}"
                                                            data-meeting-password="{{ $class['meeting_password'] }}"
                                                            data-meeting-url="{{ $class['meeting_url'] }}"
                                                            data-study-class-name="{{ $class['study_class_name'] }}"
                                                            data-note="{{ $class['note'] }}"
                                                            data-room-name="{{ $class['room_name'] ?? '' }}"
                                                            data-room-id="{{ $class['room_id'] ?? '' }}"
                                                            data-total-students = "{{ $class['total_students'] }}"
                                                            data-status="{{ $class['status'] }}"
                                                    >
                                                        <i class="fas fa-file-signature"></i>
                                                    </button>
                                                    <button
                                                        class="btn btn-secondary btn-sm btn-detail-class {{ $class['status'] == 0 ? 'disabled' : '' }}"
                                                        title="Chi tiết"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#formModal"
                                                        data-id="{{ $class['id'] }}"
                                                        data-type="{{ $class['type'] }}"
                                                        data-position="{{ $class['position'] }}"
                                                        data-proposed_at="{{ $class['proposed_at'] }}"
                                                        data-location="{{ $class['location'] }}"
                                                        data-meeting-type="{{ $class['meeting_type'] }}"
                                                        data-meeting-id="{{ $class['meeting_id'] }}"
                                                        data-meeting-password="{{ $class['meeting_password'] }}"
                                                        data-meeting-url="{{ $class['meeting_url'] }}"
                                                        data-study-class-name="{{ $class['study_class_name'] }}"
                                                        data-note="{{ $class['note'] }}"
                                                        data-room-name="{{ $class['room_name'] ?? '' }}"
                                                        data-room-description="{{ $class['room_description'] ?? '' }}"
                                                        data-room-id="{{ $class['room_id'] ?? '' }}"
                                                        data-status="{{ $class['status'] }}"
                                                        data-detail="true"
                                                    >
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            Không có lớp học nào được đăng ký.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    <div class="d-flex justify-content-center">
                        @include('components.pagination.pagination', [
                            'paginate' => $data['ListCSRs'],
                        ])
                    </div>
                </div>
            @else
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="g-2">
                        <button class="btn btn-primary btn-create-class-session"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmCreateModal" data-id="#">
                            Tạo lịch đăng ký sinh hoạt lớp
                        </button>
                        <a class="btn btn-secondary btn-create-class-session" href="{{ route('student-affairs-department.class-session.listReports') }}">
                            Báo cáo
                        </a>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="text-center alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Chưa có lịch sinh hoạt lớp.
                    </div>
                </div>
            @endif

            <!-- Modal Tạo mới lịch đăng ký sinh hoạt lớp -->
            <div class="modal fade auto-reset-modal" id="confirmCreateModal" tabindex="-1"
                 aria-labelledby="confirmCreateModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="createModalLabel">Đăng ký sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger btnReset" id="btnReset">Đặt
                                    lại
                                </button>
                            </div>

                            <form id="createform" method="POST"
                                  action="{{ route('student-affairs-department.class-session.createClassSessionRegistration') }}">
                                @csrf
                                @method('POST')
                                <div class="mb-3">
                                    <label for="semester_id" class="form-label">Học kỳ</label>
                                    <select class="form-select" id="semester_id" name="semester_id" required>
                                        @if (empty($data['semesters']))
                                            <option disabled selected>Không có học kỳ nào</option>
                                        @else
                                            @foreach ($data['semesters'] as $semester)
                                                <option value="{{ $semester->id }}">{{ $semester->name }} -
                                                    {{ $semester->school_year }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div id="semester_id_error" class="text-danger text-danger-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="open_date" class="form-label">Thời gian bắt đầu</label>
                                    <input type="text" class="form-control" id="open_date" name="open_date"
                                           placeholder="Chọn thời gian bắt đầu" onfocus="(this.type='datetime-local')"
                                           onblur="if(!this.value)this.type='text'" required
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                    <div id="open_date_error" class="text-danger text-danger-error"></div>
                                    @error('open_date')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Thời gian kết thúc</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date"
                                           placeholder="Chọn thời gian kết thúc" onfocus="(this.type='datetime-local')"
                                           onblur="if(!this.value)this.type='text'" required
                                           min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                    <div id="end_date_error" class="text-danger text-danger-error"></div>
                                    @error('end_date')
                                    <div class="text-danger text-danger-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                            style="width: 120px;">Quay lại
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-create-submit"
                                            style="width: 120px;">Đăng ký
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa lịch đăng ký sinh hoạt lớp -->
    @if (isset($data['classSessionRegistration']) && $data['classSessionRegistration'])
        <div class="modal fade auto-reset-modal" id="confirmEditModal" tabindex="-1"
             aria-labelledby="confirmEditModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="modal-title fw-bold" id="editModalLabel">Đăng ký sinh hoạt lớp cố
                                định</h5>
                            <button type="button" class="btn btn-outline-danger btnReset" id="btneditReset">Đặt
                                lại
                            </button>
                        </div>

                        <form id="editform" method="POST"
                              action="{{ route('student-affairs-department.class-session.editClassSessionRegistration', $data['classSessionRegistration']->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="semester_id_edit" class="form-label">Học kỳ</label>
                                <select class="form-select" id="semester_id_edit" name="semester_id" required>
                                    @foreach ($data['semesters'] as $semester)
                                        <option value="{{ $semester->id }}"
                                            {{ $semester->id == $data['classSessionRegistration']->semester_id ? 'selected' : '' }}>
                                            {{ $semester->name }} - {{ $semester->school_year }}</option>
                                    @endforeach
                                </select>
                                <div id="semester_id_error-edit" class="text-danger text-danger-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="open_date_edit" class="form-label">Thời gian bắt đầu</label>
                                <input type="text" class="form-control" id="open_date_edit" name="open_date"
                                       placeholder="Chọn thời gian bắt đầu"
                                       onfocus="(this.type='datetime-local')"
                                       onblur="if(!this.value)this.type='text'" required
                                       value="{{ $data['classSessionRegistration']->open_date }}"
                                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                <div id="open_date_error_edit" class="text-danger text-danger-error"></div>
                                @error('open_date')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="end_date_edit" class="form-label">Thời gian kết thúc</label>
                                <input type="text" class="form-control" id="end_date_edit" name="end_date"
                                       placeholder="Chọn thời gian kết thúc"
                                       onfocus="(this.type='datetime-local')"
                                       onblur="if(!this.value)this.type='text'" required
                                       value="{{ $data['classSessionRegistration']->end_date }}"
                                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                <div id="end_date_error_edit" class="text-danger text-danger-error"></div>
                                @error('end_date')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                        style="width: 120px;">Quay lại
                                </button>
                                <button type="submit" class="btn btn-primary btn-edit-submit"
                                        style="width: 120px;">Đăng
                                    ký
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Hình thức họp --}}
        <div class="modal fade auto-reset-modal" id="formModal" tabindex="-1" aria-labelledby="formModalLabel"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="modal-title fw-bold" id="formModalLabel">Xét duyệt sinh hoạt lớp cố định</h5>
                            <button type="button" class="btn btn-outline-danger" id="btnReject">Huỷ đăng ký</button>
                        </div>

                        <form id="formConfirm" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" class="class_session_status" value="1">
                            <input type="hidden" name="position" class="class_session_position_input">
                            <input type="hidden" name="type" value="0">
                            <input type="hidden" name="room_id" class="class_session_room_id">

                            <h6>Lớp: <span class="class_session_study_class"></span></h6>
                            <h6>Hình thức họp: <span class="class_session_position"></span></h6>
                            <h6>Thời gian: <span class="class_session_proposed_at"></span></h6>

                            <div class="session-picnic">
                                <h6>Địa điểm: <span class="class_session_location"></span></h6>
                            </div>

                            <div class="session-online d-none">
                                <h6>Nền tảng tổ chức họp: <span class="class_session_meeting_type"></span></h6>
                                <h6>ID phòng họp: <span class="class_session_meeting_id"></span></h6>
                                <h6>Mật khẩu: <span class="class_session_meeting_password"></span></h6>
                                <h6>Đường dẫn phòng họp: <a class="class_session_meeting_url" target="_blank">Bấm
                                        vào đây!</a></h6>
                            </div>

                            <h6>Ghi chú: <span class="class_session_note"></span></h6>

                            <h6 class="class_session_room d-none">Phòng: <span class="class_session_room_name"></span></h6>
                            <h6 class="class_session_room d-none">Mô tả: <span class="class_session_room_description"></span></h6>
                            <div class="form-group session-offline d-none mb-3">
                                <label for="room" class="form-label room">Chọn phòng họp:</label>
                                <select class="form-select" id="room" name="room_id">
{{--                                    @if(isset($data['rooms']) && $data['rooms']->isNotEmpty())--}}
{{--                                        @foreach($data['rooms'] as $room)--}}
{{--                                            <option value="{{ $room->id }}">{{ $room->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    @else--}}
{{--                                        <option value="" disabled selected>Không có phòng khả dụng</option>--}}
{{--                                    @endif--}}
                                </select>
                            </div>

                            <div class="form-group input-rejection d-none">
                                <label for="rejection_reason" class="form-label">Lý do từ chối:</label>
                                <textarea class="form-control resize-none" id="rejection_reason"
                                          name="rejection_reason" rows="3"
                                          placeholder="Nhập nội dung từ chối"></textarea>
                            </div>


                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                        style="width: 120px;">Quay lại
                                </button>
                                <button type="submit" class="btn btn-primary btn-confirm-form"
                                        style="width: 120px;">Xét duyệt
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Xoá lịch đăng ký sinh hoạt lớp -->
        <div class="modal fade auto-reset-modal" id="confirmDeleteModal" tabindex="-1"
             aria-labelledby="confirmDeleteModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel">Xoá lịch đăng ký sinh hoạt
                                lớp</h5>
                        </div>

                        <form id="deleteform" method="POST"
                              action="{{ route('student-affairs-department.class-session.deleteClassSessionRegistration', $data['classSessionRegistration']->id) }}">
                            @csrf
                            @method('DELETE')
                            <p>Bạn có chắc chắn muốn xoá lịch đăng ký sinh hoạt lớp này không?</p>
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                        style="width: 120px;">Quay lại
                                </button>
                                <button type="submit" class="btn btn-danger"
                                        style="width: 120px;">Xoá
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let isRejectMode = false;

            // Reset form when modal is closed
            $('.auto-reset-modal').on('hidden.bs.modal', function () {
                $('.text-danger-error').text('');
                $(this).find('form')[0].reset();
                $('.input-rejection').addClass('d-none');
                $('#room').prop('disabled', false);
                $('#btnReject').css({
                    'background-color': '',
                    'color': ''
                });
                $('.btn-confirm-form').removeClass('d-none');
                $('.class_session_room').addClass('d-none');
                $('.session-offline').addClass('d-none');
                $('.session-online').addClass('d-none');
                $('.session-picnic').addClass('d-none');
                isRejectMode = false;
            });

            // Reset form when button is clicked
            $('.btnReset').click(function () {
                $('.text-danger-error').text('');
                $('#createform')[0].reset();
            });

            $('.btn-create-submit').on('click', function (e) {
                e.preventDefault();

                let openDate = $('#open_date').val();
                let endDate = $('#end_date').val();
                let semesterId = $('#semester_id').val();

                $('#open_date_error').text('');
                $('#end_date_error').text('');

                if (semesterId == null) {
                    $('#semester_id_error').text('Vui lòng tạo học kỳ trước khi tạo lịch sinh hoạt lớp');
                    return;
                }

                if (!openDate || !endDate) {
                    if (!openDate) $('#open_date_error').text('Vui lòng chọn thời gian bắt đầu');
                    if (!endDate) $('#end_date_error').text('Vui lòng chọn thời gian kết thúc');
                    return;
                }

                if (new Date(openDate) >= new Date(endDate)) {
                    $('#end_date_error').text('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
                    return;
                }

                $('#createform').submit();
            });

            $('.btn-edit-submit').on('click', function (e) {
                e.preventDefault();

                let openDate = $('#open_date_edit').val();
                let endDate = $('#end_date_edit').val();
                let semesterId = $('#semester_id_edit').val();

                $('#open_date_error_edit').text('');
                $('#end_date_error_edit').text('');

                if (semesterId == null) {
                    $('#semester_id_error-edit').text('Vui lòng tạo học kỳ trước khi tạo lịch sinh hoạt lớp');
                    return;
                }

                if (!openDate || !endDate) {
                    if (!openDate) $('#open_date_error_edit').text('Vui lòng chọn thời gian bắt đầu');
                    if (!endDate) $('#end_date_error_edit').text('Vui lòng chọn thời gian kết thúc');
                    return;
                }

                if (new Date(openDate) >= new Date(endDate)) {
                    $('#end_date_error_edit').text('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
                    return;
                }

                $('#editform').submit();
            });

            $('#btnReject').on('click', function () {
                isRejectMode = !isRejectMode;

                if (isRejectMode) {
                    $('.input-rejection').removeClass('d-none');
                    $('#room').prop('disabled', true);
                    $('.btn-confirm-form').removeClass('d-none');
                    $(this).css({
                        'background-color': '#dc3545',
                        'color': '#fff'
                    });
                } else {
                    $('.input-rejection').addClass('d-none');
                    $('#room').prop('disabled', false);
                    $('.btn-confirm-form').addClass('d-none');
                    $(this).css({
                        'background-color': '',
                        'color': ''
                    });
                }
            });

            $('.btn-confirm-class').on('click', function () {
                const classId = $(this).data('id');
                const studyClassName = $(this).data('study-class-name');
                const type = $(this).data('type');
                const position = $(this).data('position');
                const proposedAt = $(this).data('proposed_at');
                const location = $(this).data('location');
                const meetingType = $(this).data('meeting-type');
                const meetingId = $(this).data('meeting-id');
                const meetingPassword = $(this).data('meeting-password');
                const meetingUrl = $(this).data('meeting-url');
                const note = $(this).data('note');
                const roomName = $(this).data('room-name') || '';
                const roomId = $(this).data('room-id');
                const totalStudents = $(this).data('total-students');
                const status = $(this).data('status');

                if (status === 1) {
                    $('.btn-confirm-form').addClass('d-none');
                } else {
                    $('.btn-confirm-form').removeClass('d-none');
                }

                $('.class_session_room').addClass('d-none');
                const roomSelect = $('#room');
                roomSelect.empty();

                @if(isset($data['rooms']) && $data['rooms']->isNotEmpty())
                let hasValidRoom = false;
                @foreach($data['rooms'] as $room)
                if ({{ $room->quantity }} >= totalStudents) {
                    roomSelect.append(`<option value="{{ $room->id }}">{{ $room->name }} - {{ $room->description }}</option>`);
                    hasValidRoom = true;
                }
                @endforeach
                if (!hasValidRoom) {
                    roomSelect.append(`<option value="" disabled selected>Không có phòng khả dụng cho ${totalStudents} sinh viên</option>`);
                }
                @else
                roomSelect.append(`<option value="" disabled selected>Không có phòng khả dụng</option>`);
                @endif

                if (roomId && roomName) {
                    if (roomSelect.find(`option[value="${roomId}"]`).length === 0) {
                        roomSelect.prop('selectedIndex', 0);
                    } else {
                        roomSelect.val(roomId);
                    }
                } else {
                    roomSelect.prop('selectedIndex', 0);
                }

                if (position === 0) {
                    $('.session-offline').removeClass('d-none');
                    $('.session-online').addClass('d-none');
                    $('.session-picnic').addClass('d-none');
                } else if (position === 1) {
                    $('.session-offline').addClass('d-none');
                    $('.session-online').removeClass('d-none');
                    $('.session-picnic').addClass('d-none');
                } else {
                    $('.session-offline').addClass('d-none');
                    $('.session-online').addClass('d-none');
                    $('.session-picnic').removeClass('d-none');
                }

                $('.class_session_study_class').text(studyClassName);
                $('.class_session_position').text(position === 0 ? 'Trực tiếp tại trường' : position === 1 ? 'Trực tuyến' : 'Dã ngoại');
                $('.class_session_proposed_at').text(moment(proposedAt).format('H:mm DD/MM/YYYY'));
                $('.class_session_location').text(location);
                $('.class_session_meeting_type').text(meetingType);
                $('.class_session_meeting_id').text(meetingId);
                $('.class_session_meeting_password').text(meetingPassword);
                $('.class_session_meeting_url').attr('href', meetingUrl);
                $('.class_session_note').text(!note ? '---' : note);
                $('.class_session_position_input').val(position);
                $('.class_session_room_id').val(roomId || '');

                $('#formConfirm').attr('action', `{{ route('student-affairs-department.class-session.updateClassRequest', '') }}/${classId}`);
            });

            $('.btn-detail-class').on('click', function () {
                const classId = $(this).data('id');
                const studyClassName = $(this).data('study-class-name');
                const type = $(this).data('type');
                const position = $(this).data('position');
                const proposedAt = $(this).data('proposed_at');
                const location = $(this).data('location');
                const meetingType = $(this).data('meeting-type');
                const meetingId = $(this).data('meeting-id');
                const meetingPassword = $(this).data('meeting-password');
                const meetingUrl = $(this).data('meeting-url');
                const note = $(this).data('note');
                const roomName = $(this).data('room-name') || '---';
                const roomId = $(this).data('room-id');
                const roomDescription = $(this).data('room-description') || '---';
                const status = $(this).data('status');
                const isDetail = $(this).data('detail');

                if (status === 1 && isDetail) {
                    $('.btn-confirm-form').addClass('d-none');
                }

                if (position === 0) {
                    $('.session-offline').addClass('d-none');
                    $('.session-online').addClass('d-none');
                    $('.session-picnic').addClass('d-none');
                    $('.class_session_room').removeClass('d-none');
                } else if (position === 1) {
                    $('.session-offline').addClass('d-none');
                    $('.session-online').removeClass('d-none');
                    $('.session-picnic').addClass('d-none');
                    $('.class_session_room').addClass('d-none');
                } else {
                    $('.session-offline').addClass('d-none');
                    $('.session-online').addClass('d-none');
                    $('.session-picnic').removeClass('d-none');
                    $('.class_session_room').addClass('d-none');
                }

                $('.class_session_study_class').text(studyClassName);
                $('.class_session_position').text(position === 0 ? 'Trực tiếp tại trường' : position === 1 ? 'Trực tuyến' : 'Dã ngoại');
                $('.class_session_proposed_at').text(moment(proposedAt).format('H:mm DD/MM/YYYY'));
                $('.class_session_location').text(location);
                $('.class_session_meeting_type').text(meetingType);
                $('.class_session_meeting_id').text(meetingId);
                $('.class_session_meeting_password').text(meetingPassword);
                $('.class_session_meeting_url').attr('href', meetingUrl);
                $('.class_session_note').text(!note ? '---' : note);
                $('.class_session_room_name').text(roomName);
                $('.class_session_room_description').text(roomDescription);
            });

            $('.btn-confirm-form').on('click', function (e) {
                e.preventDefault();

                const rejectionReason = $('#rejection_reason').val().trim();
                if (isRejectMode && !rejectionReason) {
                    $('#rejection_reason').focus();
                    return;
                }

                const position = $('.class_session_position_input').val();
                if (position === '0') {
                    const roomId = $('#room').val();
                    if (!roomId) {
                        $('#formConfirm input[name="room_id"]').remove();
                        $('#formConfirm').append('<input type="hidden" name="room_id" value="">');
                    }
                } else {
                    $('#formConfirm input[name="room_id"]').remove();
                }

                $('#formConfirm').submit();
            });
        });
    </script>
@endpush
