@extends('layouts.studentAffairsDepartment')

@section('title', 'Sinh hoạt lớp')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Sinh hoạt lớp']]" />
@endsection

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="p-4">
            @if ($data['checkClassSessionRegistration'])
                <div class="d-flex justify-content-between align-items-start mb-4">
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

                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmEditModal"
                            data-id="#">Chỉnh sửa thời gian</button>
                    </div>
                    <div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Tìm kiếm lớp học"
                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <button class="input-group-text" id="basic-addon2"><i
                                    class="fas fa-magnifying-glass"></i></button>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary"
                                href="{{ route('student-affairs-department.class-session.history') }}">
                                Lịch sử
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    @if (isset($data['ListCSRs']) && $data['ListCSRs']->total() > 0)
                        @foreach ($data['ListCSRs'] as $class)
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-body position-relative">
                                        <h5 class="card-title">{{ $class['name'] }}</h5>
                                        <div class="mt-3">
                                            <p class="mb-1">Thời gian:
                                                {{ \Carbon\Carbon::parse($class['proposed_at'])->format('H:i d/m/Y') }}</p>
                                            <p class="mb-1">Hình thức:
                                                @if ($class['position'] == 0)
                                                    Trực tiếp tại trường
                                                @elseif ($class['position'] == 1)
                                                    Trực tuyến
                                                @elseif ($class['position'] == 2)
                                                    Dã ngoại
                                                @endif
                                            </p>
                                        </div>
                                        <button class="btn btn-primary position-absolute"
                                            style="top: 10px; right: 10px; border-radius: 5px;" data-bs-toggle="modal"
                                            data-bs-target="#onlineModal" data-id="#">
                                            Xem chi tiết
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-md-12">
                            <div class="d-flex justify-content-center mt-4">
                                @include('components.pagination.pagination', [
                                    'paginate' => $data['ListCSRs'],
                                ])
                            </div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="text-center alert alert-warning" role="alert">
                                Chưa có lớp nào đăng ký.
                            </div>
                        </div>
                    @endif
                </div>
            @else
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="">
                    <button class="btn btn-primary btn-create-class-session"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmCreateModal" data-id="#">
                        Tạo lịch sinh hoạt lớp cố định
                    </button>
                </div>
            </div>
                <div class="mt-5">
                    <div class="text-center alert alert-warning" role="alert">
                        Chưa có lịch sinh hoạt lớp.
                    </div>
                </div>

            @endif

            <!-- Modal Tạo mới -->
            <div class="modal fade auto-reset-modal" id="confirmCreateModal" tabindex="-1"
                aria-labelledby="confirmCreateModalLabel" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="createModalLabel">Đăng ký sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger btnReset" id="btnReset">Đặt
                                    lại</button>
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
                                        style="width: 120px;">Quay lại</button>
                                    <button type="submit" class="btn btn-primary btn-create-submit"
                                        style="width: 120px;">Đăng ký</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Sửa -->
            @if (isset($data['classSessionRegistration']) && $data['classSessionRegistration'])
                <div class="modal fade auto-reset-modal" id="confirmEditModal" tabindex="-1"
                    aria-labelledby="confirmEditModalLabel" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="modal-title fw-bold" id="editModalLabel">Đăng ký sinh hoạt lớp cố
                                        định</h5>
                                    <button type="button" class="btn btn-outline-danger btnReset" id="btneditReset">Đặt
                                        lại</button>
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
                                            placeholder="Chọn thời gian bắt đầu" onfocus="(this.type='datetime-local')"
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
                                            placeholder="Chọn thời gian kết thúc" onfocus="(this.type='datetime-local')"
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
                                            style="width: 120px;">Quay lại</button>
                                        <button type="submit" class="btn btn-primary btn-edit-submit"
                                            style="width: 120px;">Đăng
                                            ký</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Modal Hình thức offline --}}
            <div class="modal fade auto-reset-modal" id="offlineModal" tabindex="-1"
                aria-labelledby="offlineModalLabel" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="offlineModalLabel">Xét duyệt sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger btnReset" id="offlineBtnReset">Huỷ
                                    đăng
                                    ký</button>
                            </div>

                            <form id="offlineForm" method="POST" action="#">
                                @csrf

                                <h6>Hình thức: Trực tiếp tại trường</h6>
                                <h6>Thời gian: 7h00 - 20/04/2025</h6>
                                <h6>Ghi chú: Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quam exercitationem
                                    labore earum tempore iure. Atque aliquid, neque sit velit unde nemo possimus praesentium
                                    ut, consectetur maiores beatae cum quos qui.</h6>

                                <div class="mb-3">
                                    <label for="sinhHoatLopThoiGianKetThuc" class="form-label">Chọn phòng học:</label>
                                    <select name="" id="sinhHoatLopThoiGianKetThuc" class="form-select">
                                        <option value="1">230A6</option>
                                        <option value="2">230A8</option>
                                        <option value="3">230A9</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                        style="width: 120px;">Quay lại</button>
                                    <button type="submit" class="btn btn-primary" style="width: 120px;">Đăng ký</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Hình thức online --}}
            <div class="modal fade auto-reset-modal" id="onlineModal" tabindex="-1" aria-labelledby="onlineModalLabel"
                >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="onlineModalLabel">Xét duyệt sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger btnReset" id="onlineBtnReset">Huỷ
                                    đăng
                                    ký</button>
                            </div>

                            <form id="onlineForm" method="POST" action="#">
                                @csrf

                                <h6>Hình thức: Trực tuyến</h6>
                                <h6>Thời gian: 7h00 - 20/04/2025</h6>
                                <h6>Nền tảng tổ chức họp: Microsoft teams</h6>
                                <h6>Phòng họp: 1000000</h6>
                                <h6>Link họp: url::lkjdflajs.sdf</h6>
                                <h6>Mật khẩu: 1234556</h6>

                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                        style="width: 120px;">Quay lại</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Hình thức dã ngoại --}}
            <div class="modal fade auto-reset-modal" id="picnicModal" tabindex="-1" aria-labelledby="picnicModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="picnicModalLabel">Xét duyệt sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger btnReset" id="picnicBtnReset">Huỷ
                                    đăng
                                    ký</button>
                            </div>

                            <form id="picnicForm" method="POST" action="#">
                                @csrf

                                <h6>Hình thức: Dã ngoại</h6>
                                <h6>Thời gian: 7h00 - 20/04/2025</h6>
                                <h6>Địa điểm: Công viên ABC</h6>
                                <h6>Ghi chú: Mang theo đồ ăn trưa và nước uống</h6>

                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                        style="width: 120px;">Quay lại</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Reset form when modal is closed
            $('.auto-reset-modal').on('hidden.bs.modal', function() {
                $('.text-danger-error').text('');
                $(this).find('form')[0].reset();
            });

            // Reset form when button is clicked
            $('.btnReset').click(function() {
                $('.text-danger-error').text('');
                $('#createform')[0].reset();
            });

            $('.btn-create-submit').on('click', function(e) {
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

            $('.btn-edit-submit').on('click', function(e) {
                e.preventDefault();

                let openDate = $('#open_date_edit').val();
                let endDate = $('#end_date_edit').val();
                let semesterId = $('#semester_id_edit').val();

                $('#open_date_error_edit').text('');
                $('#end_date_error_edit').text('');

                if (semesterId == null) {
                    $('#semester_id_error-edit').text(
                        'Vui lòng tạo học kỳ trước khi tạo lịch sinh hoạt lớp');
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

        });
    </script>
@endpush
