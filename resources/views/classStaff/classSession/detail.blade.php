@extends('layouts.classStaff')

@section('title', 'Chi tiết sinh hoạt lớp')

@push('styles')
    <style>
        :root {
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --blue-500: #3b82f6;
            --blue-600: #2563eb;
        }

        .card {
            border: none;
            border-radius: 8px;
            background-color: var(--gray-50);
        }

        .card-header {
            border-radius: 8px 8px 0 0 !important;
            background-color: var(--gray-100);
        }

        .form-control[readonly] {
            background-color: var(--gray-50);
        }

        .input-group .btn {
            border-left: 0;
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
                background-color: var(--gray-50);
            }

            .modal-fullscreen-md-down .modal-body {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .mobile-student-card {
                border-radius: 8px !important;
                background-color: var(--gray-50);
            }

            .btn {
                min-height: 44px;
                padding: 0.5rem 1rem;
            }

            .btn-close {
                padding: 0.75rem;
            }
        }

        @media (min-width: 768px) {
            .table th {
                border-top: none;
                font-weight: 600;
                font-size: 0.875rem;
                color: var(--gray-700);
            }

            .table td {
                vertical-align: middle;
                font-size: 0.875rem;
                color: var(--gray-700);
            }

            .sticky-top {
                position: sticky;
                top: 0;
                z-index: 10;
                background-color: var(--gray-50) !important;
            }
        }

        .card {
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }

        #mobileStudentList {
            overflow-y: auto;
            background-color: var(--gray-50);
        }

        .stat-box {
            min-height: 100px;
            border: 1px solid var(--gray-100);
            transition: box-shadow 0.2s ease;
            background-color: var(--gray-50);
        }

        .stat-box:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid">
        <!-- Header với thông tin khóa học -->
        <div class="mx-3">
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-info-circle text-secondary me-2"></i>Thông tin sinh hoạt
                                lớp</h5>
                            <!-- Status badge -->
                            @php
                                $statusConfig = [
                                   0 => ['text' => 'Chờ duyệt', 'class' => 'bg-warning', 'icon' => 'fas fa-clock'],
                                   1 => ['text' => 'Đã duyệt', 'class' => 'bg-success', 'icon' => 'fas fa-check-circle'],
                                   2 => ['text' => 'Đã từ chối', 'class' => 'bg-danger', 'icon' => 'fas fa-times-circle'],
                                   3 => ['text' => 'Đã kết thúc', 'class' => 'bg-secondary', 'icon' => 'fas fa-archive']
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
                                    <p class="text-dark mb-0" style="white-space: pre-line;">
                                        {{ $data['getClassSessionRequest']->content ?? 'Không có nội dung' }}
                                    </p>
                                </div>
                            </div>

                            @php
                                $statusTexts = [
                                    -1 => '', // Chưa xác nhận
                                    0  => 'Xác nhận tham gia', // Xác nhận
                                    1  => 'Xin vắng', // Xin vắng
                                    2  => 'Có mặt', // Có mặt
                                    3  => 'Vắng mặt',  // Vắng mặt
                                ];
                            @endphp
                                <!-- Nội dung xin vắng -->
                            @if((isset($data['getAttendanceStudent']) && $data['getAttendanceStudent']->status == 1))
                                <div class="mb-4">
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Lý do xin vắng
                                        </h6>
                                        <p class="mb-0">{{ $data['getAttendanceStudent']->reason }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Action buttons -->
                            <p class="text-end text-danger-emphasis bg-primary-subtle fw-bold px-2">{{ isset($data['getAttendanceStudent'])? $statusTexts[$data['getAttendanceStudent']->status] : $statusTexts[-1] }}</p>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="">
                                    <a href="{{ route('class-staff.class-session.index') }}"
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                                    </a>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-info" data-bs-target="#attendanceModal"
                                            data-bs-toggle="modal">
                                        <i class="fas fa-users me-2"></i>Danh sách lớp
                                    </button>
                                    @if($data['getClassSessionRequest']->status != 3)
                                        <button type="button"
                                                class="btn btn-success btn-confirm-attendance"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmAttendanceModal"
                                            {{ !isset($data['getAttendanceStudent']) ? '' : ($data['getAttendanceStudent']->status == 1 ? '' : 'disabled') }}>
                                            <i class="fas fa-check me-2"></i>{{ !isset($data['getAttendanceStudent']) ? 'Xác nhận tham gia' : ($data['getAttendanceStudent']->status == 1 ? 'Xác nhận tham gia' : 'Đã xác nhận') }}
                                        </button>
                                        <button type="button"
                                                class="btn btn-warning btn-request-absence"
                                                data-bs-toggle="modal"
                                                data-bs-target="#requestAbsenceModal"
                                                data-reason="{{ $data['getAttendanceStudent']->reason ?? '' }}">
                                            <i class="fas fa-times me-2"></i>Xin vắng
                                        </button>
                                    @else
                                        <a href="#" class="btn btn-secondary">
                                            <i class="fas fa-check me-2"></i>Tạo báo cáo
                                        </a>
                                    @endif
                                </div>
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
                                <label class="form-label fw-bold" style="color: var(--gray-600);">Thời gian:</label>
                                <p class="text-dark mb-0 fs-5">
                                    <i class="fas fa-calendar-alt me-2" style="color: var(--gray-600);"></i>
                                    {{ \Carbon\Carbon::parse($data['getClassSessionRequest']->proposed_at)->format('H:i d/m/Y') }}
                                </p>
                            </div>

                            @if($position == 0)
                                <!-- Trực tiếp tại trường -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold" style="color: var(--gray-600);">Phòng học:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->room->name }}</p>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Sinh viên cần có mặt tại phòng học đúng giờ quy định.</small>
                                </div>
                                @if($data['getClassSessionRequest']->status == 3)
                                    <button type="button" class="btn btn-secondary">
                                        <i class="fas fa-pager me-2"></i>Tạo báo cáo
                                    </button>
                                @endif
                            @elseif($position == 1)
                                <!-- Trực tuyến -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold" style="color: var(--gray-600);">Nền tảng:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->meeting_type ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold" style="color: var(--gray-600);">Mã cuộc
                                        họp:</label>
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
                                        <label class="form-label fw-bold" style="color: var(--gray-600);">Mật
                                            khẩu:</label>
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
                                    <label class="form-label fw-bold" style="color: var(--gray-600);">Địa điểm:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->location ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <div class="d-grid">
                                        <button class="btn btn-primary"
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

    <!-- Attendance Modal (View Only) -->
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

                            @foreach($data['getAttendanceStatusSummary'] as $status)
                                <div class="col-6 col-md-2">
                                    <div
                                        class="stat-box p-3 bg-white rounded d-flex flex-column justify-content-center align-items-center h-100">
                                        <h6 class="mb-0 fs-5 fw-bold"
                                            style="color: var(--gray-600);">{{ $status['count'] }}</h6>
                                        <small class="text-muted d-block">{{ $status['status_text'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="p-2 p-md-3 border-bottom">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchStudent"
                                   placeholder="Tìm kiếm sinh viên...">
                        </div>
                    </div>

                    <div class="d-md-none" id="mobileView">
                        <div id="mobileStudentList" class="p-2">
                        </div>
                    </div>

                    <!-- HTML/PHP structure for attendance table -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light sticky-top">
                                <tr>
                                    <th scope="col" style="width: 8%">#</th>
                                    <th scope="col" style="width: 30%">Tên sinh viên</th>
                                    <th scope="col" style="width: 25%">Trạng thái</th>
                                    <th scope="col" style="width: 37%">Lý do vắng</th>
                                </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                @if($data['students'])
                                    @php
                                        $statusColors = [
                                            -1 => 'warning', // Chưa xác nhận tham gia
                                            0  => 'primary', // Xác nhận tham gia
                                            1  => 'secondary', // Vắng mặt có phép
                                            2  => 'success', // Có mặt
                                            3  => 'danger',  // Vắng mặt
                                        ];
                                    @endphp
                                    @foreach($data['students'] as $student)
                                        <tr class="student-row"
                                            data-status="{{ $student->attendance_status }}"
                                            data-student-id="{{ $student->student_id }}"
                                            data-student-name="{{ $student->name }}"
                                            data-student-code="{{ $student->student_code }}"
                                            data-reason="{{ $student->reason ?? '-' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-medium">
                                                <p class="m-0">{{ $student->name }}</p>
                                                <small>#{{ $student->student_code }}</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $statusColors[$student->attendance_status] ?? 'warning' }}">
                                                    <i class="fas fa-check me-1"></i>{{ $student->attendance_status_text }}
                                                </span>
                                            </td>
                                            <td><span class="text-muted">{{ $student->reason ?? '-' }}</span></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
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

                    <div class="modal-footer p-2 p-md-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận tham gia -->
    <div class="modal fade" id="confirmAttendanceModal" tabindex="-1" aria-labelledby="confirmAttendanceModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <form id="confirmAttendanceForm" method="POST"
                  action="{{ route('class-staff.class-session.confirmAttendance') }}">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmAttendanceModalLabel">Xác nhận tham gia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xác nhận tham gia buổi sinh hoạt lớp này?
                        <input type="hidden" name="class_session_request_id"
                               value="{{ $data['getClassSessionRequest']->id }}">
                        <input type="hidden" name="staus" value="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">Xác nhận</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal xin vắng -->
    <div class="modal fade" id="requestAbsenceModal" tabindex="-1" aria-labelledby="requestAbsenceModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <form id="requestAbsenceForm" method="POST"
                  action="{{ route('class-staff.class-session.updateAbsence')}}">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestAbsenceModalLabel">Xin vắng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reason" class="form-label fw-bold">Lý do xin vắng:</label>
                            <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                        </div>
                        <input type="hidden" name="class_session_request_id"
                               value="{{ $data['getClassSessionRequest']->id }}">
                        <input type="hidden" name="status" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning">Gửi yêu cầu</button>
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

        $(document).ready(function () {
            generateMobileCards();

            // Search functionality
            $('#searchStudent').on('input', function () {
                const searchTerm = $(this).val().toLowerCase();
                let visibleCount = 0;

                $('.student-row, .mobile-student-card').each(function () {
                    const $this = $(this);
                    const studentName = $this.data('student-name').toLowerCase();
                    const studentId = $this.data('student-id');

                    if (studentName.includes(searchTerm) || studentId.includes(searchTerm)) {
                        $this.show();
                        if ($this.hasClass('student-row')) visibleCount++;
                    } else {
                        $this.hide();
                    }
                });

                $('#noResults').toggleClass('d-none', visibleCount > 0);
            });

            // Form submission for confirm attendance
            $('#confirmAttendanceForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        $('#confirmAttendanceModal').modal('hide');
                        // toastr.success('Xác nhận tham gia thành công');
                        toastr.success(response.message, 'success');
                        window.location.reload();

                    },
                    error: function (xhr) {
                        toastr.error('Đã xảy ra lỗi: ' + (xhr.responseText || 'Lỗi không xác định'));
                    }
                });
            });

            // Form submission for request absence
            $('#requestAbsenceForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        $('#requestAbsenceModal').modal('hide');
                        // toastr.success('Gửi lý do vắng mặt thành công');
                        toastr.success(response.message, 'success');
                        window.location.reload();
                    },
                    error: function (xhr) {
                        toastr.error('Đã xảy ra lỗi: ' + (xhr.responseText || 'Lỗi không xác định'));
                    }
                });
            });

            $('.btn-request-absence').on('click', function () {
                const reason = $(this).data('reason') || '';
                $('#requestAbsenceForm #reason').val(reason);
                {{--$('#requestAbsenceForm').attr('action', '{{ route('class-staff.class-session.requestAbsence') }}');--}}
            });
        });

        function generateMobileCards() {
            const $mobileContainer = $('#mobileStudentList');
            $mobileContainer.empty();

            $('.student-row').each(function () {
                const $row = $(this);
                const studentId = $row.data('student-id');
                const studentCode = $row.data('student-code');
                const studentName = $row.data('student-name');
                const status = $row.data('status');
                const absentReason = $row.data('reason') || '';

                let statusBadge = '';
                if (status === 0) {
                    statusBadge = '<span class="badge bg-primary">Xác nhận</span>';
                } else if (status === 1) {
                    statusBadge = '<span class="badge bg-secondary">Xin vắng</span>';
                } else if (status === 2) {
                    statusBadge = '<span class="badge bg-success">Có mặt</span>';
                } else if (status === 3) {
                    statusBadge = '<span class="badge bg-danger">Vắng mặt</span>';
                } else if (status === -1) {
                    statusBadge = '<span class="badge bg-warning">Chưa xác nhận</span>';
                }

                const card = $(`
                    <div class="mobile-student-card card mb-2 border-0 shadow-sm"
                         data-student-id="${studentId}"
                         data-student-name="${studentName}"
                         data-status="${status}"
                         data-student-code="${studentCode}"
                         data-reason="${absentReason}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold">${studentName}</h6>
                                        <span class="badge bg-light text-dark">${studentCode}</span>
                                    </div>
                                    <div class="mb-2">${statusBadge}</div>
                                    ${absentReason !== '-' ? `<small style="color: var(--gray-700);"><i class="fas fa-exclamation-triangle me-1"></i>${absentReason}</small>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                $mobileContainer.append(card);
            });
        }
    </script>
@endpush
