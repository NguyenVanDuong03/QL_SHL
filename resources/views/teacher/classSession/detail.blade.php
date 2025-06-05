@extends('layouts.teacher')

@section('title', 'Sinh hoạt lớp cố định')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'teacher.class-session.index'],
        ['label' => 'Sinh hoạt lớp cố định', 'url' => 'teacher.class-session.fixed-class-activitie'],
        ['label' => 'Đăng ký sinh hoạt lớp']
    ]"/>
@endsection

@push('styles')
    <style>
        .card {
            border: none;
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .alert {
            border-radius: 8px;
        }

        .btn {
            border-radius: 6px;
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
        }

        .input-group .btn {
            border-left: 0;
        }

        .note-icon:hover {
            color: #0dcaf0 !important;
            transform: scale(1.1);
        }

        .note-content {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .note-content small {
            font-size: 0.75rem;
            line-height: 1.4;
            border-left: 3px solid #0dcaf0;
        }

        @media (max-width: 768px) {
            .mx-3 {
                margin-left: 1rem !important;
                margin-right: 1rem !important;
            }

            .modal-fullscreen-md-down {
                width: 100vw;
                max-width: none;
                height: 100vh;
                margin: 0;
            }

            .modal-fullscreen-md-down .modal-content {
                height: 100vh;
                border: 0;
                border-radius: 0;
            }

            .modal-fullscreen-md-down .modal-body {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .mobile-student-card {
                border-radius: 8px !important;
            }

            .form-check-input {
                margin: 0;
                cursor: pointer;
            }

            .btn-group-sm .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            .btn {
                min-height: 44px;
                padding: 0.5rem 1rem;
            }

            .form-check-input {
                width: 1.25em;
                height: 1.25em;
            }

            .btn-close {
                padding: 0.75rem;
            }
        }

        /* Desktop styles */
        @media (min-width: 768px) {
            .table th {
                border-top: none;
                font-weight: 600;
                font-size: 0.875rem;
            }

            .table td {
                vertical-align: middle;
                font-size: 0.875rem;
            }

            .sticky-top {
                position: sticky;
                top: 0;
                z-index: 10;
                background-color: #f8f9fa !important;
            }
        }

        /* Common styles */
        .form-check-input {
            margin: 0;
        }

        .badge {
            font-size: 0.75rem;
        }

        .card {
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        #mobileStudentList {
            overflow-y: auto;
        }

    </style>
@endpush

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
                    <div class="d-flex justify-content-center align-items-center">
                        <h3 class="fw-bold d-none d-md-block">Chi tiết sinh hoạt lớp</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="my-4 mx-3">
            <div class="row">
                <!-- Cột trái - Thông tin chính -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div
                            class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-info-circle text-secondary me-2"></i>Thông tin sinh hoạt
                                lớp</h5>
                            <!-- Status badge -->
                            @php
                                $statusConfig = [
                                    0 => ['text' => 'Chờ duyệt', 'class' => 'bg-warning', 'icon' => 'fas fa-clock'],
                                    1 => ['text' => 'Đã duyệt', 'class' => 'bg-success', 'icon' => 'fas fa-check-circle'],
                                    2 => ['text' => 'Đã từ chối', 'class' => 'bg-danger', 'icon' => 'fas fa-times-circle']
                                ];
                                $currentStatus = $statusConfig[$data['getClassSessionRequest']->status ?? 0];
                            @endphp
                            <span class="badge {{ $currentStatus['class'] }} fs-6">
                                <i class="{{ $currentStatus['icon'] }} me-1"></i>{{ $currentStatus['text'] }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">Tiêu đề:</label>
                                <h5 class="text-dark">{{ $data['getClassSessionRequest']->title }}</h5>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">Nội dung chính:</label>
                                <div class="border rounded p-3 bg-light" style="min-height: 200px;">
                                    <p class="text-dark mb-0"
                                       style="white-space: pre-line;">{{ $data['getClassSessionRequest']->content ?? 'Không có nội dung' }}</p>
                                </div>
                            </div>

                            <!-- Rejection reason if status = 2 -->
                            @if(($data['getClassSessionRequest']->status ?? 0) == 2 && !empty($data['getClassSessionRequest']->rejection_reason))
                                <div class="mb-4">
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Lý do từ chối
                                        </h6>
                                        <p class="mb-0">{{ $data['getClassSessionRequest']->rejection_reason }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Action buttons -->
{{--                            <div class="d-flex justify-content-end gap-2">--}}
{{--                               @if(($data['getClassSessionRequest']->status ?? 0) == 1)--}}
{{--                                    <button type="button" class="btn btn-info" data-bs-target="#attendanceModal"--}}
{{--                                            data-bs-toggle="modal">--}}
{{--                                        <i class="fas fa-users me-2"></i>Danh sách tham gia--}}
{{--                                    </button>--}}
{{--                                @endif--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <!-- Cột phải - Chi tiết hình thức -->
                <div class="col-md-4 mt-md-0 mt-3">
                    <div class="card shadow-sm">
                        @php
                            $position = $data['getClassSessionRequest']->position ?? 0;
                            $positionConfig = [
                                0 => ['title' => 'Trực tiếp tại trường', 'class' => 'bg-success text-white', 'icon' => 'fas fa-building'],
                                1 => ['title' => 'Trực tuyến', 'class' => 'bg-primary text-white', 'icon' => 'fas fa-video'],
                                2 => ['title' => 'Dã ngoại', 'class' => 'bg-warning text-white', 'icon' => 'fas fa-tree']
                            ];
                            $currentPosition = $positionConfig[$position];
                        @endphp
                        <div class="card-header {{ $currentPosition['class'] }}">
                            <h6 class="mb-0 fw-bold">
                                <i class="{{ $currentPosition['icon'] }} me-2"></i>{{ $currentPosition['title'] }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label
                                    class="form-label fw-bold {{ $position == 0 ? 'text-success' : ($position == 1 ? 'text-primary' : 'text-warning') }}">Thời
                                    gian:</label>
                                <p class="text-dark mb-0 fs-5">
                                    <i class="fas fa-calendar-alt me-2 {{ $position == 0 ? 'text-success' : ($position == 1 ? 'text-primary' : 'text-warning') }}"></i>
                                    {{ \Carbon\Carbon::parse($data['getClassSessionRequest']->proposed_at)->format('H:i d/m/Y') }}
                                </p>
                            </div>

                            @if($position == 0)
                                <!-- Trực tiếp tại trường -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-success">Phòng học:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->room->name ?? 'Chưa có phòng' }}</p>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Sinh viên cần có mặt tại phòng học đúng giờ quy định.</small>
                                </div>
                            @elseif($position == 1)
                                <!-- Trực tuyến -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">Nền tảng:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->meeting_type ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">Mã cuộc họp:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               value="{{ $data['getClassSessionRequest']->meeting_id ?? '---' }}"
                                               readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="copyToClipboard(this.previousElementSibling)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                @if(!empty($data['getClassSessionRequest']->meeting_password))
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-primary">Mật khẩu:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   value="{{ $data['getClassSessionRequest']->meeting_password }}"
                                                   readonly>
                                            <button class="btn btn-outline-secondary" type="button"
                                                    onclick="copyToClipboard(this.previousElementSibling)">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <div class="d-grid">
                                        <a href="{{ $data['getClassSessionRequest']->meeting_url ?? '#' }}"
                                           target="_blank"
                                           class="btn btn-primary">
                                            <i class="fas fa-video me-2"></i>Tham gia cuộc họp
                                        </a>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <small>Kiểm tra kết nối internet và thiết bị trước khi tham gia.</small>
                                </div>
                            @else
                                <!-- Dã ngoại -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning">Địa điểm:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->location ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <div class="d-grid">
                                        <button class="btn btn-warning"
                                                onclick="openMap('{{ $data['getClassSessionRequest']->location ?? '---' }}')">
                                            <i class="fas fa-map-marker-alt me-2"></i>Xem trên bản đồ
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-leaf me-2"></i>
                                    <small>Chuẩn bị đầy đủ đồ dùng cá nhân và tuân thủ quy định an toàn.</small>
                                </div>
                            @endif

                            @if(!empty($data['getClassSessionRequest']->note))
                                <div class="mt-4">
                                    <label class="form-label fw-bold text-secondary">Ghi chú:</label>
                                    <div class="border rounded p-2 bg-light">
                                        <p class="text-dark mb-0"
                                           style="white-space: pre-line;">{{ $data['getClassSessionRequest']->note }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">
                                <i class="fas fa-info me-2"></i>Thông tin bổ sung
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="mb-2">
                                        <small class="text-muted">Học kỳ:</small>
                                        <p class="mb-0">{{ $data['getCSRSemesterInfo']->name }}
                                            - {{ $data['getCSRSemesterInfo']->school_year }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Lớp:</small>
                                        <p class="mb-0">{{ $data['getStudyClassByIds']->name }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Ngày tạo:</small>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->created_at ?? now())->format('H:i d/m/Y') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Cập nhật lần cuối:</small>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->updated_at ?? now())->format('H:i d/m/Y') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Loại sinh hoạt:</small>
                                        <p class="mb-0">
                                            @php
                                                $type = $data['getClassSessionRequest']->type ?? 0;
                                                echo $type == 0 ? 'SHL cố định' : 'SHL linh hoạt';
                                            @endphp
                                        </p>
                                    </div>
                                </div>
{{--                                <div class="col-6">--}}
{{--                                    <div class="mb-2">--}}
{{--                                        <small class="text-muted">Học kỳ:</small>--}}
{{--                                        <p class="mb-0">{{ $data['getCSRSemesterInfo']->name }}--}}
{{--                                            - {{ $data['getCSRSemesterInfo']->school_year }}</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="mb-2">--}}
{{--                                        <small class="text-muted">Lớp:</small>--}}
{{--                                        <p class="mb-0">{{ $data['getStudyClassByIds']->name }}</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="mb-2">--}}
{{--                                        <small class="text-muted">Ngày tạo:</small>--}}
{{--                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->created_at ?? now())->format('H:i d/m/Y') }}</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="mb-2">--}}
{{--                                        <small class="text-muted">Cập nhật lần cuối:</small>--}}
{{--                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->updated_at ?? now())->format('H:i d/m/Y') }}</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="mb-2">--}}
{{--                                        <small class="text-muted">Loại sinh hoạt:</small>--}}
{{--                                        <p class="mb-0">--}}
{{--                                            @php--}}
{{--                                                $type = $data['getClassSessionRequest']->type ?? 0;--}}
{{--                                                echo $type == 0 ? 'SHL cố định' : 'SHL linh hoạt';--}}
{{--                                            @endphp--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Modal -->
{{--    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel"--}}
{{--         aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-fullscreen-md-down modal-lg">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header text-black">--}}
{{--                    <h5 class="modal-title" id="attendanceModalLabel">--}}
{{--                        <i class="fas fa-users me-2"></i>Danh sách sinh viên--}}
{{--                    </h5>--}}
{{--                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"--}}
{{--                            aria-label="Close"></button>--}}
{{--                </div>--}}
{{--                <div class="modal-body p-0">--}}
{{--                    <!-- Quick Stats -->--}}
{{--                    <div class="bg-light p-2 p-md-3 border-bottom">--}}
{{--                        <div class="row text-center g-2">--}}
{{--                            <div class="col-6 col-md-3">--}}
{{--                                <div class="p-2 bg-white rounded">--}}
{{--                                    <h6 class="mb-0 text-primary fs-6" id="totalStudents">45</h6>--}}
{{--                                    <small class="text-muted d-block">Tổng số</small>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-6 col-md-3">--}}
{{--                                <div class="p-2 bg-white rounded">--}}
{{--                                    <h6 class="mb-0 text-success fs-6" id="confirmedStudents">38</h6>--}}
{{--                                    <small class="text-muted d-block">Xác nhận</small>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-6 col-md-3">--}}
{{--                                <div class="p-2 bg-white rounded">--}}
{{--                                    <h6 class="mb-0 text-danger fs-6" id="absentStudents">5</h6>--}}
{{--                                    <small class="text-muted d-block">Xin vắng</small>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-6 col-md-3">--}}
{{--                                <div class="p-2 bg-white rounded">--}}
{{--                                    <h6 class="mb-0 text-warning fs-6" id="attendedStudents">0</h6>--}}
{{--                                    <small class="text-muted d-block">Có mặt</small>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Search -->--}}
{{--                    <div class="p-2 p-md-3 border-bottom">--}}
{{--                        <div class="input-group">--}}
{{--                            <span class="input-group-text"><i class="fas fa-search"></i></span>--}}
{{--                            <input type="text" class="form-control" id="searchStudent"--}}
{{--                                   placeholder="Tìm kiếm sinh viên...">--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="d-md-none" id="mobileView">--}}
{{--                        <div id="mobileStudentList" class="p-2">--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="d-none d-md-block">--}}
{{--                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">--}}
{{--                            <table class="table table-hover mb-0">--}}
{{--                                <thead class="table-light sticky-top">--}}
{{--                                <tr>--}}
{{--                                    <th scope="col" style="width: 8%">#</th>--}}
{{--                                    <th scope="col" style="width: 30%">Tên sinh viên</th>--}}
{{--                                    <th scope="col" style="width: 20%">Trạng thái</th>--}}
{{--                                    <th scope="col" style="width: 35%">Lý do vắng</th>--}}
{{--                                    <th scope="col" style="width: 7%" class="text-center">Có mặt</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody id="studentsTableBody">--}}
{{--                                <tr class="student-row" data-status="confirmed" data-student-id="20210001"--}}
{{--                                    data-student-name="Nguyễn Văn An">--}}
{{--                                    <td>1</td>--}}
{{--                                    <td class="fw-medium">--}}
{{--                                        <p class="m-0">Nguyễn Văn An</p>--}}
{{--                                        <small>#20210001</small>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-success">--}}
{{--                                            <i class="fas fa-check me-1"></i>Đã xác nhận--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210001">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình <i--}}
{{--                                                class="fas fa-info-circle text-info ms-1 note-icon"--}}
{{--                                                style="font-size: 0.8rem; cursor: pointer;"--}}
{{--                                                data-note="Sinh viên học tập tích cực, thường xuyên tham gia các hoạt động lớp. Cần theo dõi thêm về việc nộp bài tập đúng hạn."></i>--}}
{{--                                        </p>--}}
{{--                                        <small>#20210001</small>--}}
{{--                                        <div class="note-content" style="display: none; margin-top: 5px;">--}}
{{--                                            <small class="text-primary bg-light p-2 rounded d-block">--}}
{{--                                                <strong>Ghi chú:</strong> <span class="note-text"></span>--}}
{{--                                            </small>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr class="student-row" data-status="pending" data-student-id="20210002"--}}
{{--                                    data-student-name="Lê Thị Bình">--}}
{{--                                    <td>2</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-warning">--}}
{{--                                            <i class="fas fa-clock me-1"></i>Chưa phản hồi--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210002">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                <tr class="student-row" data-status="absent" data-student-id="20210003"--}}
{{--                                    data-student-name="Phạm Văn Cường" data-absent-reason="Có việc gia đình đột xuất">--}}
{{--                                    <td>3</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-danger">--}}
{{--                                            <i class="fas fa-times me-1"></i>Xin vắng--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <small class="text-danger">Có việc gia đình đột xuất</small>--}}
{{--                                    </td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <span class="text-muted"><i class="fas fa-ban"></i></span>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                <tr class="student-row" data-status="confirmed" data-student-id="20210004"--}}
{{--                                    data-student-name="Hoàng Thị Dung">--}}
{{--                                    <td>4</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-success">--}}
{{--                                            <i class="fas fa-check me-1"></i>Đã xác nhận--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td><span class="text-muted">-</span></td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <input class="form-check-input attendance-checkbox" type="checkbox"--}}
{{--                                               value="20210004">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                <tr class="student-row" data-status="absent" data-student-id="20210005"--}}
{{--                                    data-student-name="Nguyễn Minh Em" data-absent-reason="Đi khám bệnh theo lịch hẹn">--}}
{{--                                    <td>5</td>--}}
{{--                                    <td class="fw-medium"><p class="m-0">Lê Thị Bình</p>--}}
{{--                                        <small>#20210001</small></td>--}}
{{--                                    <td>--}}
{{--                                        <span class="badge bg-danger">--}}
{{--                                            <i class="fas fa-times me-1"></i>Xin vắng--}}
{{--                                        </span>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <small class="text-danger">Đi khám bệnh theo lịch hẹn</small>--}}
{{--                                    </td>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <span class="text-muted"><i class="fas fa-ban"></i></span>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- No results -->--}}
{{--                    <div id="noResults" class="text-center py-4 d-none">--}}
{{--                        <i class="fas fa-search fa-2x text-muted mb-2"></i>--}}
{{--                        <p class="text-muted">Không tìm thấy sinh viên</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="modal-footer p-2 p-md-3">--}}
{{--                    <!-- Mobile Footer -->--}}
{{--                    <div class="d-md-none w-100">--}}
{{--                        <div class="d-flex justify-content-between align-items-center mb-2">--}}
{{--                            <div class="btn-group btn-group-sm">--}}
{{--                                <button class="btn btn-outline-success" onclick="checkAllAttendance()">--}}
{{--                                    <i class="fas fa-check-double"></i>--}}
{{--                                </button>--}}
{{--                                <button class="btn btn-outline-secondary" onclick="uncheckAllAttendance()">--}}
{{--                                    <i class="fas fa-times"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                            <span class="text-muted small">Đã chọn: <strong id="selectedCountMobile">0</strong></span>--}}
{{--                        </div>--}}
{{--                        <div class="d-grid gap-2">--}}
{{--                            <button type="button" class="btn btn-success" onclick="saveAttendance()">--}}
{{--                                <i class="fas fa-save me-2"></i>Lưu điểm danh--}}
{{--                            </button>--}}
{{--                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Desktop Footer -->--}}
{{--                    <div class="d-none d-md-flex justify-content-between align-items-center w-100">--}}
{{--                        <div>--}}
{{--                            <button class="btn btn-outline-success btn-sm me-2" onclick="checkAllAttendance()">--}}
{{--                                <i class="fas fa-check-double me-1"></i>Chọn tất cả--}}
{{--                            </button>--}}
{{--                            <button class="btn btn-outline-secondary btn-sm" onclick="uncheckAllAttendance()">--}}
{{--                                <i class="fas fa-times me-1"></i>Bỏ chọn--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <span class="text-muted me-3">Đã chọn: <strong id="selectedCount">0</strong></span>--}}
{{--                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Đóng</button>--}}
{{--                            <button type="button" class="btn btn-success" onclick="saveAttendance()">--}}
{{--                                <i class="fas fa-save me-2"></i>Lưu điểm danh--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection

@push('scripts')
    <script>
        function copyToClipboard(element) {
            element.select();
            element.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(element.value).then(function () {
                // Show success message
                showToast('Đã sao chép vào clipboard!', 'success');
            });
        }

        function openMap(location) {
            if (location) {
                const encodedLocation = encodeURIComponent(location);
                window.open(`https://www.google.com/maps/search/${encodedLocation}`, '_blank');
            }
        }

        function showToast(message, type = 'info') {
            // Simple toast implementation
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'info'} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>
                ${message}
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>

    <script>
        $(document).ready(function () {
            generateMobileCards();

            // Search
            $('#searchStudent').on('input', function () {
                const searchTerm = $(this).val().toLowerCase();
                let visibleCount = 0;

                // Desktop rows
                $('.student-row').each(function () {
                    const studentName = $(this).data('student-name').toLowerCase();
                    const studentId = $(this).data('student-id').toLowerCase();

                    if (studentName.includes(searchTerm) || studentId.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                // Mobile cards
                $('.mobile-student-card').each(function () {
                    const studentName = $(this).data('student-name').toLowerCase();
                    const studentId = $(this).data('student-id').toLowerCase();

                    if (studentName.includes(searchTerm) || studentId.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                $('#noResults').toggleClass('d-none', visibleCount > 0);
            });

            // Checkbox change
            $(document).on('change', '.attendance-checkbox', function () {
                updateCounts();
            });

            updateCounts();

            // Xử lý click vào icon ghi chú
            $('.note-icon').click(function() {
                var $this = $(this);
                var $noteContent = $this.closest('td').find('.note-content');
                var $noteText = $noteContent.find('.note-text');
                var noteData = $this.data('note');

                // Ẩn tất cả note khác
                $('.note-content').not($noteContent).slideUp(200);

                // Toggle note hiện tại
                if ($noteContent.is(':visible')) {
                    $noteContent.slideUp(200);
                } else {
                    $noteText.text(noteData || 'Chưa có ghi chú.');
                    $noteContent.slideDown(200);
                }
            });

            // Ẩn note khi click ra ngoài
            $(document).click(function(e) {
                if (!$(e.target).closest('.note-icon, .note-content').length) {
                    $('.note-content').slideUp(200);
                }
            });
        });

        function generateMobileCards() {
            const $rows = $('.student-row');
            const $mobileContainer = $('#mobileStudentList');

            $rows.each(function () {
                const $row = $(this);
                const studentId = $row.data('student-id');
                const studentName = $row.data('student-name');
                const status = $row.data('status');
                const absentReason = $row.data('absent-reason') || '';

                let statusBadge = '';
                let checkboxHtml = '';

                if (status === 'confirmed') {
                    statusBadge = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Đã xác nhận</span>';
                    checkboxHtml = `<input class="form-check-input attendance-checkbox" type="checkbox" value="${studentId}" style="transform: scale(1.2);">`;
                } else if (status === 'pending') {
                    statusBadge = '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Chưa phản hồi</span>';
                    checkboxHtml = `<input class="form-check-input attendance-checkbox" type="checkbox" value="${studentId}" style="transform: scale(1.2);">`;
                } else {
                    statusBadge = '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Xin vắng</span>';
                    checkboxHtml = '<span class="text-muted"><i class="fas fa-ban"></i></span>';
                }

                const card = $(`
                <div class="mobile-student-card card mb-2 border-0 shadow-sm"
                     data-student-id="${studentId}"
                     data-student-name="${studentName}"
                     data-status="${status}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold">${studentName}</h6>
                                    <span class="badge bg-light text-dark">${studentId}</span>
                                </div>
                                <div class="mb-2">${statusBadge}</div>
                                ${absentReason ? `<small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>${absentReason}</small>` : ''}
                            </div>
                            <div class="ms-3 d-flex align-items-center">
                                <div class="text-center">
                                    <small class="text-muted d-block mb-1">Có mặt</small>
                                    ${checkboxHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);

                $mobileContainer.append(card);
            });
        }

        function checkAllAttendance() {
            $('.attendance-checkbox').each(function () {
                const $container = $(this).closest('.student-row, .mobile-student-card');
                if ($container.is(':visible') && $container.data('status') !== 'absent') {
                    $(this).prop('checked', true);
                }
            });
            updateCounts();
        }

        function uncheckAllAttendance() {
            $('.attendance-checkbox').prop('checked', false);
            updateCounts();
        }

        function updateCounts() {
            const checkedCount = $('.attendance-checkbox:checked').length;
            $('#selectedCount, #selectedCountMobile, #attendedStudents').text(checkedCount);
        }

        function saveAttendance() {
            const attendanceData = $('.attendance-checkbox:checked').map(function () {
                return $(this).val();
            }).get();

            if (confirm(`Lưu điểm danh cho ${attendanceData.length} sinh viên?`)) {
                console.log('Attendance data:', attendanceData);
                alert('Đã lưu điểm danh thành công!');
                bootstrap.Modal.getInstance($('#attendanceModal')[0]).hide();
            }
        }
    </script>
@endpush
