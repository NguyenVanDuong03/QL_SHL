@extends('layouts.teacher')

@section('title', 'Chi tiết sinh hoạt lớp')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'teacher.class-session.index'],
        ['label' => 'Lịch sinh hoạt lớp', 'url' => 'teacher.class-session.fixed-class-activitie'],
        ['label' => 'Chi tiết sinh hoạt lớp']
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
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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

        .stat-box {
            min-height: 100px;
            border: 1px solid #f1f1f1;
            transition: box-shadow 0.2s ease;
        }

        .stat-box:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }


    </style>
@endpush

@section('main')
    <div class="container-fluid mt-4">
        <div class="mx-3">
            <div class="mb-2">
                <a href="{{ route('teacher.class-session.detailFixedClassActivitie') }}"
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
                            <div class="d-flex justify-content-end gap-2">
                                @if(($data['getClassSessionRequest']->status ?? 0) == 1)
                                    <button type="button" class="btn btn-info" data-bs-target="#attendanceModal"
                                            data-bs-toggle="modal">
                                        <i class="fas fa-users me-2"></i>Danh sách tham gia
                                    </button>
                                    <button type="button"
                                            class="btn btn-success btn-class-session-done {{ $data['getClassSessionRequest']->proposed_at < now() ? '' : 'disabled' }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDoneModal">
                                        <i class="fas fa-check me-2"></i>Hoàn thành
                                    </button>
                                @endif
                            </div>
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
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->room->name }}</p>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-lg">
            <div class="modal-content">
                <div class="modal-header text-black">
                    <h5 class="modal-title" id="attendanceModalLabel">
                        <i class="fas fa-users me-2"></i>Danh sách sinh viên
                    </h5>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="bg-light p-2 p-md-3 border-bottom">
                        <div class="row text-center g-3 align-items-stretch">
                            <div class="col-6 col-md-2">
                                <div
                                    class="stat-box p-3 bg-white rounded d-flex flex-column justify-content-center align-items-center h-100">
                                    <h6 class="mb-0 text-primary fs-5 fw-bold"
                                        id="totalStudents">{{ $data['getTotalStudentsByClass'] }}</h6>
                                    <small class="text-muted d-block">Tổng số</small>
                                </div>
                            </div>

                            @foreach($data['getAttendanceStatusSummary'] ?? [] as $status)
                                <div class="col-6 col-md-2">
                                    <div
                                        class="stat-box p-3 bg-white rounded d-flex flex-column justify-content-center align-items-center h-100">
                                        <h6 class="mb-0 text-success fs-5 fw-bold">{{ $status['count'] ?? 0 }}</h6>
                                        <small class="text-muted d-block">{{ $status['status_text'] ?? '' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-md-none" id="mobileView">
                        <div id="mobileStudentList" class="p-2">
                        </div>
                    </div>

                    <form id="attendanceForm">
                        <input type="hidden" name="session-request-id" class="session_request_id"
                               value="{{ $data['getClassSessionRequest']->id }}">
                        <input type="hidden" name="study-class-id" class="study_class_id"
                               value="{{ $data['getClassSessionRequest']->study_class_id }}">
                        <div class="d-none d-md-block">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light sticky-top">
                                    <tr>
                                        <th scope="col" style="width: 8%">#</th>
                                        <th scope="col" style="width: 30%">Tên sinh viên</th>
                                        <th scope="col" style="width: 20%">Trạng thái</th>
                                        <th scope="col" style="width: 35%">Lý do vắng</th>
                                        <th scope="col" style="width: 7%" class="text-center">Điểm danh</th>
                                    </tr>
                                    </thead>
                                    <tbody id="studentsTableBody">
                                    @if($data['students'])
                                        @php
                                            $statusColors = [
                                                -1 => 'warning',
                                                0  => 'primary',
                                                1  => 'secondary',
                                                2  => 'success',
                                                3  => 'danger',
                                            ];
                                        @endphp
                                        @foreach($data['students'] as $student)
                                            <tr class="student-row"
                                                data-proposed-at="{{ $data['getClassSessionRequest']->proposed_at }}"
                                                data-class-request-status="{{ $data['getClassSessionRequest']->status }}"
                                                data-status="{{ $student->attendance_status }}"
                                                data-student-id="{{ $student->student_id }}"
                                                data-student-name="{{ $student->name }}"
                                                data-student-code="{{ $student->student_code }}"
                                                data-reason="{{ $student->reason ?? '' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="fw-medium">
                                                    <p class="m-0">{{ $student->name }}
                                                        @if($student->note)
                                                            <i class="fas fa-info-circle text-info ms-1 note-icon"
                                                               style="font-size: 0.8rem; cursor: pointer;"
                                                               data-note="{{ $student->note }}"></i>
                                                        @endif
                                                    </p>
                                                    <small>#{{ $student->student_code }}</small>
                                                    <div class="note-content" style="display: none; margin-top: 5px;">
                                                        <small class="text-primary bg-light p-2 rounded d-block">
                                                            <strong>Ghi chú:</strong> <span class="note-text"></span>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $statusColors[$student->attendance_status] ?? 'secondary' }}">
                                                        <i class="fas fa-check me-1"></i>{{ $student->attendance_status_text }}
                                                    </span>
                                                </td>
                                                <td><span class="text-muted">{{ $student->reason ?? '-' }}</span></td>
                                                @php
                                                    $statusIcons = [
                                                        -1 => 'fa-question',
                                                        0  => 'fa-clock',
                                                        1  => 'fa-times',
                                                        2  => 'fa-check',
                                                        3  => 'fa-exclamation-triangle',
                                                    ];
                                                @endphp
                                                <td class="text-center">
                                                    @if ($student->attendance_status == 0)
                                                        <input class="form-check-input attendance-checkbox"
                                                               type="checkbox"
                                                               name="student_ids[]"
                                                               value="{{ $student->student_id }}">
                                                    @else
                                                        <span class="text-muted"><i
                                                                class="fas {{ $statusIcons[$student->attendance_status] ?? -1 }}"></i></span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-users fa-2x mb-2"></i>
                                                <p class="mb-0">Không có sinh viên trong lớp này</p>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- No results -->
                        <div id="noResults" class="text-center py-4 d-none">
                            <i class="fas fa-search fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Không tìm thấy sinh viên</p>
                        </div>

                        <!-- Mobile view -->
                        <div id="mobileStudentList" class="d-md-none"></div>

                        @if($data['getClassSessionRequest']->proposed_at < now())
                            <div class="modal-footer p-2 p-md-3">
                                <!-- Mobile Footer -->
                                <div class="d-md-none w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-success"
                                                    onclick="checkAllAttendance()">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                    onclick="uncheckAllAttendance()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <span class="text-muted small">Đã chọn: <strong
                                                id="selectedCountMobile">0</strong></span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit"
                                                class="btn btn-success btn-submit-attendance submitAttendanceBtn">
                                            <i class="fas fa-save me-2"></i>Lưu điểm danh
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng
                                        </button>
                                    </div>
                                </div>

                                <!-- Desktop Footer -->
                                <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                    <div>
                                        <button type="button" class="btn btn-outline-success btn-sm me-2"
                                                onclick="checkAllAttendance()">
                                            <i class="fas fa-check-double me-1"></i>Chọn tất cả
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                onclick="uncheckAllAttendance()">
                                            <i class="fas fa-times me-1"></i>Bỏ chọn
                                        </button>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted me-3">Đã chọn: <strong
                                                id="selectedCount">0</strong></span>
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                            Đóng
                                        </button>
                                        <button type="submit" class="btn btn-success submitAttendanceBtn">
                                            <i class="fas fa-save me-2"></i>Lưu điểm danh
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-center align-items-center w-100 my-2">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Đóng
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận hoàn thành -->
    <div class="modal fade" id="confirmDoneModal" tabindex="-1" aria-labelledby="confirmDoneModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <form id="doneForm" method="POST"
                  action="{{ route('teacher.class-session.doneSessionClass', $data['getClassSessionRequest']->id) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="room_id" value="{{ $data['getClassSessionRequest']->room_id }}">
                <input type="hidden" name="type" value="{{ $data['getClassSessionRequest']->type }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDoneModalLabel">Xác nhận hoàn thành</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn đánh dấu buổi sinh hoạt lớp này là <strong>hoàn thành</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">Xác nhận</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(element) {
            element.select();
            element.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(element.value).then(function () {
                toastr.success('Đã sao chép vào clipboard!');
            });
        }

        function openMap(location) {
            if (location) {
                const encodedLocation = encodeURIComponent(location);
                window.open(`https://www.google.com/maps/search/${encodedLocation}`, '_blank');
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            generateMobileCards();
            updateCounts();

            // Search functionality
            $('#searchStudent').on('input', function () {
                const searchTerm = $(this).val().toLowerCase();
                let visibleCount = 0;

                $('.student-row, .mobile-student-card').each(function () {
                    const $this = $(this);
                    const studentName = $this.data('student-name').toLowerCase();
                    const studentId = $this.data('student-id');
                    const studentCode = $this.data('student-code').toLowerCase();

                    if (studentName.includes(searchTerm) || studentCode.includes(searchTerm)) {
                        $this.show();
                        if ($this.hasClass('student-row')) visibleCount++;
                    } else {
                        $this.hide();
                    }
                });

                $('#noResults').toggleClass('d-none', visibleCount > 0);
            });

            // Checkbox change handler
            $(document).on('change', '.attendance-checkbox', updateCounts);

            // Note icon click handler
            $(document).on('click', '.note-icon', function (e) {
                e.stopPropagation();
                const $this = $(this);
                const $noteContent = $this.closest('td').find('.note-content');
                const $noteText = $noteContent.find('.note-text');
                const noteData = $this.data('note');

                $('.note-content').not($noteContent).slideUp(200);
                $noteText.text(noteData || 'Chưa có ghi chú.');
                $noteContent.slideToggle(200);
            });

            // Hide notes when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.note-icon, .note-content').length) {
                    $('.note-content').slideUp(200);
                }
            });

            $('#attendanceModal').on('shown.bs.modal', function () {
                toggleSubmitButton();
            });

            $(document).on('change', '.attendance-checkbox', function () {
                toggleSubmitButton();
            });

            // Form submission
            $('#attendanceForm').on('submit', function (e) {
                e.preventDefault();

                const attendanceData = $('.attendance-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();
                const session_request_id = $('.session_request_id').val();
                const study_class_id = $('.study_class_id').val();

                $.ajax({
                    url: `{{ route('teacher.attendance.updateAttendance') }}`,
                    method: 'PATCH',
                    data: {
                        student_ids: attendanceData,
                        session_request_id: session_request_id,
                        study_class_id: study_class_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#attendanceModal').modal('hide');
                        toastr.success('Đã lưu điểm danh thành công!');
                        window.location.reload();
                    },
                    error: function (xhr) {
                        toastr.error('Đã xảy ra lỗi khi lưu điểm danh: ' + (xhr.responseJSON?.message || 'Lỗi không xác định'));
                    }
                });
            });
        });

        function generateMobileCards() {
            const $mobileContainer = $('#mobileStudentList');
            $mobileContainer.empty();

            $('.student-row').each(function () {
                const $row = $(this);
                const studentId = $row.data('student-id');
                const studentName = $row.data('student-name');
                const status = $row.data('status');
                const studentCode = $row.data('student-code');
                const absentReason = $row.data('reason') || '';
                const proposedAt = $row.data('proposed-at');
                const classRequestStatus = $row.data('class-request-status');

                let statusBadge = '';
                let checkboxHtml = '';

                if (status === -1) {
                    statusBadge = '<span class="badge bg-warning"><i class="fas fa-question me-1"></i>Chưa xác nhận</span>';
                    if (proposedAt && new Date(proposedAt) < new Date() && classRequestStatus === 1) {
                        checkboxHtml = `<input class="form-check-input attendance-checkbox" type="checkbox" name="student_ids[]" value="${studentId}" style="transform: scale(1.2);">`;
                    } else
                        checkboxHtml = '<span class="text-muted"><i class="fas fa-question"></i></span>';

                } else if (status === 0) {
                    statusBadge = '<span class="badge bg-primary"><i class="fas fa-clock me-1"></i>Xác nhận</span>';
                    if (proposedAt && new Date(proposedAt) < new Date() && classRequestStatus === 1) {
                        checkboxHtml = `<input class="form-check-input attendance-checkbox" type="checkbox" name="student_ids[]" value="${studentId}" style="transform: scale(1.2);">`;
                    } else
                        checkboxHtml = '<span class="text-muted"><i class="fas fa-clock"></i></span>';

                } else if (status === 1) {
                    statusBadge = '<span class="badge bg-secondary"><i class="fas fa-times me-1"></i>Xin vắng</span>';
                    if (proposedAt && new Date(proposedAt) < new Date() && classRequestStatus === 1) {
                        checkboxHtml = `<input class="form-check-input attendance-checkbox" type="checkbox" name="student_ids[]" value="${studentId}" style="transform: scale(1.2);">`;
                    } else
                    checkboxHtml = '<span class="text-muted"><i class="fas fa-times"></i></span>';
                } else if (status === 2) {
                    statusBadge = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Có mặt</span>';
                    if (proposedAt && new Date(proposedAt) < new Date() && classRequestStatus === 1) {
                        checkboxHtml = `<input class="form-check-input attendance-checkbox" type="checkbox" name="student_ids[]" checked value="${studentId}" style="transform: scale(1.2);">`;
                    } else
                    checkboxHtml = `'<span class="text-muted"><i class="fas fa-check"></i></span>`;

                } else if (status === 3) {
                    statusBadge = '<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>Vắng mặt</span>';
                    if (proposedAt && new Date(proposedAt) < new Date() && classRequestStatus === 1) {
                        checkboxHtml = `<input class="form-check-input attendance-checkbox" type="checkbox" name="student_ids[]" value="${studentId}" style="transform: scale(1.2);">`;
                    } else
                    checkboxHtml = '<span class="text-muted"><i class="fas fa-exclamation-triangle"></i></span>';
                }

                const card = $(`
            <div class="mobile-student-card card mb-2 border-0 shadow-sm"
                 data-student-id="${studentId}"
                 data-student-name="${studentName}"
                 data-status="${status}"
                 data-student-code="${studentCode}">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold">${studentName}</h6>
                                <span class="badge bg-light text-dark">${studentCode}</span>
                            </div>
                            <div class="mb-2">${statusBadge}</div>
                            ${absentReason ? `<small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>${absentReason}</small>` : ''}
                        </div>
                        <div class="ms-3 d-flex align-items-center">
                            <div class="text-center">
                                <small class="text-muted d-block mb-1">Điểm danh</small>
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

        // Hàm kiểm tra xem có checkbox nào được chọn không
        function toggleSubmitButton() {
            const checkedCount = $('.attendance-checkbox:checked').length;
            // $('.submitAttendanceBtn').prop('disabled', checkedCount === 0);
            $('#selectedCount, #selectedCountMobile').text(checkedCount);
        }

        function checkAllAttendance() {
            $('.attendance-checkbox').each(function () {
                const $container = $(this).closest('.student-row, .mobile-student-card');
                const proposedAt = $container.data('proposed-at');
                const classRequestStatus = $container.data('class-request-status');
                const status = $container.data('status');

                if ($container.is(':visible') && status !== 1 && status !== 3 && classRequestStatus == 1 && new Date(proposedAt) < new Date()) {
                    $(this).prop('checked', true);
                }
            });
            updateCounts();
            toggleSubmitButton();
        }

        function uncheckAllAttendance() {
            $('.attendance-checkbox').prop('checked', false);
            updateCounts();
            toggleSubmitButton()
        }

        function updateCounts() {
            const checkedCount = $('.attendance-checkbox:checked').length;
            $('#selectedCount, #selectedCountMobile, #attendedStudents').text(checkedCount);
        }
    </script>
@endpush
