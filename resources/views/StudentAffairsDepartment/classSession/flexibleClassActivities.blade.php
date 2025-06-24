@extends('layouts.studentAffairsDepartment')

@section('title', 'Sinh hoạt lớp Linh hoạt')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Sinh hoạt lớp linh hoạt']]"/>
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
            <h4 class="text-center mb-4 fw-bold">Sinh hoạt lớp linh hoạt</h4>
            <!-- Table -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col" class="px-4 py-3">STT</th>
                                <th scope="col" class="px-4 py-3">Tên lớp</th>
                                <th scope="col" class="px-4 py-3">Thời gian</th>
                                <th scope="col" class="px-4 py-3">Hình thức</th>
                                <th scope="col" class="px-4 py-3">Trạng thái</th>
                                <th scope="col" class="px-4 py-3 text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (isset($data['ListCSRs']['data']) && $data['ListCSRs']['total'] > 0)
                                @foreach ($data['ListCSRs']['data'] as $index => $class)
                                    <tr>
                                        <td class="px-4 py-3">
                                            {{ ($data['ListCSRs']['current_page'] - 1) * $data['ListCSRs']['per_page'] + $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <strong>{{ $class['study_class']['name'] }}</strong>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ \Carbon\Carbon::parse($class['proposed_at'])->format('H:i d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($class['position'] == 0)
                                                <span class="badge bg-success">Trực tiếp tại trường</span>
                                            @elseif ($class['position'] == 1)
                                                <span class="badge bg-primary">Trực tuyến</span>
                                            @elseif ($class['position'] == 2)
                                                <span class="badge bg-warning">Dã ngoại</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($class['status'] == 0)
                                                <span class="badge bg-secondary">Chưa xét duyệt</span>
                                            @elseif ($class['status'] == 1)
                                                <span class="badge bg-success">Đã xét duyệt</span>
                                            @elseif ($class['status'] == 2)
                                                <span class="badge bg-danger">Đã từ chối</span>
                                            @endif
                                        </td>
                                        <td class="btn-group gap-2">
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
                                                    data-study-class-name="{{ $class['study_class']['name'] }}"
                                                    data-note="{{ $class['note'] }}"
                                                    data-room-name="{{ $class['room']['name'] ?? '' }}"
                                                    data-room-id="{{ $class['room']['id'] ?? '' }}"
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
                                                data-study-class-name="{{ $class['study_class']['name'] }}"
                                                data-note="{{ $class['note'] }}"
                                                data-room-name="{{ $class['room']['name'] ?? '' }}"
                                                data-room-id="{{ $class['room']['id'] ?? '' }}"
                                                data-status="{{ $class['status'] }}"
                                                data-detail="true"
                                            >
                                                <i class="fas fa-info-circle"></i>
                                            </button>
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

            {{-- Modal Hình thức họp --}}
            <div class="modal fade auto-reset-modal" id="formModal" tabindex="-1" aria-labelledby="formModalLabel"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="formModalLabel">Xét duyệt sinh hoạt lớp linh hoạt</h5>
                                <button type="button" class="btn btn-outline-danger" id="btnReject">Huỷ đăng ký</button>
                            </div>

                            <form id="formConfirm" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" class="class_session_status" value="1">
                                <input type="hidden" name="position" class="class_session_position_input">
                                <input type="hidden" name="type" value="1">
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

                                <h6 class="class_session_room d-none">Phòng: <span
                                        class="class_session_room_name"></span></h6>
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
                            @foreach($data['rooms'] as $room)
                            roomSelect.append(`<option value="{{ $room->id }}">{{ $room->name }}</option>`);
                            @endforeach
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
