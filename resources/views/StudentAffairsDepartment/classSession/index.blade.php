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
            {{-- có lịch sinh hoạt --}}
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4>Học kỳ: 2_2024_2025</h4>
                    <p class="p-0 m-0">Thời gian bắt đầu: 20:00 12/04/2025</p>
                    <p class="p-0 m-0">Thời gian kết thúc: 20:00 15/04/2025</p>
                    <p class="p-0 m-0">Thời gian kết thúc: 20:00 15/04/2025</p>
                    <p class="p-0">Số lớp đăng đã đăng ký: 60/60</p>

                    <a class="btn btn-warning" href="#">Chỉnh sửa thời gian</a>
                </div>
                <div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Tìm kiếm học kỳ"
                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <button class="input-group-text" id="basic-addon2"><i class="fas fa-magnifying-glass"></i></button>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-primary" href="{{ route('student-affairs-department.class-session.history') }}">
                            Lịch sử
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body position-relative">
                            <h5 class="card-title">63KTPM2</h5>
                            <div class="mt-3">
                                <p class="mb-1">Thời gian: 20:00 20/04/2025</p>
                                <p class="mb-1">Hình thức: Trực tuyến</p>
                            </div>
                            <button class="btn btn-primary position-absolute"
                                style="top: 10px; right: 10px; border-radius: 5px;" data-bs-toggle="modal"
                                data-bs-target="#onlineModal" data-id="#">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body position-relative">
                            <h5 class="card-title">64KTPM1</h5>
                            <div class="mt-3">
                                <p class="mb-1">Thời gian: 20:00 20/04/2025</p>
                                <p class="mb-1">Hình thức: Trực tiếp tại trường</p>
                            </div>
                            <button class="btn btn-primary position-absolute"
                                style="top: 10px; right: 10px; border-radius: 5px;"
                                data-bs-toggle="modal" data-bs-target="#offlineModal" data-id="#">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body position-relative">
                            <h5 class="card-title">64KTPM2</h5>
                            <div class="mt-3">
                                <p class="mb-1">Thời gian: 20:00 20/04/2025</p>
                                <p class="mb-1">Hình thức: Dã ngoại</p>
                            </div>
                            <button class="btn btn-primary position-absolute"
                                style="top: 10px; right: 10px; border-radius: 5px;"
                                data-bs-toggle="modal" data-bs-target="#picnicModal" data-id="#">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Có lịch sinh hoạt --}}

            {{-- Rỗng --}}
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4>Chưa có lịch sinh hoạt lớp</h4>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-primary" href="{{ route('student-affairs-department.class-session.history') }}">
                        Lịch sử
                    </a>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center h-100">
                <button class="btn btn-primary btn-create-class-session" style="top: 10px; right: 10px; border-radius: 5px;" data-bs-toggle="modal"
                    data-bs-target="#confirmCreateModal" data-id="#">
                    Tạo lịch sinh hoạt lớp
                </button>
            </div>
            {{-- Rỗng --}}

            <!-- Modal Tạo mới -->
            <div class="modal fade" id="confirmCreateModal" tabindex="-1" aria-labelledby="confirmCreateModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="createModalLabel">Đăng ký sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger" id="btnReset">Đặt lại</button>
                            </div>

                            <form id="createform" method="POST" action="#">
                                @csrf

                                <div class="mb-3">
                                    <label for="hocKy" class="form-label">Học kỳ</label>
                                    <select class="form-select" id="hocKy" name="hoc_ky" required>
                                        @if(empty($semesters))
                                            <option value="" disabled>Chưa có học kỳ nào</option>
                                        @else
                                            @foreach ($semesters as $semester)
                                                <option value="{{ $semester->id }}">{{ $semester->name }} - {{ $semester->school_year }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="thoiGianBatDau" class="form-label">Thời gian bắt đầu</label>
                                    <input type="text" class="form-control" id="thoiGianBatDau" name="thoi_gian_bat_dau"
                                        placeholder="Chọn thời gian bắt đầu" onfocus="(this.type='datetime-local')"
                                        onblur="if(!this.value)this.type='text'" required>
                                </div>

                                <div class="mb-3">
                                    <label for="thoiGianKetThuc" class="form-label">Thời gian kết thúc</label>
                                    <input type="text" class="form-control" id="thoiGianKetThuc"
                                        name="thoi_gian_ket_thuc" placeholder="Chọn thời gian kết thúc"
                                        onfocus="(this.type='datetime-local')" onblur="if(!this.value)this.type='text'"
                                        required>
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

            {{-- Modal Hình thức offline --}}
            <div class="modal fade" id="offlineModal" tabindex="-1" aria-labelledby="offlineModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="offlineModalLabel">Xét duyệt sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger" id="BtnReset">Huỷ đăng ký</button>
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
            <div class="modal fade" id="onlineModal" tabindex="-1" aria-labelledby="onlineModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="onlineModalLabel">Xét duyệt sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger" id="onlineBtnReset">Huỷ đăng
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
            <div class="modal fade" id="picnicModal" tabindex="-1" aria-labelledby="picnicModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="modal-title fw-bold" id="picnicModalLabel">Xét duyệt sinh hoạt lớp cố
                                    định</h5>
                                <button type="button" class="btn btn-outline-danger" id="picnicBtnReset">Huỷ đăng
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

@section('script')

<script>
$(document).ready(function () {

});
</script>

@endsection
